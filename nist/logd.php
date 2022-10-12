<?php

require_once('config.php');
require_once('fromLog.php');
require_once(__DIR__ . '/../logscl.php');
require_once('logdlog.php');

class chrony_log_daemon {
	
	// const maxLineLen = 161; // should be exactly right - full 39 character IPv6 and newline
	const seekLnBack = 40;
	const lockf = '/tmp/logdloglf';
		
	public function __construct() {
	
		try {
			$this->ollo = new ch_logd_log();
			$this->lock();
			$this->dbo = new nistLogToDBCl(true);
			$this->oec = $this->dbo->getEndCrit();
			$this->ohan = popen('tail -n ' . self::seekLnBack . $this->fornot() . callSNTPConfig::chronyLogF, 'r');
			$this->meetEndCrit();
			$this->doit();
		} catch (Exception $ex) { $this->ollo->out('EXCEPTION: ', $ex->getMessage()); }
	}
	
	private function fornot() {
		if (amDebugging()) return '';
		return ' -f ';
	}
	
	private function lock() {
		$this->lockh = fopen(self::lockf, 'w');
		$st = flock($this->lockh, LOCK_EX | LOCK_NB);
		kwas($st, 'chrony log log did not get lock');
		
	}
	
	private function doit() {
		$lnn = 1;
		while($l = fgets($this->ohan)) {
			$a = chronylog_cli_filter::getLLI($l);
			if (!$a) continue;
			if (!isset(callSNTPConfig::NISTListA[$a['ip']])) continue;
			$a['lnn'] = $lnn++;
			$this->dbo->procIP($a);
			$this->ollo->out(nist_insert::ippp($a['ip']), nist_insert::flpp($a['offset']));
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
		if ($this->lockh) {
			flock($this->lockh, LOCK_UN);
			fclose($this->lockh);
		}
	}
}

if (didCLICallMe(__FILE__)) new chrony_log_daemon();