<?php // This is calc'ing offsets where there isn't one.  Also logging chrony start.

require_once(__DIR__ . '/config.php');

class nist_summary extends dao_generic_3 {
	
	public static function get() {
		new self();
	}
	
	private function __construct() {
		parent::__construct(callSNTPConfig::dbname);
		$this->creTabs(['c' => callSNTPConfig::collname, 's' => 'start']);
		$this->do05();
		$this->do10();
	}
	
	private function do05() {
		static $key = 'active (running) since ';
		static $keyl = false;
		if ($keyl === false) $keyl = strlen($key);
				
		
		$t = shell_exec('systemctl status chrony | head -n 3');
		if (($sp = strpos($t, $key)) === false) return;
		$s = substr($t, $sp + $keyl, 27);
		$U = strtotimeRecent($s);
		$q = ['U' => $U];
		if ($this->scoll->findOne($q)) return;
		$dat = $q;
		$dat['r'] = date('r', $U);
		$dat['_id'] = dao_generic_3::get_oids(false, $U);
		$this->scoll->insertOne($dat, ['kwnoup' => true]);
		return;
	}
	
	private function do10() {
		$q = ['U' => ['$gte' => time() - DAY_S * 2], 'Uns4' => ['$exists' => true]];
		$q['offset'] = ['$exists' => false];
		$a = $this->ccoll->find($q);
		foreach($a as $r) {
			$off = sntpSanity::SNTPOffset($r['Uns4']) / M_BILLION;
			$this->ccoll->upsert(['_id' => $r['_id']], ['offset' => $off]);
		}
		return;
	}
	
}

if (didCLICallMe(__FILE__)) nist_summary::get();