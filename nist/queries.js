printjson(db.getCollection('calls').find({}, { _id : 0, 'r' : 1, 'via' : 1}).toArray())

printjson(db.getCollection('calls').find({}, { _id : 0, 'r' : 1, 'via' : 1}).sort({'U' : -1}).toArray())
