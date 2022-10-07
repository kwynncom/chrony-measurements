<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/' . 'config.php');
require_once('validIP.php');

class callSNTP implements callSNTPConfig {

	const resnt = 4;
	const thecmd = 'sntpw -nosleep -json -d';

	private function __construct() {
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
		$t = shell_exec(self::thecmd);
		$this->setValid($t);
	}
	
	private function setValid($t) {
		
		try {
			$a = json_decode($t, true);
			if (!$a || $a['status'] !== 'OK') return;
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
