<?php

require_once('/opt/kwynn/kwutils.php');
require_once('sourcestats.php');

class chrony_log_parse {
    
    const tailn = 8;
	const path  = '/var/log/chrony/';
	const files = ['tracking.log', /* 'statistics.log', */ 'measurements.log'];
    
    public $an10 = [];
    
    protected function __construct($npss) {
		$this->npss = $npss;
		$this->linea = false;		
		$this->load10();
		$this->do10();
		$this->do20();
		if (!isset($this->maxe)) return;
		$this->an10['maxe'] = $this->maxe;
    }
    
    public static function get($npss) {
		$o = new self($npss);
		return $o->linea;
    }
    
    private function do20() {
		
	if (!isset($this->lpa10)) return;
		
	$a = $this->lpa10;
	$fi = 0;
	$n = count($a);
	if ($n < 2) return;
	$li = $n - 1;
	$spans = $a[$fi]['ts'] - $a[$li]['ts']; 
	
	$change = 0;
	for($i=0; $i < $li; $i++) $change += abs($a[$i  ]['freq_corr'] - 
					         $a[$i+1]['freq_corr']);

	unset($fi, $li, $i, $a);
	
	$change_freq_corr = $change; unset($change);
	
	$this->an10 = get_defined_vars();
	
	return;
	
	
    }
    
    public static function getE($e) {
	kwas(preg_match('/^\d+\.\d+e[+-]\d+$/', $e, $ms), "getE failed with input $e");
	return floatval($ms[0]);
	
    }
    
    private function do10() {
	$ret = [];
	$maxe = false;
	
	if (!is_array($this->linea)) return;
	foreach(      $this->linea as $ts => $snas)
	{
		if (!isset($snas['t'])) continue;
		$l =	   $snas['t'];
	    $t = [];
	    $ds = $t['ds'] = $l[0] . ' ' . $l[1] . ' UTC';
	    $ts = $t['ts'] = strtotime($ds);
	    $t['dss'] = date('h:i:s A', $ts);
	    if (!preg_match('/^-?\d+\.\d+$/',$l[4], $ms)) continue;
	    $t['freq_corr'] = floatval($ms[0]);
	   
	    if ($maxe === false) $maxe = self::getE($l[13]);
    
	    $ret[] = $t;
	    continue;
	}
	
	$this->maxe = $maxe;
	$this->lpa10 = $ret;
    }
    
    private function load10() {
		
		$now = time();
		$thea = [];	

		foreach(self::files as $file) {
		
			$fsn = substr($file, 0, 1);
			
			$cmd  = '';
			$cmd .= 'tail -n ';
			$cmd .= self::tailn + 3; // account for headers
			$cmd .=  ' ';
			$cmd .= self::path . $file; unset($file);
			$cmd .= ' | tac';

			$t = shell_exec($cmd); kwas($t && is_string($t) && strlen($t) > 30, 'chrony tracking file load fail shell'); unset($cmd);
			$la = explode("\n", $t); unset($t);
			$lii = 0;
		
			foreach($la as $l) {
				if (!$l) continue; // the blank string following the last line
				if (strpos($l, '='   ) !== false) continue; // header =====
				if (strpos($l, 'Date') !== false) continue; // header labels
				$a = preg_split('/\s+/', $l); unset($l);
				
				if ($fsn === 'm') {
					kwynn();
					if ($a[7] !== '1111') continue; // score fails
				}
				
				$dhu = $a[0] . ' ' . $a[1];
				$ts  = strtotime($dhu . ' UTC');
				$pd  = $now - $this->npss;
				if ($ts < $pd) continue; unset($pd, $ts);
				$ta[$dhu][$fsn] = $a; unset($a, $dhu);
				$thea[] = $ta; unset($ta);

				if (++$lii >= self::tailn) break;
			} unset($la, $lii);
		} unset($now, $fsn);
		
		$this->setHuA($thea);
    }
	
	private function setHuA($ain) { /* This turns $thea[0]['2021...']['t'] into $rea['2021']['t'].  Off hand, I can't think of a way to do it directly and still use 
									   usort.  I need  indexes for usort. */	
		usort($ain, ['self', 'sort']);
		foreach($ain as $iIgnore => $hudia) // human date indexed array
		foreach($hudia as $hud => $ltya) // human date => log type array ['t'] or ['m'] 
		foreach($ltya as $lty  => $fvsa) // log type => final values array
			$rea[$hud][$lty] = $fvsa;
		unset($thea, $iIgnore, $hudia, $hud, $ltya, $lty, $fvsa, $ain);
		if (isset($rea)) $this->linea = $rea;		
	}
	
	private static function sort($a, $b) { return -(strtotime(key($a)) - strtotime(key($b))); }
	
}

if (didCLICallMe(__FILE__)) print_r(chrony_log_parse::get());
