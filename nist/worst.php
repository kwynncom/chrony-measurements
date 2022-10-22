<?php

require_once('/opt/kwynn/kwutils.php');

class sntpWorstQCl {

	const port = 3000;
	const addr = '127.0.0.1';
	const url   = 'http://' . self::addr;
	const cmd  = 'nodejs /opt/node/server.js';
	
	private function __construct(bool $doTest = false) {
		$this->runActual();
		if ($doTest) self::get();
	}
	
	private function runActual() {
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
		if (!$internal) new self();
		$r = json_decode(file_get_contents(self::url . ':' . self::port), true);	
		return $r;
	}
	
	public function null_eh() { }

}

if (didCLICallMe(__FILE__)) sntpWorstQCl::get();

