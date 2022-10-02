<?php

require_once('/opt/kwynn/kwutils.php');

class chronylog_cli_filter {
	public function __construct() {
		$this->init();
		while ($l = $this->get()) $this->do10($l);
	}
	
	private function do10($l) {
		
		static $ipa = 15;
		
		echo(substr($l, 0, 20));
		$ipb = substr($l, 20);
		preg_match('/\S+/', $ipb, $ms);
		$ip = $ms[0];
		$ipl = strlen($ip);
		echo(substr($ip, $ipl - 3) . ' ');
		$ones = substr($l, 35 + ($ipl <= $ipa ? 0 : $ipl - $ipa));
		echo($ones);
		echo("\n");
		return;
	}
	
	private function init() {
		//						-f /var/log/chrony/measurements.log
		$this->ohan = popen('tail -n 100    /var/kwynn/chm.log', 'r');		
		$this->oi = 0;

	}
	
	private function get() { return trim(fgets($this->ohan));}
}

if (iscli()) new chronylog_cli_filter();
