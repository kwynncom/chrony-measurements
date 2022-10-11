<?php

function ipdis($ip) {
	
	$ml = 24;
		
	$l = strlen($ip);
	if ($l <= $ml) return $ip;
	return '...' . substr($ip, $l - $ml, $ml);
	
}

function kwChmNISTRowsIP($bain) {
	$now = time();
	$ht = '';
	$i = 0;
	$maxr = 4;
	
	foreach($bain as $hud => $a) {
		if (isset($a['ip']))
			$ip =  $a['ip'];
		
		if (isset($a['dsms']))
			$o =  $a['dsms'];
		
		if (!isset($ip) || !isset($o)) continue;
		
		$ht .= '<tr>';
		$ht .= '<td class="n60">';
		if ($o >= 0) $ht .= '&nbsp;';
		$ht .= sprintf('%0.3f', $o);
		$ht .= '</td>';
		$ht .= '<td class="n50">';
		$ht .= ipdis($ip);
		$ht .= '</td>';
		$ht .= '</tr>';
		
		unset($o, $ip);
		
		if (++$i >= $maxr) break;
		continue;
	}
	
	return $ht;
}

function kwChmAdjVia($via) {
	switch($via) {
		case 'www'  : return 'w';
	    case 'cron' : return 'r';
		case 'log'  : return 'l';
		default	    : return  '';
	}
}

function kwChmNISTRows($bain) {
	$now = time();
	$ht = '';
	foreach($bain as $hud => $a) {
		if (isset($a['dsms']))
			$o =  $a['dsms'];
		
		if (!isset($o)) continue;
		
		$ht .= '<tr>';
		$ht .= '<td class="mago20">';
		$ht .= chrony_readouts_formatting::minf($now - $a['U'], true, 1, 9.999); unset($ts);
		$ht .= '</td>';
		$ht .= '<td class="n40">';
		if (isset($o)) $ht .= sprintf('%0.3f', $o);
		$ht .= '</td>';
		$ht .= '<td>';
		$ht .= kwChmAdjVia(kwifs($a, 'via'));
		$ht .= '</td>';
		$ht .= '</tr>';
		
		unset($o);
		continue;
	}
	
	return $ht;
}

function kwChmHistRows($bain) {
	$now = time();
	$ht = '';
	foreach($bain as $hud => $a) {
		if (isset($a['fcor'])) 
			$f =  $a['fcor'];
		if (isset($a['poff']))
			$o =  $a['poff'];
		
		if (!isset($f) && !isset($o)) continue;
		
		$ht .= '<tr>';
		$ht .= '<td class="mago20">';
		$ts  = strtotime($hud);
		$ht .= chrony_readouts_formatting::minf($now - $ts, true, false); unset($ts);
		$ht .= '</td>';
		$ht .= '<td class="n40">';
		if (isset($o)) $ht .= chrony_readouts_formatting::msf10($o);
		$ht .= '</td>';
		$ht .= '<td class="fcor">';
		if (isset($f)) $ht .= $f;
		$ht .= '</td>';
		$ht .= '</tr>';
		
		unset($f, $o);
		continue;
	}
	
	return $ht;
}

