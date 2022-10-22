class sntpWorst {
	
	constructor()	   {					this.omclip = require('mongodb').MongoClient.connect('mongodb://localhost/');	}	
	async defineColl() { this.coll = (await this.omclip).db('sntp4').collection('calls');	}
	async get() {
		if (!this.coll) await this.defineColl();
        return await this.coll.aggregate(
            [
                {$match : {'U' : {'$gte' : new Date().getTime() / 1000 - 172800 }, offset : {$exists : true}}},
                {$project : {absoff : {$abs : '$offset'}, _id : 0, U : 1, r : 1}},
                {$sort : {absoff : -1}},
                {$limit : 20}
            ]
        ).toArray();  
    }
	
	static async fromcli() {
		console.log(await new sntpWorst().get());
		process.exit();
	}	
}
if (require.main === module) sntpWorst.fromcli();
module.exports = sntpWorst;
