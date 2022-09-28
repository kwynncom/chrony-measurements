<?php

function kwChmNISTRowsIP($bain) {
	$now = time();
	$ht = '';
	foreach($bain as $hud => $a) {
		if (isset($a['ip']))
			$ip =  $a['ip'];
		
		if (isset($a['dsms']))
			$o =  $a['dsms'];
		
		if (!isset($ip) || !isset($o)) continue;
		
		$ht .= '<tr>';
		$ht .= '<td class="n50">';
		if ($o >= 0) $ht .= '&nbsp;';
		$ht .= sprintf('%0.4f', $o);
		$ht .= '</td>';
		$ht .= '<td class="n50">';
		$ht .= $ip;
		$ht .= '</td>';
		$ht .= '</tr>';
		
		unset($o);
		continue;
	}
	
	return $ht;
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
		if (isset($o)) $ht .= sprintf('%0.6f', $o);
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

