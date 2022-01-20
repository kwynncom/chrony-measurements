<?php

require_once('servers.php');
require_once('callSNTP.php');

class nist_backoff_calls extends nist_servers {
	
	private static function testMode() {
		return FALSE;
	}
	
	public static function get($limitN = 1) {
		$ip = false;
		if (!self::testMode()) $ip = nist_servers::regGet();
		$sres = false;
		if ($ip) $sres = callSNTP::getNISTActual($ip); // turning off for now // 2 lines after REGget
		$o = new self($sres);
		return $o->getdb($limitN);
	}
	
	private function __construct($rin) {
		parent::__construct(true);
		$this->creTabs(['o' => 'offsets']);
		$this->clean();
		if ($rin) $this->save($rin);
	}
	
	private function save($rin) {
		$res = $this->ocoll->insertOne($rin);
	}
	
	public function getdb($limitn = 1) {
		$res = $this->ocoll->find([], ['sort' => ['U' => -1], 'limit' => $limitn]);
		if (!$res) return $res;
		if ($limitn === 1) return $res[0];
		return $res;
	}
	
}

if (didCLICallMe(__FILE__)) print_r(nist_backoff_calls::get());