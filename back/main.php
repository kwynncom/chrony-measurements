<?php

require_once('config.php');
require_once('logsP10.php');
require_once(__DIR__ . '/../nist/callSNTPReg.php');

class chrony_analysis {
	
	const nistnlim = 9;
	
	public static function get() {
		$o = new self();
		return $o->getI();
	}
		
	private function getI() { return $this->ret; }
	private function __construct() {
		$this->ret = [];
		$this->do10();
		$this->do20();
		$this->do30();
		$this->do40();
		$this->do50();
		$this->do60();
		$this->do70();
	}
	
	public static function toms($ns) { return $ns / M_MILLION; }
	
	private function offsetOnlyCalcs($a) {
		if (!isset($a['offset'])) return;
		$or = $a;
		$s = $or['ds']   = $a['offset'];
		$or['dsms'] = $s * 1000;
		$or['dsns'] = $s * M_BILLION;
		return $or;
	}
	
	private function SNTPcalcs($ain) {
		if (!$ain) return;
		if (!isset($ain['t4Uns'])) return $this->offsetOnlyCalcs($ain);
		$or  =	   $ain;
		$a   =	$or['t4Uns'];
		$min = $or['min'] = min($a);
		for($i=0; $i < 2; $i++) $or['relmss'][$i] = self::toms($a[$i] - $min);
		$avgns = (($a[3] + $a[0]) >> 1);
		$avgs = self::toms($avgns - $min);
		$or['relmss'][2] = $avgs;
		for($i=2; $i <= 3; $i++) $or['relmss'][$i + 1] = self::toms($a[$i] - $min);
		$d = self::SNTPOffset($a);
		$or['dsns'] = $d;
		$or['ds'  ] = $d / M_BILLION;
		$or['dsms'] = $d / M_MILLION;
		$or['outms'] = self::toms($a[1] - $a[0]);
		$or['inms']  = self::toms($a[3] - $a[2]);
		return $or;
	}
	
	public static function SNTPOffset($T) {
		return ((($T[1] - $T[0]) + ($T[2] - $T[3]))) >> 1;
	}
	
	private function do65NISTPop() {
		$nr = [];
		$raw = nist_backoff_calls::get(self::nistnlim);
		foreach($raw as $r) $nr[] = $this->SNTPcalcs($r);
		return $nr;
	}
	
	private function do70() {
		$nr = $this->do65NISTPop();
		$this->ret['laoffnist'] = self::get1NIST($nr);
		$this->ret['nistall']   = $nr;
		return;
	}
	
	public static function get1NIST($n) {
		if (isset($n   ['dsms']))
		   return $n   ['dsms'];
		if (isset($n[0]['dsms']))
		   return $n[0]['dsms'];
		
		return false;
	}
	
	private function do60() {
		
		if (isset(  $this->ret['rdi']) && isset($this->ret['logs']['lpoll']))
			$maxe = $this->ret['rdi'] + 
					$this->ret['rde'] / 2.0 + 
					abs($this->ret['logs']['lpoll']) + 
					abs($this->ret['estoffa']['float']);
		else $maxe = 10000;
		
		$this->ret['maxe'] = $maxe;
		
	}
	
	private function do50() {
		$this->ret = array_merge($this->ret, $this->ssa);
	}
	
	private function do40() { 
		if (!isset($this->ssa['np_span_s']) 
				|| !is_numeric($this->ssa['np_span_s'])) $ss = false;
		else $ss = $this->ssa['np_span_s'];
		$this->ret['logs'] = [];
		$res = chrony_log_parse_p10::get($ss);	
		if (is_array($res)) $this->ret['logs'] = $res;
	}
		
	private function do30() { 
		$this->ssa = [];
		try { $this->ssa = parse_sourcestats::get();  } catch(Exception $ex) { }
	}
	
	private function do20() {
		if (!$this->cha) return;
		if (!isset($this->cha['detailed_array'])) return;
		$a = $this->cha['detailed_array'];
		if (!isset(  $a['Ref time (UTC)']['s_ago'])) return;
		$lastPollS = $a['Ref time (UTC)']['s_ago'];
		$rfr	   = $a['Residual freq'];
		$rdi	   = $a['Root dispersion'];
		$rde	   = $a['Root delay'];
		$estoffa   = $a['System time'];
		unset($a);
		$this->ret = array_merge($this->ret, get_defined_vars());
	}
	
	private function do10() {
		require_once(getChronyParserPath());
		$this->cha = [];
		try {
		$this->cha = chrony_parse::get();
		} catch(Exception $ex) { }
	}
}

if (didCLICallMe(__FILE__)) print_r(chrony_analysis::get());
