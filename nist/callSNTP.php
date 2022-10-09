<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/' . 'config.php');
require_once('validIP.php');

class callSNTP implements callSNTPConfig {

	const resnt = 4;
	const cmdRun   = 'sntpw -nosleep -json -d';
	const cmdDebug = 'sntp  -nosleep -json';

	private function isDebugMode() {
		if (amDebugging()) return true;
		if (time() < strtotime('2022-10-09 00:50')) return true;
		return false;
	}
	
	private function __construct() {
		$this->debug = $this->isDebugMode();
		$this->init();
		$this->doit();
		// $this->calcs();
	}


	
	public static function d($T) { 
		$t = ((($T[1] - $T[0]) + ($T[2] - $T[3]))) >> 1;
		return $t;
	}
	
	public static function toms($ns) { return $ns / M_MILLION; }
	
	private function init() {
		$this->ores = []; // Kwynn 2022/07
	}
	
	private function doit() {
		if ($this->isDebugMode()) $c = self::cmdDebug;
		else			   $c = self::cmdRun;
		$t = shell_exec($c);
		$this->setValid($t);
	}
	
	private function setValid($t) {
		
		try {
			if (!$this->debug) {
				$a = json_decode($t, true);
				if (!$a || $a['status'] !== 'OK') return;
			} else {
				$a = SNTPTextToArr($t);
				$a['t4'] = $a['Uns4'];
			}
			$this->ores['t4Uns'] = $a['t4'];
			$this->ores['ip' ] = $a['ip'];
		} catch(Exception $ex) { }
		
		return;
	}
	
	public function getRes() { return $this->ores; }
	
	public static function getNISTActual() {
		$o = new self();
		$res = $o->getRes();
		return $res;
	}

}

if (didCLICallMe(__FILE__)) callSNTP::getNISTActual();
