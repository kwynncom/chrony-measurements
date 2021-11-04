<?php

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
			if ($k === 'laoffnist') $ret[$k]	   = $v !== false ? self::msf10($v, 1) : ''; 
			if ($k === 'maxe')      $ret[$k]  = self::ifabovems($v);
			if ($k === 'rfr')       $ret[$k]  = sprintf('%0.3f', $v);		
		}
		
		$now = time();
		$ret['asof'] = date('g:ia D m/d', $now) . ' (' . date('s', $now) . 's) ' . date('P', $now);
		
		$ret['lpoll'] = $lpoll = self::msf10($a['logs']['lpoll']);
		
		return $ret;
	}
	
	public static function msf10($v, $mby = 1000) {
		return sprintf('%0.6f', $v * $mby);

	}

	public static function minf($vin, $divide = true) {
		$v = $vin;
		if ($divide) $v /= 60;
		return intval(round($v));
	}	
	
	public static function ifabovems($vin) {
		$v = $vin * 1000;
		$ret = sprintf('%0.2f', $v);
		return $ret;
	}
} // class

