<?php

require_once(__DIR__ . '/callSNTPReg.php');

class NISTCronCl {
	public function __construct() {
		cliOrDie();
		self::liveOrDie();
		// $this->launchLL();
		$this->doit();
	}
	
	private function launchLL() {
		// kwnohup('php ' . __DIR__ . '/../logscl.php -d');
	}
	
	private static function liveOrDie() {
		if (1 && ispkwd() && time() > strtotime('2026-04-10 01:15')) {
			self::rd("End of test, exiting\n");
			exit(0);
		}		
	}
	
	private static function rd($a = '', $b = '', $c = '') {
		$r = date('r');
		$s = "$r : $a $b $c \n";
		file_put_contents('/tmp/kwsncronr.txt', $s, FILE_APPEND);	
		echo($s);
	}
	
	private function doit() {
		
		$rf = ['NISTCronCl', 'rd'];
		
		$ws  = nist_backoff_calls::getWait();

		if (1) {
			$rf('wait', $ws);

			$maxws = callSNTPConfig::cronIntervalS;

			if ($ws > $maxws) exit(0);

			$wmin = max($ws, 0);

			$wfus  = random_int(roint($wmin * M_MILLION), $maxws * M_MILLION);

			if (ispkwd()) {
			    $rf('reducing sleep time for kwd');
			    $wfus /= 15;
			}

			$rf('min / max (S) = ', $wmin, $maxws);
			$rf('sleeping for x s = ', $wfus / M_MILLION);
			if ($wfus > 0) usleep(roint($wfus));
		}

		$dat = nist_backoff_calls::get(1, 'cron');
		$rf(print_r($dat, true));		
	}
	
}

if (didCLICallMe(__FILE__)) new NISTCronCl();
