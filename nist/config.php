<?php

interface callSNTPConfig {
	const toleranceNS = 1 * M_BILLION;
	const dbname = 'sntp4';
	const collname = 'calls';
	const NISTminS  = 4;
	const cronIntervalM = 4; // does not have to stay in sync with cron, but probably best
	const cronIntervalS = self::cronIntervalM * 60;
}

