<?php

require_once('/opt/kwynn/mongodb2.php');
require_once('/opt/kwynn/lock.php');
require_once('validIP.php');
require_once('backoff.php');

class nist_servers extends dao_generic_2 {

    const dbName = 'sntp3';
	const srvn = 7;
	const ip4n = 5;
	const ip6n = 2;
	const baseip4 = '129.6.15.';
	const baseip6 = '2610:20:6f15:15::';
	
	const ipbase = 26;
	const org = 'NIST';
	
	const backe = 1.2;
	const mind  = 4;
	const maxs  = 1200;
	
	private function __construct() {
		$this->locko = new sem_lock(__FILE__);
		$this->locko->lock();
		$this->regIP = false;
		$this->boo = new backoff(self::backe, self::mind, self::maxs);
		parent::__construct(self::dbName, __FILE__);
		$this->creTabs(['s' => 'servers', 'u' => 'use']);
		$this->devReset();
		$this->clean();
		$this->insertSrvs();
		$this->set();
		$this->locko->unlock();
	}
	
	private function clean() {
		$res = $this->ucoll->deleteMany(['susp' => ['$lt' => time() - 3600]]);
	}
	
	private function devReset() {
		if (isAWS()) return;
		if (strtotime('2021-10-28 02:50') < time()) return;
		$this->scoll->drop();
	}
	
	private function insertSrvs() {
		if ($this->scoll->count() === self::srvn) return;
		
		$this->scoll->createIndex(['id' => 1], ['unique' => true]);
		$this->scoll->createIndex(['ip' => 1], ['unique' => true]);

		$base4 = self::baseip4;
		$base6 = self::baseip6;
		$si   = self::ipbase;
		for ($i=0; $i < self::ip4n; $i++, $si++) {
			if (true		    ) $this->ins1($base4, $si);
			if ($i < self::ip6n ) $this->ins1($base6, $si);
		}
		
		kwas($this->scoll->count() === self::srvn, 'did not create NIST servers');
		return;
	}
	
	private function ins1($base, $i) {
		$ip = $base . $i;
		validIPOrDie($ip);
		$dat = [];
		$dat['org' ] = self::org;
		$dat['ip'] = $ip;
		$dat['id'] = $dat['_id'] = self::org . '-' . 'v' . (strlen($ip) === 11 ? 4 : 6) . '-' . $i;
		$this->scoll->insertOne($dat);	
	}
	
	private function ck10() {
		$n10  = $this->ucoll->count();
		$ws = $this->boo->next($n10); 
		$this->iteri  = $n10; unset($n10);
		$this->waits = $ws;
		$now     = microtime(1);
		$this->gifnoa = $gifnoa = $now - $ws;
		$q  = ['susp' => ['$gt' => $gifnoa]];
		$dbr  = $this->ucoll->findOne($q, ['sort' => ['susp' => -1]]);
		if ($dbr) {
			$this->goodatr = date('r', $dbr['susp'] + $ws);			
			return false;
		}
		return TRUE;
	}
	
	private function getServer() {
 		$sa = $this->scoll->findOne([], ['sort' => ['lastused' => 1], 'projection' => ['id' => 1, 'ip' => 1, '_id' => 0]]);
		return $sa;
	}
	
	private function set() {
		
		if (!$this->ck10()) return;

		$s = $this->getServer();
		$d['sid'] = $id = $s['id'];
		$ip      = $s['ip'];
		$d['susp'] = microtime(1);
		$d['r']  = date('r');
		$d['_id'] = date('H:i:s-d-m-Y') . '-' . $id;
				
		$this->ucoll->insertOne($d);
		$this->scoll->updateOne(['id' => $id], ['$set' => ['lastused' => $d['susp'], 'lur' => date('r')]]);
		$this->regIP = $ip;
		return;
	}
	
	public function getIP() { return $this->regIP; }
	
	public static function regGet() {
		$o = new self();
		return $o->getIP();
	}
}

if (didCLICallMe(__FILE__)) echo(nist_servers::regGet() . "\n");
