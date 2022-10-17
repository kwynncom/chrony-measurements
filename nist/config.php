<?php

require_once('/opt/kwynn/kwutils.php');

interface callSNTPConfig {
	
	const testCleanTime = '2022-10-16 22:40';
	
	const toleranceNS = 1 * M_BILLION;
	const dbname = 'sntp4';
	const collname = 'calls';
	const NISTminS  = 4;
	const cronIntervalM = 4; // does not have to stay in sync with cron, but probably best
	const cronIntervalS = self::cronIntervalM * 60;
	
	const enterFromLogIfM = 20;
	const enterFromLogIfS = self::enterFromLogIfM * 60;
	
	const NISTListA = [													  "129.6.15.26" => true,         "129.6.15.27" => true, "129.6.15.28" => true, 
					"129.6.15.29" => true, "129.6.15.30" => true, "2610:20:6f15:15::26" => true, "2610:20:6f15:15::27" => true];
	
	const chronyLogF = '/var/log/chrony/measurements.log';

}

function isFlTSEq($a, $b) {
	static $cmp = 1 / M_BILLION / 10;
	$d = abs($a - $b);
	return  $d < $cmp;
}

function kwsntp_doTestClean() {
	return time() < strtotime(callSNTPConfig::testCleanTime);
}