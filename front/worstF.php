<?php

require_once('/opt/kwynn/kwutils.php');

class WorstF {
	
	const cln = 4;
	
	public static function get($a, $n = self::cln) {
		$o = new self($a, $n);
		return $o->getI();
	}
	
	public function getI() { return $this->oht; } 
	
	private function __construct($a, $n) {
		$this->on = $n;
		$this->odin = $a;
		$this->do10();
		return;
		
	}
	
	private function do10() {
		$a = $this->odin;
		$a = array_slice($a, 0, $this->on);
		ob_start();
		foreach($a as $r) $this->pop($r);
		$ht  = '<div class="worp">';
		$ht .= ob_get_clean();
		$ht .= '</div>';
		$this->oht = $ht;
	}
	
	private function pop($din) {
		
		static $now = false;
		if (!$now) $now = time();
		
		$msr = $din['absoff'] * 1000;
		$sago = $now - $din['U'];
		$hrr = $sago / 3600;

		$hr = $this->hrf($hrr);
		$ms = sprintf('%0.1f', $msr);
		
		require(__DIR__ . '/worstT.php');		
	}
	
	private function hrf($h) {
		$f  = '%0.';
		
		if ($h >= 0.999 && $h <   5) $d = 1;
		else if (		   $h >=  5) $d = 0;
		else $d = 3;
				
		$f .= $d;
		$f .= 'f';
		return sprintf($f, $h);	
	}
}
