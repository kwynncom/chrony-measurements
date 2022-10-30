<?php

require_once('worstF.php');

class chrony_readouts_formatting {
	
	public static function get($a) {
		
		$ret = $a;
		foreach ($a as $k => $v) {
			if ($k === 'np')	    $ret[$k] = $v;
			if ($k === 'np_span_s') $ret['np_span_min'] = self::minf($v);
			if ($k === 'lastPollS') $ret['lpmin']		= self::minf($v);
			if ($k === 'rdi')		$ret[$k]       = self::ifabovems($v);
			if ($k === 'rde')		$ret[$k]       = self::ifabovems($v);
			if ($k === 'estoffa') 	$ret['estoff'] = self::msf10($v['float']);
			if ($k === 'laoffnist') $ret[$k]	   = self::msf10($v, 1); 
			if ($k === 'maxe')      $ret[$k]  = self::maxef($v);
			if ($k === 'rfr')       $ret[$k]  = sprintf('%0.3f', $v);		
		}
		
		$now = time();
		$ret['asof'] = date('g:ia D m/d', $now) . ' (' . date('s', $now) . 's) ' . date('P', $now);
		
		if (!isset(   $a['logs']['lpoll'])) $lpoll = false;
		else $lpoll = $a['logs']['lpoll'];
		
		$lpoll = self::msf10($lpoll);
		
		$ret['lpoll'] = $lpoll;
		
		if (!isset($ret['logs']['logs']))
				   $ret['logs']['logs'] = [];
		
		$ret['worstHT']  = WorstF::get($a['worstRe'], $a['worstAn']);

		return $ret;
	}
	
	public static function maxef($maxein) {
		if ($maxein < 1000) return self::ifabovems($maxein);
		else return '?';
		
	}
	
	public static function msf10($v, $mby = 1000) {
		if ($v === false) return '';
		return sprintf('%0.6f', $v * $mby);

	}

	public static function minf($vin, $divide = true, $digr = false, $spifgt = PHP_FLOAT_MAX) {
		$v = $vin;
		
		if (!is_numeric($v)) return '-';
		
		if ($divide) $v /= 60;
		$rounded = intval(round($v));
		if (!is_integer($digr))    return $rounded;
		if ($v < $spifgt && $digr) return sprintf('%0.' . $digr . 'f', $v);
		$ret = $rounded;
		for ($i = 0; $i <= $digr; $i++) $ret .= '&nbsp;';
		return $ret;
	}	
	
	public static function ifabovems($vin) {
		$v = $vin * 1000;
		$ret = sprintf('%0.2f', $v);
		return $ret;
	}
} // class

