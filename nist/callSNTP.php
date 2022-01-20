<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/' . 'config.php');
require_once('validIP.php');

class callSNTP extends callSNTPConfig {

	const simShell = false;
	const rescnt   = 4;
	
	private function simShell() {
		return false;
		/* 
		 * if (!self::simShell) return false;
		$t = $r[0] = nanotime();
		$r[1] = $t + intval(round(self::toleranceNS * 0.999));
		$r[2] = $r[1];
		$r[3] = $t;
		$this->ores['raw'] = $r;
		$j = json_encode($r);
		return $j;
		 * 
		 */
	}
	
	private function __construct($ip) {
		$this->ipinraw = $ip;
		$this->init();
		$this->doit();
		$this->calcs();
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
	
	public static function d($T) { // getting the sign right per RFC 4330 page 13 - https://datatracker.ietf.org/doc/html/rfc4330
		$t = ((($T[1] - $T[0]) + ($T[2] - $T[3]))) >> 1;
		return $t;
	}
	
	public static function toms($ns) { return $ns / M_MILLION; }
	
	private function init() {
		$this->ores = false;
		$this->setIP();
		$this->setCmd();
	}
	
	private function setIP() {
		global $argv;
		global $argc;
		
		if ($this->ipinraw && validIPOrDie($this->ipinraw)) $this->ip = $this->ipinraw;
		else { 
			kwas($argc >= 2, 'need an IP argument');
			$ip = $argv[1];
			$this->ip = validIPOrDie($ip);
		}
	}
	
	private function setCmd() {
		if (FALSE /*&& !is*** AWS() */) { // assuming it's in the PATH
			$locpp = self::locPath;
			$locp = $locpp . self::file;
			kwas(is_readable($locp), "$locp unreadable");
			$this->cmd = self::loccmd . ' ' . $locp . ' ' . $this->ip;
			return;
		}
		
		$this->cmd = self::file . ' ' . $this->ip;
	}
	
	private function doit() {
		$cmd = trim($this->cmd);
		if (!($r = $this->simShell())) $r = shell_exec($cmd);
		$a = json_decode(trim($r));
		$this->setValid($a);
	}
	
	private function setValid($a) {
		if (!is_array($a)) return;
		if (count($a) !== self::rescnt) return;

		$now = nanotime();
		for($i=0; $i <    self::rescnt; $i++) {
			if (!is_integer($a[$i])) return;
			$d = abs($a[$i] - $now);
			if ($d > self::toleranceNS) return;
		}
		
		if ($i !== self::rescnt) return;
		$this->ores['raw'] = $a;
		return;
	}
	
	public function getRes() { return $this->ores; }
	
	public static function getNISTActual($ip = false) {
		$o = new self($ip);
		return $o->getRes();
	}

}
if (didCLICallMe(__FILE__)) { $d = callSNTP::getNISTActual();
	print_r($d);
	// var_dump($d);
	unset($d);
}
