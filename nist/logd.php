<?php

require_once('config.php');
require_once('fromLog.php');
require_once(__DIR__ . '/../logscl.php');

class chrony_log_daemon {
	
	// const maxLineLen = 161; // should be exactly right - full 39 character IPv6 and newline
	const seekLnBack = 40;
		
	public function __construct() {
		$this->dbo = new nistLogToDBCl(true);
		$this->oec = $this->dbo->getEndCrit();
		$this->ohan = popen('tail -n ' . self::seekLnBack . '  ' . callSNTPConfig::chronyLogF, 'r');
		$this->meetEndCrit();
		$this->doit();
	}
	
	private function doit() {
		$lnn = 1;
		while($l = fgets($this->ohan)) {
			$a = chronylog_cli_filter::getLLI($l);
			if (!$a) continue;
			if (!isset(callSNTPConfig::NISTListA[$a['ip']])) continue;
			$a['lnn'] = $lnn++;
			$this->dbo->procIP($a);
		}
		
	}
	
	private function meetEndCrit() {
		
		$i = 0;
		
		while ($l = fgets($this->ohan)) {
			kwas(++$i <= self::seekLnBack, 'should always meet end crit - line overrun');
			$a = chronylog_cli_filter::getLLI($l);
			if ($a && $this->oec && chronylog_cli_filter::testEndCritAB($a, $this->oec)) return;
		}
		
		kwas(false, 'should always meet end crit - line overrun');
	}
	
	public function __destruct() {
		if ($h = kwifs($this, 'ohan')) fclose($h);
	}
}

if (didCLICallMe(__FILE__)) new chrony_log_daemon();