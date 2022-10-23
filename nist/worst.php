<?php

require_once('/opt/kwynn/kwutils.php');

class sntpWorstQCl {

	const port = 3000;
	const addr = '127.0.0.1';
	const url   = 'http://' . self::addr;
	const nfcl = '/opt/node/server.js';
	const cmd  = 'nodejs ' . self::nfcl;
	
	private function __construct(bool $doTest = false) {
		$this->runActual();
		if ($doTest) self::get();
	}
	
	private function runActual() {
		if (!file_exists(self::nfcl)) return;
		set_error_handler([$this, 'null_eh']);
		$h = socket_create(AF_INET, SOCK_STREAM,  SOL_TCP);
		$res = socket_bind($h, self::addr, self::port);
		socket_close($h);
		if ($res) {
			kwnohup(self::cmd);
			$this->readUntilStart();
		}
		restore_error_handler();		
	}
	
	private function readUntilStart() {
		for ($i=0; $i < 20; $i++) {
			$r = self::get(true);
			if (!$r) usleep(100000);
			else break;
		}
		
		if (iscli()) echo('read sleep = '. $i ."\n");
		
		return;
	}
	
	public static function get(bool $internal = false) {
		if (!file_exists(self::nfcl)) return [];
		if (!$internal) new self();
		$r = [];
		set_error_handler('kwynn');
		$r = json_decode(file_get_contents(self::url . ':' . self::port), true);	
		restore_error_handler();
		if (isset($r) && $r) return $r; // I do need to do the condition - comment out nohup and then this is necessary
		return [];
	}
	
	public function null_eh() { }

}

if (didCLICallMe(__FILE__)) sntpWorstQCl::get();

