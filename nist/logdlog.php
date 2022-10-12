<?php

class ch_logd_log {
	
	const llf = '/var/kwynn/mysd/ll.txt';
	
	public function __construct() {
		$this->out('start');
	}
	
	public function out(...$ss) {
		$this->theo(date('r') . ':');		
		foreach($ss as $s) $this->theo($s);
		$this->theo("\n");
	}
	
	private function theo($s) {
		if ($s !== "\n") $s .= ' ';
		file_put_contents(self::llf, $s, FILE_APPEND);
	}
}

