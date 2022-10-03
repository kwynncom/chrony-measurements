<?php

require_once(__DIR__ . '/callSNTPReg.php');

$ws  = nist_backoff_calls::getWait();
rd('wait', $ws);

$maxws = callSNTPConfig::cronIntervalM * 60 - callSNTPConfig::NISTminS - 1;

if ($ws > $maxws) exit(0);

$wfus  = random_int(roint($ws * M_MILLION), $maxws * M_MILLION);
rd('sleeping for x s = ', $wfus / M_MILLION);
usleep($wfus);

nist_backoff_calls::get();

function rd($a = '', $b = '') {
	$r = date('r');
	file_put_contents('/tmp/kwsncronr.txt', "$r : $a $b \n", FILE_APPEND);	
}