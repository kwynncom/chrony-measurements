<?php

require_once(__DIR__ . '/callSNTPReg.php');

if (iscli()) {

	if (ispkwd() && time() > strtotime('2022-10-09 22:20')) {
		echo("End of test, exiting\n");
		exit(0);
	}
	
	$ws  = nist_backoff_calls::getWait();

	if (1) {
		rd('wait', $ws);

		$maxws = callSNTPConfig::cronIntervalS;

		if ($ws > $maxws) exit(0);
		
		$wmin = max($ws, 0);

		$wfus  = random_int(roint($wmin * M_MILLION), $maxws * M_MILLION);
		rd('min / max (S) = ', $wmin, $maxws);
		rd('sleeping for x s = ', $wfus / M_MILLION);
		if ($wfus > 0) usleep($wfus);
	}

	$dat = nist_backoff_calls::get();
	rd(print_r($dat, true));
}

function rd($a = '', $b = '', $c = '') {
	$r = date('r');
	file_put_contents('/tmp/kwsncronr.txt', "$r : $a $b $c \n", FILE_APPEND);	
}
