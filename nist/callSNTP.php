<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/' . 'config.php');
require_once('validIP.php');

class callSNTP extends callSNTPConfig {

	const resnt = 4;
	const resnl = self::resnt + 1;
	const thecmd = 'sntp -nosleep';

	private function __construct() {
		$this->init();
		$this->doit();
		// $this->calcs();
	}

	private function calcs() {
		if (!isset($this->ores['raw'])) return;
		$or  =	   $this->ores;
		$a   =	$or['raw'];
		$min = $or['min'] = min($a);
		for($i=0; $i < 2; $i++) $or['relmss'][$i] = self::toms($a[$i] - $min);
		$avgns = (($a[3] + $a[0]) >> 1);
		$avgs = self::toms($avgns - $min);
		$or['relmss'][2] = $avgs;
		for($i=2; $i <= 3; $i++) $or['relmss'][$i + 1] = self::toms($a[$i] - $min);
		$d = self::d($a);
		$or['dsns'] = $d;
		$or['ds'  ] = $d / M_BILLION;
		$or['dsms'] = $d / M_MILLION;
		$or['outms'] = self::toms($a[1] - $a[0]);
		$or['inms']  = self::toms($a[3] - $a[2]);
		
		$or['r'] = date('r');
		$or['U'] = time();
		
		$this->ores = $or;
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
			$a = explode("\n", trim($t)); unset($t); kwas(count($a) === self::resnl, 'wrong lines sntp sanity check');
			$ip = validIPOrDie($a[4]); unset($a[4]);
						
			for ($i=0; $i < self::resnt; $i++) $a[$i] = intval($a[$i]);

			$min = min($a);
			$max = max($a);
			kwas($max - $min < callSNTPConfig::toleranceNS, 'time sanity check fails - ck 1 0417');
			$ds = abs(nanotime() - $max);
			kwas($ds < callSNTPConfig::toleranceNS , 'time sanity check fail 2');
			kwas($a[1] <= $a[2], 'server time sanity check fail between in and out');
			kwas($a[0] <  $a[3], 'server time sanity check internal out and in');

			$this->ores['t4Uns'] = $a;
			$this->ores['ip' ] = $ip;
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
