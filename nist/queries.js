db.getCollection('calls').aggregate(
[
{$match : {'U' : {'$gte' : new Date().getTime() / 1000 - 86400}}}
]
)

printjson(db.getCollection('calls').find({}, { _id : 0, 'r' : 1, 'via' : 1}).toArray())

printjson(db.getCollection('calls').find({}, { _id : 0, 'r' : 1, 'via' : 1}).sort({'U' : -1}).toArray())

db.getCollection('calls').aggregate(
[
{$match : {'U' : {'$gte' : new Date().getTime() / 1000 - 86400}, offset : {$exists : true}}},
{$project : {absoff : {$abs : '$offset'}}},
{$sort : {absoff : -1}},
{$limit : 20}
]
)
