<?php

require_once('config.php');
require_once(__DIR__ . '/../logscl.php');
require_once('fromLog.php');

class chrony_log_reader implements callSNTPConfig {
		
	public function __construct() {
	
		$this->dbo = new nistLogToDBCl(true);
		$this->fpo = new filePtrTracker(self::chronyLogF, kwsntp_doTestClean());
		$this->doit();

	}
	
	private function doit() {
		$lnn = 1;
		while($l = $this->fpo->fgets()) {
			$a = chronylog_cli_filter::getLLI($l);
			if (!$a) continue;
			if (!isset(callSNTPConfig::NISTListA[$a['ip']])) continue;
			$a['lnn'] = $lnn++;
			$this->dbo->procIP($a);
		}
		
	}
}

if (didCLICallMe(__FILE__)) new chrony_log_reader();