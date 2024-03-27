<?php

require_once('logsRaw.php');

class chrony_log_parse_p10 extends chrony_log_parse {

	private array $p10a;

	private function __construct($npss = self::defaultSpanS) {
		parent::__construct($npss);
		$this->p10();
	}
	
	public static function get($npss = self::defaultSpanS) {
		$o = new self($npss);
		return $o->getI();
    }
	
	public function getI() { 
		if (!isset($this->p10a)) return [];
		return     $this->p10a; 
	}
	
	private function p10() {
		$fp = false;
		$ba = $this->linea;
		if (!is_array($ba)) return;
		foreach($ba as $hudnotz => $a) {
			$hudtz =   $hudnotz . ' UTC';
			if (isset($a['m'][11])) {
				$t = $r[$hudtz]['poff'] = floatval($a['m'][11]);
				if ($fp === false) $fp = $t;
			}
			if (isset($a['t'][ 4])) $r[$hudtz]['fcor'] =		  $a['t'][ 4];			
		}
		
		$this->p10a['logs']  = $r;
		if ($fp !== false) $this->p10a['lpoll'] = floatval($fp);
	}
	
}
