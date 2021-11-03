<?php

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
		$ht .= '<td>';
		$ts  = strtotime($hud);
		$ht .= chrony_readouts_formatting::minf($now - $ts); unset($ts);
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

