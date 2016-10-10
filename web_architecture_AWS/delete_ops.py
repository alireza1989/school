''' Delete operations '''

# Installed packages
from boto.dynamodb2.items import Item
from boto.dynamodb2.exceptions import ItemNotFound

def delete_by_id(table, id, response):
    try:
        item = table.get_item(id=id, consistent=True)

    except ItemNotFound as inf:
        response.status = 404
        return {"errors": [{
                        "not_found": {
                            "id": id}
                        }]
                    }
    item.delete()
    response.status = 200
    return {"data": {
                "type": "person",
                "id": id }
           }

# Delete a user by name Function:

def delete_by_name(table, name, response):
    try:
        results = table.scan(name__eq=name, limit=1)
        result_id = None
        for res in results:
            result_id = res['id']
        if result_id is None:
            raise ItemNotFound('No such instance of '+name+' exists')
        table.delete_item(id=result_id)
    except ItemNotFound as inf:
        response.status = 404
        return {"errors": [{
                        "not_found": {
                            "name": name}
                        }]
                    }
    response.status = 200
    return {"data": {
                "type": "person",
                "id": int(result_id) }
           }
