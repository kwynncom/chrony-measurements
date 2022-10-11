<?php
// #! /usr/bin/php // can't do this on the web

// require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/nist/config.php');

class chronylog_cli_filter  {

	const version = '10/11 03:38 - nearing rc';
	const linesnExt = 100;
	const linesnInt =  76;
	const chmeaf = '/var/log/chrony/measurements.log';
	
	public function __construct(bool $internal = false, array $ec = []) {
		cliOrDie();
		$this->ores = [];
		$this->internal = $internal;
		$this->oec = $ec;
		$this->init();
		$this->do05();

	}
	
	public function __destruct() {
		if (isset($this->ohan) && $this->ohan) pclose($this->ohan);
	}
	
	public function get() { return array_reverse($this->ores); }
	
	private function do05() {

		while ($l = $this->getLine()) {
			$l = trim($l); 
			if (!$l) continue;
			if (!is_numeric($l[0])) continue;
			if ($this->oi === 0 && !$this->internal) $this->oout("VERSION: " . self::version . "\n");
			if ($this->oi % 20 === 0 && !$this->internal) $this->outHeader();	
			$ret = $this->do30($l);
			if ($ret === true) break;
			if ($ret) $this->ores[] = $ret;
			$this->oi++;	
		}		
	}
	
	private function setFollow($cmd) {
		$this->ohan = popen($cmd, 'r');
		
	}
	
	private function getLine() {
		if ($this->internal) return $this->glstatic();
		return fgets($this->ohan);
	}
	
	private function glstatic() {
		if (!isset($this->oabsi)) $this->oabsi = 0;
		return kwifs($this->oba, $this->oabsi++);
	}
	
	
	private function oout($s) {
		if (iscli() && !$this->internal) echo($s);
	}
	
	private function testEndCrit($Ui, $hui, $ipi, $offseti) {
		if (!$this->oec) return;
		extract($this->oec);
		if ($Ui  !== $Uactual ) return;
		if ($ipi !== $ip) return;
		if (!isFlTSEq($offseti, $offset)) return;
		return TRUE;
	}
	
	private function do30($l) {
		
		static $ipa = 15;
		static $lnns = 0;
		static $now = false;
		
		if (!$now) $now = time();

		$hu = trim(substr($l, 0, 20));
		$U = strtotime($hu . ' UTC');
		if ($this->internal && ($U + callSNTPConfig::enterFromLogIfS < $now)) return;

		$ipb = substr($l, 20);
		if (!preg_match('/\S+/', $ipb, $ms)) {
			kwynn();
		}
		$ip = $ms[0];
		
		if ($this->internal && !isset(callSNTPConfig::NISTListA[$ip])) return;
		
		$ipl = strlen($ip);
		$ones = substr($l, 35 + ($ipl <= $ipa ? 0 : $ipl - $ipa));
		$restl = substr($ones, 19, 66);
		$offsets = substr($restl, 12, 10);
		$offset = floatval($offsets);
		
		if ($this->testEndCrit($U, $hu, $ip, $offset)) return TRUE;
		
		if (!$this->internal) {
			$this->oout($hu . ' ');	
			$this->oout(substr($ip, $ipl - 3) . ' ');		
			$this->oout($ones[16]);
			$this->oout($restl);
			$this->oout("\n");
		}
	
		if ($this->internal) {
			$lnn = $this->oln - $lnns;
			$lnns++;
		}
		
		unset($l, $ipb, $ipl, $ones, $restl, $ms, $offsets, $now);
		$ret = get_defined_vars();
		unset($ret['ipa'], $ret['lnns']);
		
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
		if (1 && iscli() && !$this->internal) $l .= '-f ';
		$l .= self::chmeaf;
		if ($this->internal)
			$l .= ' | tac';
		if ($this->internal) {
			$this->orawt = shell_exec($l);
			$this->oba = explode("\n", trim($this->orawt));
			$this->oln = count($this->oba);
		} else $this->setFollow($l);
		
	}

}

if (didCLICallMe(__FILE__)) new chronylog_cli_filter();
