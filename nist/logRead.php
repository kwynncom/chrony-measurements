<?php

require_once('config.php');
require_once(__DIR__ . '/../logscl.php');
require_once('logdlog.php');
require_once('fromLog.php');

class chrony_log_reader implements callSNTPConfig {


	const lockf = '/tmp/logdloglf';
		
	public function __construct() {
	
		try {
			$this->ollo = new ch_logd_log();
			$this->lock();
			$this->dbo = new nistLogToDBCl(true);
			$this->fpo = new filePtrTracker(self::chronyLogF, kwsntp_doTestClean());
			$this->doit();
		} catch (Exception $ex) { $this->ollo->out('EXCEPTION: ', $ex->getMessage()); }
	}
	
	private function lock() {
		$this->lockh = fopen(self::lockf, 'w');
		$st = flock($this->lockh, LOCK_EX | LOCK_NB);
		kwas($st, 'chrony log log did not get lock');
		
	}
	
	private function doit() {
		$lnn = 1;
		while($l = $this->fpo->fgets()) {
			$a = chronylog_cli_filter::getLLI($l);
			if (!$a) continue;
			if (!isset(callSNTPConfig::NISTListA[$a['ip']])) continue;
			$a['lnn'] = $lnn++;
			$this->dbo->procIP($a);
			$this->ollo->out(nist_insert::ippp($a['ip']), nist_insert::flpp($a['offset']));
		}
		
	}
	
	public function __destruct() {
		if ($this->lockh) {
			flock($this->lockh, LOCK_UN);
			fclose($this->lockh);
		}
	}
}

if (didCLICallMe(__FILE__)) new chrony_log_reader();