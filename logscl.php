<?php

require_once(__DIR__ . '/nist/config.php');

class chronylog_cli_filter  {

	const version = '10/11 22:49 - abstracted log info';
	const linesnExt = 100;
	const linesnInt =  76;
	const chmeaf = '/var/log/chrony/measurements.log';
	
	public function __construct(bool $internal = false, array $ec = []) {
		// cliOrDie();
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
		
		static $lnns = 0;
		static $now = false;
		
		if (!$now) $now = time();
		
		$li = self::getLLI($l, !$this->internal);
		extract($li); unset($li);

		if ($this->internal && ($U + callSNTPConfig::enterFromLogIfS < $now)) return;
		if ($this->internal && !isset(callSNTPConfig::NISTListA[$ip])) return;
		
		if ($this->testEndCrit($U, $hu, $ip, $offset)) return TRUE;
		
		if (!$this->internal) {
			$this->oout($hu . ' ');	
			$ipl = strlen($ip);
			$this->oout(substr($ip, $ipl - 3) . ' '); unset($ipl);	
			$this->oout($valc); unset($valc);
			$this->oout($restl); unset($restl);
			$this->oout("\n");
		}
	
		if ($this->internal) {
			$lnn = $this->oln - $lnns;
			$lnns++;
		}
		
		unset($l);
		$ret = get_defined_vars();
		unset($ret['lnns'], $ret['now']);
		
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
		if (!amDebugging() && iscli() && !$this->internal) $l .= '-f ';
		$l .= self::chmeaf;
		if ($this->internal)
			$l .= ' | tac';
		if ($this->internal) {
			$this->orawt = shell_exec($l);
			$this->oba = explode("\n", trim($this->orawt));
			$this->oln = count($this->oba);
		} else $this->setFollow($l);
		
	}

	public static function getLLI(string $l, bool $exv = false) {
		$ipa = 15; // length of 255.255.255.255 ; I unset it below, so don't use static
		if (!$l || !isset($l[76])) return; // I want at least 76 chars
		$hu = trim(substr($l, 0, 20));
		$Uactual = strtotime($hu . ' UTC');
		$U = $Uus = $Uactual + 1;
		$ipb = substr($l, 20);
		preg_match('/\S+/', $ipb, $ms); unset($ipb);
		$ip = $ms[0]; unset($ms);
		$ipl = strlen($ip);
		$r20 = substr($l, 35 + ($ipl <= $ipa ? 0 : $ipl - $ipa)); unset($ipl, $ipa, $l);
		$restl = substr($r20, 19, 66);
		$valc = $r20[16]; unset($r20);
		$offsets = substr($restl, 12, 10); 
		$offset = floatval($offsets); unset($offsets);
		if (!$exv) unset($valc, $restl); unset($exv);	
		$vars = get_defined_vars();
		return $vars;
	}
	
}

if (didCLICallMe(__FILE__)) new chronylog_cli_filter();
