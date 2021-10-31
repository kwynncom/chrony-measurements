<?php

class chrony_readouts_formatting {
	public static function get($a) {
		$ret = $a;
		foreach ($a as $k => $v) {
			if ($k === 'np_span_s') $ret['np_span_min'] = sprintf('%0.1f', $v / 60);
			if ($k === 'lastPollS') $ret['lpmin']		= sprintf('%0.1f', $v / 60);
			if ($k === 'rdi')		$ret['rdius']       = intval(round($v * M_MILLION));
			if ($k === 'estoffa') 	$ret['estoff'] = number_format(round($v['float'] * M_BILLION)) . ' ns (' . $v['direction'] . ')';
			if ($k === 'laoff')     $ret[$k]  = number_format(round($v		     * M_MILLION)) . '&micro;s';
			if ($k === 'maxe')      $ret[$k]  = sprintf('%0.2f', $v * 1000) . ' ms';
		}
		
		return $ret;
	}
	
}

