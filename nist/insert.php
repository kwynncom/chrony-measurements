<?php

require_once(__DIR__ . '/config.php');

class nist_insert extends dao_generic_3 {
	
	public function __construct(string $via = '') { // manual call to callSNTPReg.php
		parent::__construct  (callSNTPConfig::dbname   );
		$this->creTabs(['c' => callSNTPConfig::collname]);
		$this->dbmg();
		$this->setPI($via);
	}
	
	private function dbmg() {
		if (time() < strtotime('2022-10-12 01:30')) $this->ccoll->drop();
		$this->ccoll->createIndex(['U' => -1]);
		$this->ccoll->createIndex(['Uus' => -1]);
	}
	
	private function setPI(string $viain = '') {
		
		$this->via = 'unk';
		
		if ($viain) {
			$this->via = $viain; 
			switch($viain) { case 'hand' : case 'www' : return;		}
		}
		
		if (PHP_SAPI !== 'cli') { $this->via = 'www'; return; }
		$pid = posix_getpid();
		if ($viain === 'log') { $this->pid = $pid; return; }
		$pt = substr(trim(shell_exec("pstree -s $pid")), 0, 200);
		if (strpos($pt, '---cron---') !== false) { $this->via = 'cron'; return; }

	}
	
	public static function flpp(float $f) { return ($f >= 0 ? '+' : '') . sprintf('%0.6f', $f);	}
	public static function ippp(string $ip) { return substr($ip, strlen($ip) - 3); }
	
	public function fromLog(array $datin) {
		
		extract($datin); unset($datin);
		$via = 'log';
		$r = date('r', $Uactual);

		$_id  = date('md-Hi-s-Y', $Uactual) . '-' . substr($via, 0, 3) . self::ippp($ip);
		$_id .= '-line-' . $lnn;
		$_id .= self::flpp($offset);
		
		$pid = $this->pid;
		
		$dat = get_defined_vars();
		unset($dat['cli'], $dat['cmp']);
		$this->ccoll->insertOne($dat, ['kwnoup' => true]);
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
		$this->ccoll->upsert(['_id' => $_id], $nistr, null, false);
	}
	

	
}
