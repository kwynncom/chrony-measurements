<?php

require_once('config.php');
require_once('logs.php');

class chrony_analysis {
	public static function get() {
		$o = new self();
	}
	
	private function __construct() {
		$this->do10();
		$this->do20();
		$this->do30();
		$this->do40();
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
		kwas(isset($a['detailed_array']['Ref time (UTC)']['hours_ago']), 'ref time hrs ne');
		$phr =	   $a['detailed_array']['Ref time (UTC)']['hours_ago'];
		kwas(isset($a['detailed_array']['RMS offset']), 'RMS O ne');	
		$rms =	   $a['detailed_array']['RMS offset'];
		kwas(isset($a['detailed_array']['Residual freq']), 'RMS O ne');
		$rfr =     $a['detailed_array']['Residual freq'];
		kwas(isset($a['detailed_array']['Root dispersion']), 'RMS O ne');
		$rdi =     $a['detailed_array']['Root dispersion'];

		return;		
	}
	
	private function do10() {
		require_once(getChronyParserPath());
		$this->cha = chrony_parse::get(1);
	}
}

if (didCLICallMe(__FILE__)) print_r(chrony_analysis::get());
