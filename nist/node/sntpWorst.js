module.exports = class sntpWorst {
	
	config() { 	this.daysBack = 2;	}
	
	constructor() {
		this.config();
		this.SBack = this.daysBack * 86400;
		const MongoClient = require('mongodb').MongoClient;
		this.omclip = MongoClient.connect('mongodb://localhost/');
	}	

	async get() {

		if (!this.coll) {
			const cli = await this.omclip;
			this.coll = cli.db('sntp4').collection('calls');
		}

        return await this.coll.aggregate(
            [
                {$match : {'U' : {'$gte' : ((new Date().getTime()) / 1000) - this.SBack }, offset : {$exists : true}}},
                {$project : {absoff : {$abs : '$offset'}, _id : 0, U : 1, r : 1}},
                {$sort : {absoff : -1}},
                {$limit : 20}
            ]
        ).toArray();  
    }
}

if (require.main === module) { 

	(async() => {
		const ref = module.exports;
		const o = new ref();
		const r = await Promise.resolve(o.get());
		console.log(r);
	
	})();

}
