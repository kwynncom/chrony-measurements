<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/callSNTPReg.php');
require_once(__DIR__ . '/../logscl.php');
require_once(__DIR__ . '/insert.php');

class nistLogToDBCl extends dao_generic_3 implements callSNTPConfig {
	
	public function __construct(bool $islive = false) {
		parent::__construct(self::dbname);
		$this->creTabs(['c' => self::collname]);
		$this->ino = new nist_insert('log');
		$this->indexMgmt();
		$this->clean();
		$this->lineCalcs();
		$this->do10();
		if ($islive) $this->lineCalcs();
	}
	
	public function getEndCrit() { return $this->endCrit; }
	
	private function lineCalcs() {
		$this->endCrit = [];
		$this->lnnprev = 0;
		$r = $this->ccoll->findOne([], ['sort' => ['lnn' => -1]]);
		if (!isset($r['lnn'])) return;
		$lnn = $r['lnn'];
		$this->lnnprev = $lnn;
		foreach(['Uactual', 'ip', 'offset', 'r'] as $f) $this->endCrit[$f] = $r[$f];
		return;
	}
	
	private function clean() {
		if (time() < strtotime('2022-10-11 01:00')) $this->ccoll->drop();
	}
		
	private function do10() {
		$o = new chronylog_cli_filter(true, $this->endCrit);
		$a = $o->get();
		$n = count($a);
		for ($i = 0; $i < $n; $i++) $this->procIP($a[$i]);
		return;
	}
	
	private function indexMgmt() {
		try { $this->ccoll->dropIndex('U_-1'); } catch(Exception $ex) {}
		$name = $this->ccoll->createIndex(['U' => -1]);
		return;
	}
	
	public static function isNIST($ip) {
		return in_array($ip, self::nista);
	}

	public function procIP(array $a) {
		static $exn = 0;
		
		$a['lnn'] += $this->lnnprev;
		try {
			unset($a['hu']);
			$this->ino->fromLog($a);
		} catch (Exception $ex) {
			if (++$exn > 4) { 
				echo('chrony logcl.php exception as follows.  Exiting: ' . $ex->getMessage());
				exit(2122);
			}
		}
		
		return true;
	}

}

if (didCLICallMe(__FILE__)) new nistLogToDBCl();
