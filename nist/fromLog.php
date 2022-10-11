<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/callSNTPReg.php');

class nistLogToDBCl extends dao_generic_3 implements callSNTPConfig {
	
	const enterIfM = 20;
	const enterIfS = self::enterIfM * 60;
	
	public function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['c' => self::collname]);
		$this->indexMgmt();

	}
	
	private function indexMgmt() {
		try { $this->ccoll->dropIndex('U_-1'); } catch(Exception $ex) {}
		$name = $this->ccoll->createIndex(['U' => -1]);
		return;
	}
	
	public static function isNIST($ip) {
		return in_array($ip, self::nista);
	}

	public function put(string $hu, string $ip, float $off) {
		if (!self::isNIST($ip)) return;
		$ts = strtotime($hu . ' UTC');
		if ($ts + self::enterIfS < time()) return;
		nist_backoff_calls::fromLog($this->ccoll, $ts, $ip, $off);

	}
	
}
