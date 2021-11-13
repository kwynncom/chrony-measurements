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
	
	private function do70() {
		$nr = nist_backoff_calls::get(self::nistnlim);
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
		
		if (isset($this->ret['rdi']))
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
		if (!isset($this->ssa['np_span_s'])) $ss = PHP_INT_MIN;
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
		$a = $this->cha['detailed_array'];
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
