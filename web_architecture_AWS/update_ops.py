''' Add_activities function '''

from boto.dynamodb2.items import Item
from boto.dynamodb2.exceptions import ItemNotFound

def add_activity(table, id, activity, response):
    try:
        item = table.get_item(id=id, consistent=True)
        addedActivity = set()
        if item['activities'] is None:
            item['activities'] = {activity}
            addedActivity.add(activity)
        else:
            activities = item['activities']
            if activity not in activities:
                activities.add(activity)
                addedActivity.add(activity)
            item['activities'] = activities
        item.save()
    except ItemNotFound as inf:
        response.status = 404 # "NOT FOUND"
        return {"errors": [{
                "not_found": {
                        "id": id
                        }
                }]
        }
    response.status = 200 # "OK"
    return {"data": {
            "type": "person",
            "id": id,
            "added": list(addedActivity)
            }
    }

def delete_activity(table, id, activity, response):
    try:
        item = table.get_item(id=id, consistent=True)
        deletedActivity = set()
        if item['activities'] is not None:
            activities = item['activities']
            if activity in activities:
                activities.remove(activity)
                deletedActivity.add(activity)
            item['activities'] = activities
        item.save()
    except ItemNotFound as inf:
        response.status = 404 # "NOT FOUND"
        return {"errors": [{
                "not_found": {
                        "id": id
                        }
                }]
        }
    response.status = 200 # "OK"
    return {"data": {
            "type": "person",
            "id": id,
            "deleted": list(deletedActivity)
            }
    }
