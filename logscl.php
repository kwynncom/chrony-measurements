#! /usr/bin/php
<?php

require_once('/opt/kwynn/kwutils.php');

class chronylog_cli_filter {

	const linesn = 100;
	const thef   = '/var/log/chrony/measurements.log';

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
	
	private function do10($l) {
		
		static $ipa = 15;
		
		if (!trim($l)) return;
		
		if ($this->oi % 20 === 0) $this->outHeader();
			
		$hu = trim(substr($l, 0, 20));
		echo($hu . ' ');		

		$ipb = substr($l, 20);
		preg_match('/\S+/', $ipb, $ms);
		$ip = $ms[0];
		$ipl = strlen($ip);
		echo(substr($ip, $ipl - 3) . ' ');
		$ones = substr($l, 35 + ($ipl <= $ipa ? 0 : $ipl - $ipa));
		echo($ones[16]);
		$restl = substr($ones, 19, 66);
		echo($restl);
		if ($this->isBatch) $this->procIP($hu, $ip, $restl);
		echo("\n");
		$this->oi++;
		return;
	}
	

	
	private function outHeader() {
		static $o = false;
		$s = <<<CHRH
   Date (UTC) Time   IP 6 LP RP Score    Offset  Peer del.  Peer disp. Root del.  Root disp. Refid     MTxRx
CHRH;
		if ($o === false) $o = substr(rtrim($s), 0, 91);
		echo($o . "\n");
	}
	
	private function init() {
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
