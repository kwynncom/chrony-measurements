<?php

require_once('/opt/kwynn/mongodb3.php');
require_once('/opt/kwynn/lock.php');
require_once('callSNTP.php');
require_once('backoff.php');
require_once(__DIR__ . '/../logscl.php');

class nist_backoff_calls extends dao_generic_3 implements callSNTPConfig {

	const dbname = callSNTPConfig::dbname;
	const resetS = 1200;
	const backe = 1.2;
	const maxs  = 1200;	
	const deleteAtDays = 100;
	const deleteAtS    = DAY_S * self::deleteAtDays;

	public static function getWait() {
		$o = new self();
		return $o->waitSFl();
	}
	
	public static function get($lim = 1) {
		try { 
			$o = new self();
			$o->doit();
			return $o->getdb($lim);
		} catch(Exception $ex) {}
		
		return [];
	}
	
	public function doit() {
		$lo = new sem_lock(__FILE__);
		try { 
			$lo->lock();
			$this->quotaOrDie();
			$this->doTheCall();
		} catch(Exception $ex) { }
		
		$lo->unlock();		
	}
	
	private function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['c' => self::collname]);
		$this->ccoll->createIndex(['U' => -1], ['unique' => true]);
		$this->clean();
	}

	private function clean() {
		$dats = self::deleteAtS;
		$dat = time() - $dats;
		$this->ccoll->deleteMany(['U' => ['$lt' => time() - self::deleteAtS]]);
	}
	
	public function waitSfl() {
		$w1 = $this->waitSfl20();
		if ($w1 > 0) return $w1;
		unset($w1);
		new chronylog_cli_filter(true);
		return $this->waitSfl20();
		
	}
	
	private function waitSfl20() {
		$boo = new backoff(self::backe, self::NISTminS, self::maxs);	
		$n10  = $this->ccoll->count(['U' => ['$gte' => time() - self::maxs]]);
		$ws = $boo->next($n10);
		if ($n10 === 0) return 0;
		$nowus = microtime(1);
		$failifago = $nowus - $ws;
		$ckr = $this->ccoll->findOne([], ['sort' => ['Uus' => -1]]);
		$towait = $ckr['Uus'] - $failifago;
		return $towait;
	}

	public static function fromLog($cli, int $ts, string $ip, float $offset) {
		if (!in_array($ip, self::nista)) return;
		$Uactual = $ts;
		$r = date('r', $ts);
		$Uus = $U = $ts + 1; 
		$fromLog = true;
		$_id = dao_generic_3::get_oids(false, $ts); unset($ts);
		$dat = get_defined_vars();
		unset($dat['cli']);
		$cli->insertOne($dat, ['kwnoup' => true]);
		kwynn();
		
		
	}
	
	private function doTheCall() {
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
		$towait = $this->waitSfl();
		kwas($towait <= 0, 'quota overflow');
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
