#! /usr/bin/php
<?php

require_once('/opt/kwynn/kwutils.php');

class chronylog_cli_filter {

	const linesn = 100;

	public function __construct() {
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
		// $this->procIP($hu, $ip, $restl);
		echo("\n");
		$this->oi++;
		return;
	}
	
	private function procIP($hu, $ip, $restl) {
				
		$offs = substr($restl, 12, 10);
		$offfl = floatval($offs);
		
		kwynn();
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
		// $t = 'tail -n 100    /var/kwynn/chm.log';
		$l = 'tail -n '; 
		$l .= self::linesn . ' ';
		if (!amDebugging()) $l .= '-f ';
		$l .= '/var/log/chrony/measurements.log';
		$this->ohan = popen($l, 'r');		
		$this->oi = 0;

	}
	
	private function get() { 
		$l = trim(fgets($this->ohan));
		if (!$l) return false;
		if (!is_numeric($l[0])) return ' ';
		return $l;
	
	}
}

if (iscli()) new chronylog_cli_filter();
