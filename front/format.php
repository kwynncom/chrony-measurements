<?php

class chrony_readouts_formatting {
	public static function get($a) {
		$ret = $a;
		foreach ($a as $k => $v) {
			if ($k === 'np_span_s') $ret['np_span_min'] = self::ifabovemin($v);
			if ($k === 'lastPollS') $ret['lpmin']		= self::ifabovemin($v);
			if ($k === 'rdi')		$ret[$k]       = self::ifabovems($v);
			if ($k === 'rde')		$ret[$k]       = self::ifabovems($v);
			if ($k === 'estoffa') 	$ret['estoff'] = sprintf('%0.3f', $v['float'] * M_MILLION) . ' &micro;s (' . $v['direction'] . ')';
			if ($k === 'laoff')     $ret[$k]  = sprintf('%0.2f', $v * M_MILLION) . '&micro;s';
			if ($k === 'maxe')      $ret[$k]  = self::ifabovems($v);
			if ($k === 'rfr')       $ret[$k]  = sprintf('%0.3f', $v) . ' ppm';		
		}
		
		return $ret;
	}

	public static function ifabovemin($vin) {
		$v = $vin / 60;
		if ($v < 9.999) return sprintf('%0.1f', $v);
		else		    return intval(round($v));
	}	
	
	
	public static function ifabovems($vin) {
		$v = $vin * 1000;
		if ($v < 8) $ret = sprintf('%0.2f', $v);
		else		$ret = intval(round($v));
		$ret .= ' ms';
		return $ret;
	}
	
}

