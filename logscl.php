<?php
// #! /usr/bin/php // can't do this on the web
require_once('/opt/kwynn/kwutils.php');

class chronylog_cli_filter {

	const linesn = 100;
	const thef   = '/var/log/chrony/measurements.log';
	const dlockf = '/var/kwynn/mysd/loglock';

	private function procIP($hu, $ip, $restl) {
		static $o = false;
		
		require_once(__DIR__ . '/nist/fromLog.php');
		
		if (!$o) $o = new nistLogToDBCl();
		
		if (!nistLogToDBCl::isNIST($ip)) return;
		
		$offs = substr($restl, 12, 10);
		$offfl = floatval($offs);
		
		$o->put($hu, $ip, $offs);
	}
	
	
	public function __construct(bool $isBatch = false) {
		$this->isBatch = $isBatch;
		$this->init();
		while ($l = $this->get()) $this->do10($l);
	}
	
	private function oout($s) {
		if (iscli()) echo($s);
	}
	
	private function do10($l) {
		
		static $ipa = 15;
		
		if (!trim($l)) return;
		
		if ($this->oi % 20 === 0) $this->outHeader();
			
		$hu = trim(substr($l, 0, 20));
		$this->oout($hu . ' ');		

		$ipb = substr($l, 20);
		preg_match('/\S+/', $ipb, $ms);
		$ip = $ms[0];
		$ipl = strlen($ip);
		$this->oout(substr($ip, $ipl - 3) . ' ');
		$ones = substr($l, 35 + ($ipl <= $ipa ? 0 : $ipl - $ipa));
		$this->oout($ones[16]);
		$restl = substr($ones, 19, 66);
		$this->oout($restl);
		// if ($this->isBatch) 
		$this->procIP($hu, $ip, $restl);
		$this->oout("\n");
		$this->oi++;
		return;
	}
	

	
	private function outHeader() {
		static $o = false;
		$s = <<<CHRH
============================================================================================
   Date (UTC) Time   IP 6 LP RP Score    Offset  Peer del.  Peer disp. Root del.  Root disp.
============================================================================================
CHRH;
		if ($o === false) $o = rtrim($s); // substr(rtrim($s), 0, 91);
		$this->oout($o . "\n");
	}
	
	private function amIDaemon() {
		global $argc, $argv;
		
		for($i=1; $i < $argc; $i++) if ($argv[$i] === '-d') return true;
		return false;
	}
	
	private function __destruct() {
		$this->cleanupf();
		
	}
	
	private function cleanupf() {
		if (!$this->h) return;
		if ($this->hasLock) flock($this->h, LOCK_UN);
		$this->hasLock = false;
		fclose($this->h);
		$this->h = false;
	}
	
	private function daemonize() {
		$this->h = false;
		$this->hasLock = false;
		$this->h = $h = fopen(self::dlockf, 'r');
		
		if ($amd = $this->amIDaemon()) $lockty = LOCK_EX;
		else $lockty = LOCK_SH;
		
		$this->hasLock = $l = flock($h, $lockty | LOCK_NB);
		if ($l && !$amd) {
			$this->cleanupf();
			kwnohup('php ' . __FILE__ . ' -d');
			if ($this->isBatch) exit(0);
		}
		
		// if (!$amd) $this->cleanupf();
	}
	
	private function init() {
		
		$this->daemonize();

		$this->oi = 0;
		
		if (!$this->isBatch) {
			$l = 'tail -n '; 
			$l .= self::linesn . ' ';
			if (!amDebugging()) $l .= '-f ';
			$l .= self::thef;
			$this->ohan = popen($l, 'r');		
		} else {
			$t = trim(shell_exec('tail -n 60 ' . self::thef));
			$this->olines = explode("\n", $t);
		}


	}
	
	private function get() { 
		if (!$this->isBatch) $l = trim(fgets($this->ohan));
		else {
			if (!isset($this->olgi)) $this->olgi = 0;
			$i = $this->olgi++;
			$l = kwifs($this->olines, $i);
		}
		if (!$l) return false;
		if (!is_numeric($l[0])) return ' ';
		return $l;
	
	}
}

if (didCLICallMe(__FILE__)) new chronylog_cli_filter();
