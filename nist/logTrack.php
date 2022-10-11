<?php

	/* private */ function procIP(string $hu, string $ip, string $restl) {
		static $o = false;
		static $exn = 0;
		
		if (!$o) $o = new nistLogToDBCl();
		
		if (!nistLogToDBCl::isNIST($ip)) return;
		
		$offs = substr($restl, 12, 10);
		$offfl = floatval($offs);
		try {
			$o->put($hu, $ip, $offfl);
		} catch (Exception $ex) {
			if (++$exn > 4) { 
				echo('chrony logcl.php exception as follows.  Exiting: ' . $ex->getMessage());
				exit(2122);
			}
			
		}
	}

	// 		$this->procIP($hu, $ip, $restl);