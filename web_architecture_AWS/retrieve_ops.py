''' Retrieve operations'''

# Installed packages
from boto.dynamodb2.items import Item
from boto.dynamodb2.exceptions import ItemNotFound

def retrieve_by_id(table, id, response):
    try:
        item = table.get_item(id=id, consistent=True)
        itemActivities = set()

    except ItemNotFound as inf:
        response.status = 404
        return {"errors": [{
                        "not_found": {
                            "id": id}
                        }]
                    }

    response.status = 200 # "ok"

    if item['activities'] is None:
        itemActivities = []
    else:
        itemActivities = item['activities']

    return {
             "data": {
             "type": "person",
             "id": id,
             "name": item['name'],
             "activities": list (itemActivities)
             }
     }


def retrieve_by_name(table, name, response):
    item_set = table.scan(name__eq=name) # Returns a set of items which match the given name

    all_items = list(item_set)

    if(len(all_items) == 0):
        response.status = 404
        return {"errors": [{
                        "not_found": {
                            "name": name}
                        }]
                    }
    item_activities = set()
    item_id = None

    for item in all_items: # Can contain a maximum of one item with the given name
        item_id = item['id']
        item_activities = item['activities']

    response.status = 200 # "ok"

    if item_activities is None:
        item_activities = []

    return {
            "data": {
            "type": "person",
            "id": int(item_id),
            "name": name,
            "activities": list (item_activities)
            }
    }

def retrieve_all_users(table, response):
    users_set = table.scan()  # Returns a set of all the users present in the table
    all_users = list(users_set)

    json_response = {}
    json_response["data"] = []

    response.status = 200 # "OK"

    if(len(all_users) == 0):
        return{"data":[{
               "type":"users",
               "id":[],
             }]}

    for user in all_users:
        json_response["data"].append({"type":"users", "id":int(user['id'])})

    return json_response
