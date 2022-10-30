<?php // this both does the "worst" fetch and has the code to log chrony starts, but I'm not using those now.
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/worst.php');

class nist_summary extends dao_generic_3 {
	
	public static function get() {
		$o = new self();
		$ret = [];
		$ret['chstarts'] = $o->getCSI();
		$ret['worst'] = sntpWorstQCl::get();
		return $ret;
	}
	
	private function __construct() {
		parent::__construct(callSNTPConfig::dbname);
		$this->creTabs(['c' => callSNTPConfig::collname, 's' => 'start']);
		$this->do05();
	}
	
	public function getCSI() {
		return $this->scoll->find(['U' => ['$gte' => time() - 200000]], ['sort' => ['U' => -1]]);
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
}

if (didCLICallMe(__FILE__)) nist_summary::get();