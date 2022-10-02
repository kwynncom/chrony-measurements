<?php

class chronylog_cli_filter {
	public function __construct() {
		$this->init();
		while ($l = $this->get()) $this->do10($l);
	}
	
	private function do10($l) {
		echo(substr($l, 0, 20) . "\n");
	}
	
	private function init() {
		//						-f /var/log/chrony/measurements.log
		$this->ohan = popen('tail -n 100    /var/kwynn/chm.log', 'r');		
		$this->oi = 0;

	}
	
	private function get() { return fgets($this->ohan);}
}

new chronylog_cli_filter();