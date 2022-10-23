<?php

require_once(__DIR__ . '/../nist/summary.php');

class sntpWorstAn {
	private function __construct() {
		$this->do10();
		return;
	}
	
	private function do10() {
		$r = nist_summary::get();
		$this->oret = $r;
	}
	
	public function getI() { return $this->oret; }
	
	public static function get() {
		$o = new self();
		return $o->getI();
	}
}

