<?php

require_once('/opt/kwynn/kwutils.php');

class WorstF {
	
	// const cldn = 9;
	const clwn = 4;
	const clrn = 100;
	
	public static function get($recent, $worst) {
		$o = new self($recent, $worst);
		return $o->getI();
	}
	
	public function getI() { return $this->oht; } 
	
	private function __construct($r, $w) {
		$this->oht = '';
		$this->do05($r, $w);
		$this->do10();
		return;
		
	}
	
	private function sort($a, $b) {
		return $b['U'] - $a['U'];
	}
	
	private function filterForStarts(&$a, $big) {
		$ss = kwifs($big, 'chstarts');
		if (!$ss) return;
		$rm = [];
		foreach($a as $i => $r) foreach($ss as $s) 
			if (abs($r['U'] - $s['U']) < 30) $rm[] = $i;
		
		foreach($rm as $i) unset($a[$i]);
		$a = array_values($a);
		return;
	}
	
	private function do05($r, $win) {
		$w = $win['worst'];
		$r = array_slice($r, 0, self::clrn);
		$w = array_slice($w, 0, self::clwn);
		$a = kwam($r, $w);
		$this->filterForStarts($a, $win);
		usort($a, [$this, 'sort']);
		$this->oa = $a;
		return;
		
		
	}
	
	private function do10() {
		ob_start();
		require_once(__DIR__ . '/worstT.php');
		$ht = ob_get_clean();
		foreach($this->oa as $r) $ht .= $this->pop($r);
		$ht .= '</tbody></table>';
		$this->oht = $ht;
	}
	
	private function pop($din) {
	
		static $max = -1;
		static $maxd = '';
		static $now = false;
		if (!$now) $now = time();
		
		$msr = $din['absoff'] * 1000;
		if ($msr < $max) return '';
		$max = $msr;
		$sago = $now - $din['U'];
		$hrr = $sago / 3600;

		$ms = sprintf('%0.1f', $msr);
		if ($ms === $maxd) return '';
		$maxd = $ms;
		
		$ht = '';
		$ht .= '<tr>';
		$ht .= '<td class="worhr">';
		$ht .= sprintf('%0.1f', $hrr);
		$ht .= '</td>';	
		$ht .= '<td class="woroff">';
		$ht .= $ms;
		$ht .= '</td>';	
		$ht .= '</tr>';
		return $ht;
		
	
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
