'''
   Ops for the frontend.
'''
import sys

# Standard library packages
import json

# Installed packages
import boto.sqs

# Imports of unqualified names
from bottle import post, get, put, delete, request, response

# Local modules
import SendMsg

# Constants
AWS_REGION = "us-west-2"

Q_IN_NAME_BASE = 'a3_in'
Q_OUT_NAME = 'a3_out'

def setup_op_counter():
    global seq_num
    zkcl = send_msg_ob.get_zkcl()
    if not zkcl.exists('/SeqNum'):
        zkcl.create('/SeqNum', "0")
    else:
        zkcl.set('/SeqNum', "0")

    seq_num = zkcl.Counter('/SeqNum')
    seq_num._inner_change(1)

def abort(response, status, errors):
    response.status = status
    return {"errors": errors}

# Respond to health check
@get('/')
def health_check():
    response.status = 200
    return "Healthy"

# EXTEND:
# Define all the other REST operations here ...
@post('/users')
def create_route():
    ct = request.get_header('content-type')
    if ct != 'application/json':
        return abort(response, 400, [
            "request content-type unacceptable:  body must be "
            "'application/json' (was '{0}')".format(ct)])
    out = {'operation': "create", 'userID': request.json["id"], 'name': request.json["name"], 'request': request.urlparts, 'opnum' : seq_num.value}
    return set_msg(out)

@get('/users/<id>')
def get_id_route(id):
    out = {'operation': "retriveByID", 'userID': int(id), 'opnum' : seq_num.value}
    return set_msg(out)

@get('/names/<name>')
def get_name_route(name):
    out = {'operation': "retriveByName", 'name': name, 'opnum' : seq_num.value}
    return set_msg(out)

@get('/users')
def get_all_users_route():
    out = {'operation': "retriveAll", 'opnum' : seq_num.value}
    return set_msg(out)

@put('/users/<id>/activities/<activity>')
def add_activity_route(id, activity):
    out = {'operation': "addActivity", 'userID': int(id), 'activity': activity, 'opnum' : seq_num.value}
    return set_msg(out)

@delete('/users/<id>/activities/<activity>')
def delete_activity_route(id, activity):
    out = {'operation': "deleteActivity", 'userID': int(id), 'activity': activity, 'opnum' : seq_num.value}
    return set_msg(out)

@delete('/names/<name>')
def delete_name_route(name):
    out = {'operation': "deleteByName", 'name': name, 'opnum' : seq_num.value}
    return set_msg(out)

@delete('/users/<id>')
def delete_id_route(id):
    out = {'operation': "deleteById", 'userID': int(id), 'opnum' : seq_num.value}
    return set_msg(out)

def set_msg(out):
    global seq_num
    seq_num += 1
    json_dump = json.dumps(out)

    msg_a = boto.sqs.message.Message()
    msg_a.set_body(json_dump)
    msg_b = boto.sqs.message.Message()
    msg_b.set_body(json_dump)

    result = send_msg_ob.send_msg(msg_a, msg_b)
    return make_response(result, response)

def make_response(result, response):
    response.status = result['responseCode']
    return result['result']


'''
   Boilerplate: Do not modify the following function. It
   is called by frontend.py to inject the names of the two
   routines you write in this module into the SendMsg
   object.  See the comments in SendMsg.py for why
   we need to use this awkward construction.

   This function creates the global object send_msg_ob.

   To send messages to the two backend instances, call

       send_msg_ob.send_msg(msg_a, msg_b)

   where

       msg_a is the boto.message.Message() you wish to send to a3_in_a.
       msg_b is the boto.message.Message() you wish to send to a3_in_b.

       These must be *distinct objects*. Their contents should be identical.
'''
def set_send_msg(send_msg_ob_p):
    global send_msg_ob
    send_msg_ob = send_msg_ob_p.setup(write_to_queues, set_dup_DS)

'''
   EXTEND:
   Set up the input queues and output queue here
   The output queue reference must be stored in the variable q_out
'''

try:
    conn = boto.sqs.connect_to_region(AWS_REGION)
    if conn == None:
        sys.exit(1)
    q_out = conn.create_queue(Q_OUT_NAME)
    q_in_a = conn.create_queue(Q_IN_NAME_BASE + "_a")
    q_in_b = conn.create_queue(Q_IN_NAME_BASE + "_b")
except Exception as e:
    sys.exit(1)

def write_to_queues(msg_a, msg_b):
    # EXTEND:
    # Send msg_a to a3_in_a and msg-b to a3_in_b
    q_in_a.write(msg_a)
    q_in_b.write(msg_b)

'''
   EXTEND:
   Manage the data structures for detecting the first and second
   responses and any duplicate responses.
'''

# Define any necessary data structures globally here

# Data structures for storing messages sent to the backend
sent_messages = {}

def is_first_response(id):
    # EXTEND:
    # Return True if this message is the first response to a request
    if sent_messages[id]['responseNum'] == 0:
        return True
    else:
        return False

    pass

def is_second_response(id):
    # EXTEND:
    # Return True if this message is the second response to a request
    if sent_messages[id]['responseNum'] == 1:
        return True
    else:
        return False
    pass

def get_response_action(id):
    # EXTEND:
    # Return the action for this message
    return sent_messages[id]['action']
    pass

def get_partner_response(id):
    # EXTEND:
    # Return the id of the partner for this message, if any
    return sent_messages[id]['partner']
    pass

def mark_first_response(id):
    # EXTEND:
    # Update the data structures to note that the first response has been received

    # Set the number of responses received for this message to 1
    sent_messages[id]['responseNum'] = 1

    # Get the id of the partner message
    partner_id = sent_messages[id]['partner']

    # Set the number of responses received for the partner to 1 to track idempotency
    sent_messages[partner_id]['responseNum'] = 1
    pass

def mark_second_response(id):
    # EXTEND:
    # Update the data structures to note that the second response has been received

    #Set the number of responses received for this message to 2
    sent_messages[id]['responseNum'] = 2

    # Get the id of the partner message
    partner_id = sent_messages[id]['partner']

    #Set the number of responses received for the partner to 2 to track idempotency
    sent_messages[partner_id]['responseNum'] = 2
    pass

def clear_duplicate_response(id):
    # EXTEND:
    # Do anything necessary (if at all) when a duplicate response has been received
    pass

def set_dup_DS(action, sent_a, sent_b):
    '''
       EXTEND:
       Set up the data structures to identify and detect duplicates
       action: The action to perform on receipt of the response.
               Opaque data type: Simply save it, do not interpret it.
       sent_a: The boto.sqs.message.Message() that was sent to a3_in_a.
       sent_b: The boto.sqs.message.Message() that was sent to a3_in_b.

               The .id field of each of these is the message ID assigned
               by SQS to each message.  These ids will be in the
               msg_id attribute of the JSON object returned by the
               response from the backend code that you write.
    '''
    # Each message stores the id of the partner message and the number of responses received to identify second/duplicate response for the same request
    sent_messages[sent_a.id] = {'partner': sent_b.id, 'action': action, 'responseNum': 0}
    sent_messages[sent_b.id] = {'partner': sent_a.id, 'action': action, 'responseNum': 0}
    pass
