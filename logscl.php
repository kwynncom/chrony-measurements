<?php

require_once('/opt/kwynn/kwutils.php');

class chronylog_cli_filter {
	public function __construct() {
		$this->init();
		while ($l = $this->get()) $this->do10($l);
	}
	
	private function do10($l) {
		
		static $ipa = 15;
		
		if (!trim($l)) return;
		
		if ($this->oi === 0) $this->outHeader();
			
		
		echo(substr($l, 0, 20));
		$ipb = substr($l, 20);
		preg_match('/\S+/', $ipb, $ms);
		$ip = $ms[0];
		$ipl = strlen($ip);
		echo(substr($ip, $ipl - 3) . ' ');
		$ones = substr($l, 35 + ($ipl <= $ipa ? 0 : $ipl - $ipa));
		echo($ones[16]);
		echo(substr($ones, 19, 66));
		echo("\n");
		$this->oi++;
		return;
	}
	
	private function outHeader() {
		static $o = false;
		$s = <<<CHRH
   Date (UTC)    Time   IP 6 LP RP Score    Offset  Peer del.  Peer disp. Root del.  Root disp. Refid     MTxRx
CHRH;
		if ($o === false) $o = substr(trim($s), 0, 91);
		echo($o . "\n");
	}
	
	
	
	
	private function init() {
		//						-f /var/log/chrony/measurements.log
		$this->ohan = popen('tail -n 100    /var/kwynn/chm.log', 'r');		
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
