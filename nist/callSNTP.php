<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/' . 'config.php');
require_once('validIP.php');

class callSNTP implements callSNTPConfig {

	const ipv6Percent = 70;
	const cmdpre = 'sntp --json ';
	const cmdhost = 'time.nist.gov';
	
	
	private function __construct() {
		$this->init();
		$this->doit();
	}

	public static function toms($ns) { return $ns * 1000; /* Kwynn 2023/06 ??? */ }
	
	private function init() {
		$this->ores = [];
	}
	
	private function doit() {
		$do6 = random_int(1, 100) <= self::ipv6Percent;
		$c = self::cmdpre . ' -' . ($do6 ? '6' : '4') . ' ' . self::cmdhost;
		$t = shell_exec($c);
		if ($t) $this->setValid($t);

	}
	
	private function setValid(string $t) {
		
		try {
			$a = sntpSanity::ck($t); kwas($a, 'no sane result');
			$this->ores = $a;
		} catch(Exception $ex) { }
		
		return;
	}
	
	public function getRes() { return $this->ores; }
	
	public static function getNISTActual(bool $direct = false) {
		$o = new self();
		$res = $o->getRes();
		if ($direct) print_r($res);
		return $res;
	}

}

if (didCLICallMe(__FILE__)) callSNTP::getNISTActual(true);
