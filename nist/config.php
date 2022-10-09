<?php

interface callSNTPConfig {
	const toleranceNS = 1 * M_BILLION;
	const dbname = 'sntp4';
	const collname = 'calls';
	const NISTminS  = 4;
	const cronIntervalM = 4; // does not have to stay in sync with cron, but probably best
	const cronIntervalS = self::cronIntervalM * 60;
	const nista = [         "129.6.15.26",         "129.6.15.27", "129.6.15.28", "129.6.15.29", "129.6.15.30", 
					"2610:20:6f15:15::26", "2610:20:6f15:15::27" ];
	
}

