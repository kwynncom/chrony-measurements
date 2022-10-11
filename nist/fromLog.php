<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/callSNTPReg.php');
require_once(__DIR__ . '/../logscl.php');

class nistLogToDBCl extends dao_generic_3 implements callSNTPConfig {
	
	public function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['c' => self::collname]);
		$this->indexMgmt();
		$this->do10();

	}
	
	private function do10() {
		$o = new chronylog_cli_filter(true);
		$a = $o->get();
		$n = count($a);
		for ($i = $n - 1; $i >= 0; $i--) {
			if (!$this->procIP($a[$i])) break;
		}
		return;
	}
	
	private function indexMgmt() {
		try { $this->ccoll->dropIndex('U_-1'); } catch(Exception $ex) {}
		$name = $this->ccoll->createIndex(['U' => -1]);
		return;
	}
	
	public static function isNIST($ip) {
		return in_array($ip, self::nista);
	}

	private function procIP(array $a) {
		static $o = false;
		static $exn = 0;

		if (!$o) $o = new nistLogToDBCl();
		
		extract($a);

		try {
			unset($a['hu']);
			return nist_backoff_calls::fromLog($this->ccoll, $a);
		} catch (Exception $ex) {
			if (++$exn > 4) { 
				echo('chrony logcl.php exception as follows.  Exiting: ' . $ex->getMessage());
				exit(2122);
			}
		}
		
		return true;
	}
	
}

if (didCLICallMe(__FILE__)) new nistLogToDBCl();
