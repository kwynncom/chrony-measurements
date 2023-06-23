<?php

require_once('/opt/kwynn/kwutils.php');

interface callSNTPConfig {
	
	const testCleanTime = '2022-10-16 23:08';
	
	const toleranceNS = 1 * M_BILLION;
	const dbname = 'sntp4';
	const collname = 'calls';
	const NISTminS  = 4;
	const cronIntervalM = 3; // does not have to stay in sync with cron, but probably best
	const cronIntervalS = self::cronIntervalM * 60;
	
	const enterFromLogIfM = 20;
	const enterFromLogIfS = self::enterFromLogIfM * 60;
	
	const NISTListA = [													  "129.6.15.26" => true,         "129.6.15.27" => true, "129.6.15.28" => true, 
					"129.6.15.29" => true, "129.6.15.30" => true, "2610:20:6f15:15::26" => true, "2610:20:6f15:15::27" => true,
					"2610:20:6f97:97::4" => true, // Fort Collins, CO
					"2610:20:6f97:97::6" => true, // Fort C.
					"2610:20:6f96:96::4" => true, // Boulder, CO
					"2610:20:6f96:96::6" => true, // Boulder
		];
	
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
