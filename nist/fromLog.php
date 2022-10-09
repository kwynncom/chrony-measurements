<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/../logscl.php');
require_once(__DIR__ . '/callSNTPReg.php');

class nistLogToDBCl extends dao_generic_3 implements callSNTPConfig {
	
	public function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['c' => self::collname]);
		$this->setMin();

	}
	
	private function setMin() {
		$r = $this->ccoll->findOne([], ['sort' => ['U' => -1]]);
		if (!$r) $this->minU = 0;
		else $this->minU = $r['U'];
		return;
	}
	
	public static function isNIST($ip) {
		return in_array($ip, self::nista);
	}

	public function put(string $hu, string $ip, float $off) {
		if (!self::isNIST($ip)) return;
		$ts = strtotime($hu . ' UTC');
		if ($ts <= $this->minU) return;
		nist_backoff_calls::fromLog($this->ccoll, $ts, $ip, $off);
		
	}
	
}

if (didCLICallMe(__FILE__)) {
	new chronylog_cli_filter(true);
}
