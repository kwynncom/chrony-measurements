<?php

require_once('/opt/kwynn/kwutils.php');

class nodeStuff {

	const port = 3000;
	const addr = '127.0.0.1';
	const url   = 'http://' . self::addr;
	const cmd  = 'nodejs /opt/node/server.js';
	
	function __construct(bool $doTest = false) {
		$this->runActual();
		if ($doTest) $this->test();
	}
	
	private function runActual() {
		set_error_handler([$this, 'null_eh']);
		$h = socket_create(AF_INET, SOCK_STREAM,  SOL_TCP);
		$res = socket_bind($h, self::addr, self::port);
		socket_close($h);
		if ($res) kwnohup(self::cmd);
		restore_error_handler();		
	}
	
	private function read() {
		$t = file_get_contents(self::url . ':' . self::port);
		if (0) { $h = curl_init(self::url);
		curl_setopt($h, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($h, CURLOPT_FOLLOWLOCATION, true);
		$t = curl_exec($h);
		}
		
		return json_decode($t, true);
	}
	
	public function null_eh() { }
	
	private function test() {
		$a = $this->read();
		return;
	}

}

if (didCLICallMe(__FILE__)) new nodeStuff(true);

