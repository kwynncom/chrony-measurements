<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/config.php');


class nist_passfail extends dao_generic_3 implements callSNTPConfig {
	
	const withinM = 30;
	const withinS = self::withinM * 60;
	const mustn   = 3;
	const tolerancens = M_MILLION;
	const tolerancensTest = 1000;
	
	private function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(self::collname);
		$this->init();
		try { $this->do10(); } catch (Exception $ex) { return; }
		$this->ores->pass = true;
	}
	
	public function getRes() { return $this->ores; }
	
	private function init() {
		$this->ores = new stdClass();
		$this->ores->pass = false;
		$this->ores->otrcnt = 0;
		$this->ores->offs = [];
		$this->ores->iskwdev = ispkwd();
	}
	
	private function do10() {
		$res = $this->ccoll->find(['U' => ['$gte' => time() - self::withinS]], ['sort' => ['U' => -1], 'limit' => 5]);
		kwas($res && is_array($res) && count($res) >= self::mustn, 'SNTP pf count fail 1 - 2121');
		$this->ores->otrcnt = count($res);
		$this->do20($res);
		return;
	}
	
	private function do20($dbr) {
		
		$ti = 0;
		
		foreach($dbr as $r) {
			$a = $r['Uns4'];
			try { 
				$o = self::SNTPOffset($a); kwas($o && is_numeric($o), 'bad form of passfail NIST SNTP result');
				$this->ores->offs[] = $o;
				kwas(self::passfail($o), 'not within tolerance SNTP passfail');
				$ti++;
			} catch (Exception $ex) {}
		}
		
		kwas($ti >= self::mustn, 'not enough pass tolerance SNTP passfail');
		
	}
	
	public static function isTest() {
		if (ispkwd()) return true;
		if (time() < strtotime('2022-10-02 23:29')) return true;
		return false;
	}
	
	public static function passfail($o) {
		static $t = false;
		if ($t === false) $t = self::isTest() ? self::tolerancensTest : self::tolerancens;
		return abs($o) < $t;
	}
	
	public static function SNTPOffset($T) {
		return ((($T[1] - $T[0]) + ($T[2] - $T[3]))) >> 1;
	}
	
	public static function getDets() {
		$o = new self();
		return $o->getRes();
	}
	
	public static function ispass() {
		try {
			new self();
			return true;
		} catch(Exception $ex) { 
			return false; 
		}
	}
	
	
}

if (didCLICallMe(__FILE__)) nist_passfail::ispass();
