<?php

require_once('logsRaw.php');

class chrony_log_parse_p10 extends chrony_log_parse {
	private function __construct($npss) {
		parent::__construct($npss);
		$this->p10();
	}
	
	public static function get($npss) {
		$o = new self($npss);
		return $o->getI();
    }
	
	public function getI() { return $this->p10a; }
	
	private function p10() {
		$ba = $this->linea;
		foreach($ba as $hudnotz => $a) {
			$hudtz =   $hudnotz . ' UTC';
			if (isset($a['m'][11])) $r[$hudtz]['poff'] = floatval($a['m'][11]);
			if (isset($a['t'][ 4])) $r[$hudtz]['fcor'] =		  $a['t'][ 4];			
		}
		
		$this->p10a = $r;
	}
	
}
