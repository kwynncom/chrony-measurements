<?php

require_once(__DIR__ . '/nist/config.php');

class chronylog_cli_filter  {

	const version = '10/16 22:39 - CLI / self only';
	const linesnExt = 100;
	const chmeaf = callSNTPConfig::chronyLogF;
	
	public function __construct() {
		$this->init();
		$this->do05();
	}
	
	public function __destruct() {
		if (isset($this->ohan) && $this->ohan) pclose($this->ohan);
	}
	
	
	private function do05() {

		while ($l = $this->getLine()) {
			$l = trim($l); 
			if (!$l) continue;
			if (!is_numeric($l[0])) continue;
			if ($this->oi === 0) $this->oout("VERSION: " . self::version . "\n");
			if ($this->oi % 20 === 0) $this->outHeader();	
			$this->do30($l);
			$this->oi++;	
		}		
	}
	
	private function openFile($cmd) {
		$this->ohan = popen($cmd, 'r');
	}
	
	private function getLine() {
		return fgets($this->ohan);
	}
	
	
	private function oout($s) {
		if (iscli()) echo($s);
	}
	
	private function do30($l) {
		
		
		$li = self::getLLI($l, true);
		extract($li); unset($li);


		$this->oout($hu . ' ');	
		$ipl = strlen($ip);
		$this->oout(substr($ip, $ipl - 3) . ' '); unset($ipl);	
		$this->oout($valc); unset($valc);
		$this->oout($restl); unset($restl);
		$this->oout("\n");

	
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
		$l .= self::linesnExt . ' ';
		if (!amDebugging() && iscli()) $l .= '-f ';
		$l .= self::chmeaf;
		$this->openFile($l);
		
	}

	public static function getLLI(string $l, bool $exv = false) {
		$ipa = 15; // length of 255.255.255.255 ; I unset it below, so don't use static
		
		if (!$l) return;
		if (!is_numeric($l[0])) return;
		if (!isset($l[76])) return; // I want at least 76 chars
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
