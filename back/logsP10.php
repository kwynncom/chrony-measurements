<?php

require_once('logsRaw.php');

class chrony_log_parse_p10 extends chrony_log_parse {
	private function __construct($npss) {
		parent::__construct($npss);
		$this->p10();
	}
	
	public static function get($npss) {
		$o = new self($npss);
		return $o->linea;
    }
	
	private function p10() {
		$a = $this->linea;
	}
	
}
