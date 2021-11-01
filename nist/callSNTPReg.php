<?php

require_once('servers.php');
require_once('callSNTP.php');

class nist_backoff_calls extends nist_servers {
	public static function get() {
		$ip = nist_servers::regGet();
		$sres = false;
		if ($ip) $sres = callSNTP::getReal($ip); // 2 lines after REGget
		$o = new self($sres);
		return $o->getR();
	}
	
	public function getR() { return $this->oret; }
	
	private function __construct($rin) {
		parent::__construct(true);
		$this->creTabs(['o' => 'offsets']);
		$this->clean();
		if ($rin) {
			$this->save($rin);
			$this->oret = $rin;
		} else 
			$this->oret = $this->getdb();
	}
	
	private function save($rin) {
		$res = $this->ocoll->insertOne($rin);
	}
	
	private function getdb() {
		$res = $this->ocoll->findOne([], ['sort' => ['U' => -1]]);
		return $res;
	}
	
}

if (didCLICallMe(__FILE__)) print_r(nist_backoff_calls::get());