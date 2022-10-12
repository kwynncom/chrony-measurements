<?php

require_once(__DIR__ . '/config.php');

class nist_insert extends dao_generic_3 {
	
	public function __construct() {
		parent::__construct  (callSNTPConfig::dbname   );
		$this->creTabs(['c' => callSNTPConfig::collname]);
		$this->setPI();
	}
	
	private function setPI() {
		if (PHP_SAPI !== 'cli') { $this->via = 'www'; return; }
		$pid = posix_getpid();
		$pt = substr(trim(shell_exec("pstree -s $pid")), 0, 200);
		if (strpos($pt, '---cron---') !== false) { $this->via = 'cron'; return; }
		$this->pid = $pid;
		$this->via = 'log';
	}
	
	public function preCall() {
		$a = [];
		$us = $a['Uus'] = microtime(1);
		$U	= $a['U'  ] = intval(floor($us));
		$a['r'] = date('r', $U);
		$via = $a['via'] = $this->via;
		if (isset(		$this->pid)) 
			$a['pid'] = $this->pid;
		
		$a['_id']  = date('md-Hi-s-Y', $U) . '-' . substr($via, 0, 3) . '-' . dao_generic_3::get_oids(false, null, null, true);

		$this->ccoll->insertOne($a, ['kwnoup' => true]);		
		
		return $a['_id'];
	}
	
	public function postCall(string $_id, array $nistr) {
		$this->ccoll->upsert(['_id' => $_id], $nistra);
	}
	

	
}
