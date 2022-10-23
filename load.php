<?php

require_once('back/main.php');
require_once('front/format.php');
require_once('front/histTable.php');

function getTemplateINITGV() { return chrony_readouts_formatting::get(chrony_analysis::get()); }
function sendJSON() {
	if ((!isset($_REQUEST['json']))) return;
	
	$a = getTemplateINITGV();
	$a['logs']['htrf'] = kwChmHistRows  ($a['logs']['logs']);
	$a['nistallHT']    = kwChmNISTRows  ($a['nistall']); 
	$a['nistHTIP']	   = kwChmNISTRowsIP($a['nistall']); 
	
	header('Content-Type: application/json');
	echo(json_encode($a));
	exit(0);
}

sendJSON();