<?php

require_once('/opt/kwynn/mongodb3.php');
require_once('/opt/kwynn/lock.php');
require_once('callSNTP.php');
require_once('backoff.php');

class nist_backoff_calls extends dao_generic_3 implements callSNTPConfig {

	const dbname = 'sntp4';
	const resetS = 1200;
	const backe = 1.2;
	const mind  = 4;
	const maxs  = 1200;	
	const deleteAtDays = 100;
	const deleteAtS    = DAY_S * self::deleteAtDays;
	
	public static function get($lim = 1) {
		try { 
			$o = new self();
			return $o->getdb($lim);
		} catch(Exception $ex) {}
		
		return [];
	}
	
	private function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['c' => self::collname]);
		$this->ccoll->createIndex(['U' => -1], ['unique' => true]);
		$this->clean();
		$lo = new sem_lock(__FILE__);
		try { 
			$lo->lock();
			$this->quotaOrDie();
			$this->doit();
		} catch(Exception $ex) { }
		
		$lo->unlock();
	}

	private function clean() {
		$dats = self::deleteAtS;
		$dat = time() - $dats;
		$this->ccoll->deleteMany(['U' => ['$lt' => time() - self::deleteAtS]]);
	}
	
	private function doit() {
		$a = [];
		$us = $a['Uus'] = microtime(1);
		$U	= $a['U'  ] = intval(floor($us));
		$a['r'] = date('r', $U);
		$a['via'] = iscli() ? 'cli' : 'www';
		$this->ccoll->insertOne($a, ['kwnoup' => true]);
		$r = callSNTP::getNISTActual();
		if (!$r) return;
		$this->ccoll->upsert(['U' => $U], kwam($a, $r), 1, false);
		return;
	}
	
	private function quotaOrDie() {
		$boo = new backoff(self::backe, self::mind, self::maxs);	
		$n10  = $this->ccoll->count(['U' => ['$gte' => time() - self::maxs]]);
		$ws = $boo->next($n10); 
		$ckr = $this->ccoll->findOne(['Uus' => ['$gte' => (microtime(1) - $ws)]]);
		kwas(!$ckr, 'quota overflow');
		return;
	}

	public function getdb($limitn = 1) {
		$o = [];
		$o['projection'] = ['_id' => 0, 'U' => 1, 'r' => 1, 'ip' => 1, 't4Uns' => 1];
		$o['sort'] = ['U' => -1];
		$o['limit'] = $limitn;
		$res = $this->ccoll->find(['U' => ['$gte' => time() - 3600]], $o);
		if (!$res) return $res;
		if ($limitn === 1) return $res[0];
		return $res;
	}
	
}

if (didCLICallMe(__FILE__)) print_r(nist_backoff_calls::get());