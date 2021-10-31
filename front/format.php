<?php

class chrony_readouts_formatting {
	public static function get($a) {
		$ret = [];
		foreach ($a as $k => $v) {
			if ($k === 'np_span_s') $ret['np_span_min'] = sprintf('%0.1f', $v / 60);
		}
		
		return $ret;
	}
	
}

