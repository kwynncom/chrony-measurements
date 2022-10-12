<?php

require_once('/opt/kwynn/mongodb3.php');
require_once('/opt/kwynn/lock.php');
require_once('callSNTP.php');
require_once('backoff.php');
require_once('fromLog.php');
require_once('insert.php');

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
	
	public static function get($lim = 1, string $cf = '') {
		try { 
			$o = new self($cf === __FILE__);
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
	
	private function __construct(bool $didCallMe = false) {
		
		parent::__construct(self::dbname);
		$this->creTabs(['c' => self::collname]);
		$this->boo = new backoff(self::backe, self::NISTminS, self::maxs);
		$this->inso = new nist_insert($didCallMe);
		$this->clean();
	}
	
	private function clean() {
		$dats = self::deleteAtS;
		$dat = time() - $dats;
		$this->ccoll->deleteMany(['U' => ['$lt' => time() - self::deleteAtS]]);
	}
	
	public function waitSfl() {
		if (1) { 
			$w1 = $this->waitSfl20();
			if ($w1 > 0) return $w1;
			unset($w1);  
		}
		
		new nistLogToDBCl();
				
		return $this->waitSfl20();
		
	}
	
	private function waitSfl20() {
		$n10  = $this->ccoll->count(['U' => ['$gte' => time() - self::maxs]]);
		$ws = $this->boo->next($n10);
		if ($n10 === 0) return 0;
		$nowus = microtime(1);
		$failifago = $nowus - $ws;
		$ckr = $this->ccoll->findOne([], ['sort' => ['Uus' => -1]]);
		$towait = $ckr['Uus'] - $failifago;
		return $towait;
	}


	public static function fromLog($cli, array $datin) {
		
		extract($datin); unset($datin);
		
		$Uactual = $U;
		$Uus = $U = $U + 1; 
		$via = 'log';
		
		$r = date('r', $Uactual);

		$_id  = date('md-Hi-s-Y', $Uactual) . '-' . substr($via, 0, 3) . substr($ip, strlen($ip) - 3); 
		$_id .= ($offset >= 0 ? '+' : '-') . sprintf('%0.6f', $offset);
		$dat = get_defined_vars();
		unset($dat['cli'], $dat['cmp']);
		$dat = kwam($dat, self::getpinfo(false));
		$cli->insertOne($dat, ['kwnoup' => true]);
		return true;
	}
	
	private function doTheCall() {
		$_id = $this->inso->preCall();
		$r = callSNTP::getNISTActual();
		if (!$r) return;
		$this->inso->postCall($_id, $r);
		return;
	}

	private function quotaOrDie() {
		$towait = $this->waitSfl();
		kwas($towait <= 0, 'quota overflow');
		return;
	}

	public function getdb($limitn = 1) {
		$o = [];
		$o['projection'] = ['_id' => 0, 'U' => 1, 'r' => 1, 'ip' => 1, 't4Uns' => 1, 'offset' => 1, 'via' => 1];
		$o['sort'] = ['U' => -1];
		$o['limit'] = $limitn;
		$res = $this->ccoll->find(['U' => ['$gte' => time() - 3600]], $o);
		if (!$res) return $res;
		if ($limitn === 1) return $res[0];
		return $res;
	}
	
}

if (didCLICallMe(__FILE__)) print_r(nist_backoff_calls::get(null, __FILE__));
