<?php

require_once('/opt/kwynn/kwutils.php');

function validIPTest() {
	$a = [
		'127.0.0.1',
		'1.2.3.4',
		'2600:8800:7a8f:cb00:e983:93da:eec0:69fa',
		'123.123.123.123', 
		'::0',
		'255.255.255.255'
	];
	
	foreach($a as $ip) {
		validIPOrDie($ip);
		echo($ip . ' OK' . "\n");
	}
	return;
}

if (didCLICallME(__FILE__)) validIPTest();
