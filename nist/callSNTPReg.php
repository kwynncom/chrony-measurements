<?php

require_once('/opt/kwynn/mongodb3.php');
require_once('/opt/kwynn/lock.php');
require_once('callSNTP.php');
require_once('backoff.php');

class nist_backoff_calls extends dao_generic_3 {

	const dbname = 'sntp4';
	const resetS = 1200;
	
	
	public static function get() {
		$o = new self();
		return $o->getdb();
	}
	
	private function __construct() {
		parent::__construct(true);
		$this->creTabs(['o' => 'calls']);
		$this->clean();
		$this->quotaOrDie();
		// if ($rin) $this->save($rin);
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