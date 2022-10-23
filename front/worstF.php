<?php

class WorstF {
	
	const cln = 3;
	
	public static function get($a) {
		$o = new self($a);
		return $o->getI();
	}
	
	public function getI() { return $this->oht; } 
	
	private function __construct($a) {
		$this->odin = $a;
		$this->do10();
		return;
		
	}
	
	private function do10() {
		$t = $this->odin;
		$a = array_slice($t['worst'], 0, self::cln);
		ob_start();
		foreach($a as $r) $this->pop($r);
		$ht = ob_get_clean();
		$this->oht = $ht;
	}
	
	private function pop($din) {
		
		static $now = false;
		if (!$now) $now = time();
		
		$msr = $din['absoff'] * 1000;
		$sago = $now - $din['U'];
		$hr  = sprintf('%0.3f', $sago / 3600);
		
		$ms = sprintf('%0.1f', $msr);
		// $ht = $ms .  '@' . $hr;
		
		require(__DIR__ . '/worstT.php');		
	}
}
