const http = require('http');
const url = require('url');
const hostname = '127.0.0.1';
const port = 3000;

const sntpWorst = require('./sntpWorst.js');

class myMongoDBServer {
  constructor() {
    
    this.sntpWoO = new sntpWorst(); 
    this.initHTServer();
  }

 async doq() {   return JSON.stringify(await this.sntpWoO.get());  }

 async doHTr(req, res) {
    const json = await this.doq();
    res.statusCode = 200;
    res.setHeader('Content-Type', 'application/json');
    res.end(json);
  }

  initHTServer() {
    const self = this;
    const server = http.createServer((req, res) => { self.doHTr(req, res);   });
    server.listen(port, hostname, () => { console.log(`Server running at http://${hostname}:${port}/`);  });
  } // func
} // class

new myMongoDBServer();

