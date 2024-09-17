<?php

require_once('/opt/kwynn/kwutils.php');

interface callSNTPConfig {
	
	const testCleanTime = '2022-10-16 23:08';
	
	const NISTjfile = __DIR__ . '/NISTServers.json';
	
	const toleranceNS = 1 * M_BILLION;
	const dbname = 'sntp4';
	const collname = 'calls';
	const NISTminS  = 4;
	const cronIntervalM = 3; // does not have to stay in sync with cron, but probably best
	const cronIntervalS = self::cronIntervalM * 60;
	
	const enterFromLogIfM = 20;
	const enterFromLogIfS = self::enterFromLogIfM * 60;
	
	// Kwynn 2023/04/30 - recenty chrony version resets permission of log directory, so create link and use it
	const chronyLogF = '/var/kwynn/chrony/measurements.log';

}

function isFlTSEq($a, $b) {
	static $cmp = 1 / M_BILLION / 10;
	$d = abs($a - $b);
	return  $d < $cmp;
}

function kwsntp_doTestClean() {
	return time() < strtotime(callSNTPConfig::testCleanTime);
}

function isNIST(string $ip) : bool { // Now this is actually "is it NOT the AWS server"?

	if ($ip !== '169.254.169.123') return true;
	else return false;

	
	if (false) {
	    static $a = [];
	    if (!$a) $a = json_decode(file_get_contents(callSNTPConfig::NISTjfile), true);
	    return in_array($ip, $a);
	}
	
}