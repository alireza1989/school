#!/usr/bin/env python

'''
  Back end DB server for Assignment 3
'''

# Library packages
import argparse
import json
import sys
import time

# Installed packages
import boto.dynamodb2
import boto.dynamodb2.table
import boto.sqs

# Local imports
import create_ops
import retrieve_ops
import delete_ops
import update_ops

# Bottle import:
from bottle import response

AWS_REGION = "us-west-2"
TABLE_NAME_BASE = "activities"
Q_IN_NAME_BASE = "a3_back_in"
Q_OUT_NAME = "a3_out"

MAX_TIME_S = 3600 # One hour
MAX_WAIT_S = 20 # SQS sets max. of 20 s
DEFAULT_VIS_TIMEOUT_S = 60

def continue_checking_next_requests():
    opnum_to_process = last_opnum_processed+1
    global requests_in_queue
    while opnum_to_process in requests_in_queue:
        unprocessed_dups = requests_in_queue[opnum_to_process]
        i = 1
        for msgid in unprocessed_dups:
            process_msg_in_wait_queue(unprocessed_dups[msgid])
            i += 1

        del requests_in_queue[opnum_to_process]
        opnum_to_process = last_opnum_processed+1


def handle_args():
    argp = argparse.ArgumentParser(
        description="Backend for simple database")
    argp.add_argument('suffix', help="Suffix for queue base ({0}) and table base ({1})".format(Q_IN_NAME_BASE, TABLE_NAME_BASE))
    return argp.parse_args()

def setup():
    args = handle_args()

    #update table and queue names
    TABLE_NAME = TABLE_NAME_BASE + args.suffix
    Q_IN_NAME = Q_IN_NAME_BASE + args.suffix

    global table
    global q_in
    global q_out
    #DS to hold processed messages
    global processed_messages
    global requests_in_queue
    global last_opnum_processed
    # Empty dictionary to keep track of requests
    processed_messages = dict()
    requests_in_queue = dict()
    last_opnum_processed = 0

    #connect to DB
    try:
        table_conn = boto.dynamodb2.connect_to_region(AWS_REGION)
        if table_conn == None:
            sys.stderr.write("Could not connect to AWS region '{0}'\n".format(AWS_REGION))
            sys.exit(1)

        # Reading the table (connecting):
        table = boto.dynamodb2.table.Table(TABLE_NAME, connection=table_conn)

    except Exception as e:
        sys.stderr.write("Exception connecting to DynamoDB table {0}\n".format(TABLE_NAME))
        sys.stderr.write(str(e))
        sys.exit(1)


    #connect to a3_back_in and a3_out queues
    sqs_conn = boto.sqs.connect_to_region(AWS_REGION)
    q_in = sqs_conn.create_queue(Q_IN_NAME)
    q_out = sqs_conn.create_queue(Q_OUT_NAME)

# Idempotency Implementation:
# Check if the message is processed before
def check_if_message_processed_before(msg_id):
    if msg_id in processed_messages:
        return True
    else:
        return False

# Process the message read from in_q:
def db_process(body):
    operation_type = body['operation']

    # check the operation type:
    # call the appropriate function from locally imported modules
    if operation_type == "create":
        u_id = body['userID']
        request = body['request']
        name = body['name']
        db_op_result = create_ops.do_create(request, table, u_id, name, response)

    elif operation_type == "addActivity":
        u_id = body['userID']
        activity = body['activity']
        db_op_result = update_ops.add_activity(table, u_id, activity, response)

    elif operation_type == "deleteActivity":
        u_id = body['userID']
        activity = body['activity']
        db_op_result = update_ops.delete_activity(table, u_id, activity, response)

    elif operation_type == "retriveByName":
        name = body['name']
        db_op_result = retrieve_ops.retrieve_by_name(table, name, response)

    elif operation_type == "retriveByID":
        u_id = body['userID']
        db_op_result = retrieve_ops.retrieve_by_id(table, u_id, response)

    elif operation_type == "retriveAll":
        db_op_result = retrieve_ops.retrieve_all_users(table, response)

    elif operation_type == "deleteByName":
        name = body['name']
        db_op_result = delete_ops.delete_by_name(table, name, response)

    elif operation_type == "deleteById":
        u_id = body['userID']
        db_op_result = delete_ops.delete_by_id(table, u_id, response)

    out_body =  {'msg_id': body['msg_id'], 'action':operation_type , 'responseCode' : response.status , 'result' : db_op_result}

    return out_body

def get_already_processed_msg(msg_id):
    #Get the message response from data structure and it write to q_out:
    old_response = processed_messages[msg_id]
    old_response_json_str = json.dumps(old_response)
    old_msg = boto.sqs.message.Message()
    old_msg.set_body(old_response_json_str)
    q_out.write(old_msg)

def process_new_message(body):
    global last_opnum_processed
    last_opnum_processed += 1
    # The message returned from database
    fresh_response = db_process(body)

    #Put the databse response to the data structure
    processed_messages[body['msg_id']] = fresh_response

    #convert the response to json format
    fresh_response_json_str = json.dumps(fresh_response)

    # Add the message(response) to the out queue
    new_msg = boto.sqs.message.Message()
    new_msg.set_body(fresh_response_json_str)
    q_out.write(new_msg)

def process_msg_in_wait_queue(body):
    msg_id = body['msg_id']
    if check_if_message_processed_before(msg_id):
        get_already_processed_msg(msg_id)
    else:
        process_new_message(body)





if __name__ == "__main__":
    setup()

    wait_start = time.time()
    while True:
        msg_in = q_in.read(wait_time_seconds=MAX_WAIT_S)
        if msg_in:
            body = json.loads(msg_in.get_body())
            msg_id = body['msg_id']
            if check_if_message_processed_before(msg_id):
                get_already_processed_msg(msg_id)
            else:
                if body['opnum'] == last_opnum_processed + 1:
                    process_new_message(body)

                    continue_checking_next_requests()
                else:
                    if body['opnum'] in requests_in_queue:
                        unprocessed_dups = requests_in_queue[body['opnum']]
                        unprocessed_dups[msg_in.id] = body
                    else:
                        unprocessed_dups = dict()
                        unprocessed_dups[msg_in.id] = body
                    requests_in_queue[body['opnum']] = unprocessed_dups


            q_in.delete_message(msg_in)
            wait_start = time.time()
        elif time.time() - wait_start > MAX_TIME_S:
            print "\nNo messages on input queue for {0} seconds. Server no longer reading response queue {1}.".format(MAX_TIME_S, q_out.name)
            break
        else:
            pass
