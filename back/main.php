<?php

require_once('config.php');
require_once('logs.php');
require_once('nist/callSNTPReg.php');

class chrony_analysis {
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
		$this->ret['nist'] = nist_backoff_calls::get();
		return;
	}
	
	private function do60() {
		$maxe = $this->ret['rdi'] + 
				$this->ret['rde'] / 2.0 + 
				abs($this->ret['laoff']) + 
				abs($this->ret['estoffa']['float']);
		
		$this->ret['maxe'] = $maxe;
		
	}
	
	private function do50() {
		$this->ret = array_merge($this->ret, $this->ssa);
	}
	
	private function do40() {
		$this->lpa = chrony_log_parse::get($this->ssa['np_span_s']);
		return;
	}
		
	private function do30() {
		$this->ssa = parse_sourcestats::get();
		return;
	}
	
	private function do20() {
		$a = $this->cha;
		kwas(isset($a['detailed_array']['Ref time (UTC)']['s_ago'    ]), 'ref time s_ago ne');
		kwas(isset($a['detailed_array']['Ref time (UTC)']['UNIX Epoch']), 'ref time UE ne');
		kwas(isset(  $a['detailed_array']['Ref time (UTC)']['hours_ago']), 'ref time hrs ne');
		$lastPollS = $a['detailed_array']['Ref time (UTC)']['s_ago'];
		kwas(isset($a['detailed_array']['RMS offset']), 'RMS O ne');	
		// $rms =	   $a['detailed_array']['RMS offset'];
		kwas(isset($a['detailed_array']['Residual freq']), 'RMS O ne');
		$rfr =     $a['detailed_array']['Residual freq'];
		kwas(isset($a['detailed_array']['Root dispersion']), 'RMS O ne');
		$rdi =     $a['detailed_array']['Root dispersion'];
		$laoff  =  $a['detailed_array']['Last offset'];
		$rde =		$a['detailed_array']['Root delay'];
		
		$estoffa =     $a['detailed_array']['System time'];
		
		unset($a);
		
		$this->ret = array_merge($this->ret, get_defined_vars());
		
		return;		
	}
	
	private function do10() {
		require_once(getChronyParserPath());
		$this->cha = chrony_parse::get();
	}
}

if (didCLICallMe(__FILE__)) print_r(chrony_analysis::get());
