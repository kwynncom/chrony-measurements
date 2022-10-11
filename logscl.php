<?php
// #! /usr/bin/php // can't do this on the web
require_once('/opt/kwynn/kwutils.php');

class chronylog_cli_filter {

	const version = '10/11 00:08 - giving up on daemon';
	const linesn = 40;
	const thef   = '/var/log/chrony/measurements.log';
	const dlockf = '/var/kwynn/mysd/loglock';

	public function __construct() {
		cliOrDie();
		$this->init();
		while ($l = $this->get()) $this->do10($l);
	}
	
	private function oout($s) {
		if (iscli()) echo($s);
	}
	
	private function do10($l) {
		
		static $ipa = 15;
		
		if (!trim($l)) return;
		
		if ($this->oi === 0) echo("VERSION: " . self::version . "\n");
		
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
	
	
	private function init() {
	
		$this->oi = 0;
		
		$l = 'tail -n '; 
		$l .= self::linesn . ' ';
		$l .= self::thef;
		$this->theLines = shell_exec($l);
	}
	
	private function get() { 
		
		static $i = 0;
		
		if ($i++ === 0) $l = strtok($this->theLines, "\n");
		else $l = strtok("\n");
		
		$l = trim($l);
		if (!$l) return false;
		if (!is_numeric($l[0])) return ' ';
		return $l;
	
	}
}

if (didCLICallMe(__FILE__)) new chronylog_cli_filter();
