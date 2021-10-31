<?php

require_once('/opt/kwynn/kwutils.php');

function validIPOrDie($ip) {
    static $ipv6re    = '/^([0-9A-Fa-f:]+){2,39}$/';
    static $ipv4re    = '/^((\d+){1,3}\.){3}(\d+){1,3}$/';
	
	$sl = strlen($ip);
	kwas($ip && is_string($ip) && $sl >= 7 && $sl <= 39, 'need an IP arg - 2');
	
	
	kwas(	 ($ip4m = preg_match($ipv4re, $ip))
		  || ($ip6m = preg_match($ipv6re, $ip))
			, 'bad IP preg'	);
	
	if ($ip4m) {
		kwas($sl <= 15, 'ipv4 too big');
		$ip4 = ip2long($ip);
		kwas($ip4 && $ip4 > 0, 'ipv4 failed'); 
	}
	
	kwas(inet_pton($ip), 'inet_pton failed'); 
	
	return $ip;
}

function validIPTest() {
	$a = [
		'127.0.0.1',
		'1.2.3.4',
		'2600:8800:7a8f:cb00:e983:93da:eec0:69fa',
		'123.123.123.123'
	];
	
	foreach($a as $ip) validIPOrDie($ip);
	return;
}

if (didCLICallME(__FILE__)) validIPTest();