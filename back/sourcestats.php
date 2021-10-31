<?php

require_once('/opt/kwynn/kwutils.php');

class parse_sourcestats {
    
    public $np = [];
    
    public static function get() {
	$o = new self();
	return $o->np;
    }
    
    private function __construct() {
	$this->do10();
    }
    
    private function do10() {
		$ret = [];

		$r = shell_exec('/usr/bin/chronyc sourcestats'); 

		kwas($r && is_string($r), 'invalid sourcestats result - string');
		kwas(preg_match('/\b\S+\s+(\d+)\s+\d+\s+(\d+)([dhm]?)/', $r, $ms) && isset($ms[2]), 'sourcestats invalid result - regex'); // see ssExOut in README
		$ss = $ms[2] * (isset($ms[3]) ? 
			  self::dhmsX($ms[3]) : 1 );
		$np = intval($ms[1]);

		$ret = [ 'np'   => $np, 'np_span_s' => $ss ];
		$this->np = $ret;
    }
    
    public static function dhmsX($uin) {
		if (!$uin || !is_string($uin)) return 1;
		$u = trim($uin[0]);
		switch($u) {
			case 'd' : return 84600; break;
			case 'h' : return  3600; break;
			case 'm' : return    60; break;
			case 's' : return     1; break;
			default  : return     1; break;
		}
    }
}

if (didCLICallMe(__FILE__)) print_r(parse_sourcestats::get());