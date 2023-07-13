<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/' . 'config.php');

class setNISTarray implements callSNTPConfig {
	
	const tfile = '/tmp/nistsvrskw.html';
	
	const nurl = 'https://tf.nist.gov/tf-cgi/servers.cgi';
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		if  (is_readable(self::tfile) && ($t = file_get_contents(self::tfile))) ;
		else {
			$t = file_get_contents(self::nurl);
			file_put_contents(self::tfile, $t);
		}
		$l = strlen($t);
		$a = ips_kw::getAllIPs($t);
		file_put_contents(self::NISTjfile, json_encode($a, JSON_PRETTY_PRINT));
		
	}
	
}

if (didCLICallMe(__FILE__)) new setNISTarray();