<?php
// #! /usr/bin/php // can't do this on the web

require_once('/opt/kwynn/kwutils.php');

class chronylog_cli_filter  {

	const version = '10/11 00:08 - giving up on daemon';
	const linesnExt = 100;
	const linesnInt =  46;
	const chmeaf = '/var/log/chrony/measurements.log';
	
	public function __construct($internal = false) {
		cliOrDie();
		$this->ores = [];
		$this->internal = $internal;
		$this->init();
		$this->do05();

	}
	
	public function get() { return $this->ores; }
	
	private function do05() {
		
		while ($l = $this->getLine()) {
			self::do10($l);
			$l = trim($l); 
			if (!$l) continue;
			if ($this->oi === 0 && !$this->internal) $this->oout("VERSION: " . self::version . "\n");
			if ($this->oi % 20 === 0 && !$this->internal) $this->outHeader();	
			$ret = $this->do10($l);
			if ($ret) $this->ores[] = $ret;
			$this->oi++;	
		}		
	}
	
	private function oout($s) {
		if (iscli() && !$this->internal) echo($s);
	}
	
	private function do10($l) {
		
		static $ipa = 15;
		static $now = false;
		
		if (!$now) $now = time();
		
		if (!isset($l[2])) return;

		$hu = trim(substr($l, 0, 20));
		$U = strtotime($hu . ' UTC');
		if ($U + callSNTPConfig::enterFromLogIfS < $now) return;

		$ipb = substr($l, 20);
		preg_match('/\S+/', $ipb, $ms);
		$ip = $ms[0];
		
		if ($this->internal && !isset(callSNTPConfig::NISTListA[$ip])) return false;
		
		$ipl = strlen($ip);
		$ones = substr($l, 35 + ($ipl <= $ipa ? 0 : $ipl - $ipa));
		$restl = substr($ones, 19, 66);
		$offsets = substr($restl, 12, 10);
		$offset = floatval($offsets);
		
		if (!$this->internal) {
			$this->oout($hu . ' ');	
			$this->oout(substr($ip, $ipl - 3) . ' ');		
			$this->oout($ones[16]);
			$this->oout($restl);
			$this->oout("\n");
		}
	
		unset($l, $ipb, $ipl, $ones, $restl, $ms, $offsets, $now);
		$ret = get_defined_vars();
		unset($ret['ipa']);
		
		return $ret;
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
		$l .= ($this->internal ? self::linesnInt : self::linesnExt) . ' ';
		$l .= self::chmeaf;
		$this->theLines = shell_exec($l);
	}
	
	private function getLine() { 
		
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
