<?php

class chrony_readouts_formatting {
	public static function get($a) {
		$ret = $a;
		foreach ($a as $k => $v) {
			if ($k === 'np_span_s') $ret['np_span_min'] = sprintf('%0.1f', $v / 60);
			if ($k === 'lastPollS') $ret['lpmin']		= sprintf('%0.1f', $v / 60);
			if ($k === 'rdi')		$ret[$k]       = self::ifabove($v);
			if ($k === 'rde')		$ret[$k]       = self::ifabove($v);
			if ($k === 'estoffa') 	$ret['estoff'] = number_format(round($v['float'] * M_BILLION)) . ' ns (' . $v['direction'] . ')';
			if ($k === 'laoff')     $ret[$k]  = sprintf('%0.2f', $v * M_MILLION) . '&micro;s';
			if ($k === 'maxe')      $ret[$k]  = self::ifabove($v);
			if ($k === 'rfr')       $ret[$k]  = sprintf('%0.3f', $v) . ' ppm';		
		}
		
		return $ret;
	}
	
	public static function ifabove($vin) {
		$v = $vin * 1000;
		if ($v < 8) $ret = sprintf('%0.2f', $v) . ' ms';
		else		$ret = intval(round($v));
		$ret .= ' ms';
		return $ret;
	}
	
}

