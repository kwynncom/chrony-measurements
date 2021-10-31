<?php

class chrony_readouts_formatting {
	public static function get($a) {
		
		$offf = 1000;
		
		$ret = $a;
		foreach ($a as $k => $v) {
			if ($k === 'np')	    $ret[$k] = $v . '&nbsp;&nbsp;';
			if ($k === 'np_span_s') $ret['np_span_min'] = self::minf($v);
			if ($k === 'lastPollS') $ret['lpmin']		= self::minf($v);
			if ($k === 'rdi')		$ret[$k]       = self::ifabovems($v);
			if ($k === 'rde')		$ret[$k]       = self::ifabovems($v);
			if ($k === 'estoffa') 	$ret['estoff'] = sprintf('%0.6f', $v['float'] * $offf);
			if ($k === 'laoff')     $ret[$k]	   = sprintf('%0.6f', $v * $offf);
			if ($k === 'maxe')      $ret[$k]  = self::ifabovems($v);
			if ($k === 'rfr')       $ret[$k]  = sprintf('%0.3f', $v);		
		}
		
		$ret['asof'] = date('g:ia D m/d');
		
		return $ret;
	}

	public static function minf($vin, $divide = true) {
		$v = $vin;
		if ($divide) $v /= 60;
		if ($v > 9.95) return $v . '&nbsp;&nbsp;';
		return sprintf('%0.1f', $v);
	}	
	
	public static function ifabovems($vin) {
		$v = $vin * 1000;
		$ret = sprintf('%0.2f', $v);
		return $ret;
	}
} // class

