<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/callSNTPReg.php');
require_once(__DIR__ . '/../logscl.php');

class nistLogToDBCl extends dao_generic_3 implements callSNTPConfig {
	
	public function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['c' => self::collname]);
		$this->indexMgmt();
		$this->clean();
		$this->lineCalcs();
		$this->do10();

	}
	
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
		if (time() < strtotime('2022-10-11 01:30')) $this->ccoll->drop();
	}
		
	private function do10() {
		$o = new chronylog_cli_filter(true, $this->endCrit);
		$a = $o->get();
		$n = count($a);
		for ($i = 0; $i < $n; $i++) {
			if (!$this->procIP($a[$i])) break;
		}
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

	private function procIP(array $a) {
		static $exn = 0;
		
		$a['lnn'] += $this->lnnprev;
		try {
			unset($a['hu']);
			return nist_backoff_calls::fromLog($this->ccoll, $a);
		} catch (Exception $ex) {
			if (++$exn > 4) { 
				echo('chrony logcl.php exception as follows.  Exiting: ' . $ex->getMessage());
				exit(2122);
			}
		}
		
		return true;
	}
	
	public static function getLLI(string $l, bool $exv = false) {
		
		$ipa = 15; // length of 255.255.255.255 ; I unset it below, so don't use static
		
		if (!$l || !isset($l[76])) return; // I want at least 76 chars
		$hu = trim(substr($l, 0, 20));
		$Uactual = strtotime($hu . ' UTC');
		$U = $Uus = $Uactual + 1;
		$ipb = substr($l, 20);
		preg_match('/\S+/', $ipb, $ms); unset($ipb);
		$ip = $ms[0]; unset($ms);

		$ipl = strlen($ip);
		$r20 = substr($l, 35 + ($ipl <= $ipa ? 0 : $ipl - $ipa)); unset($ipl, $ipa, $l);
		$restl = substr($r20, 19, 66);
		$valc = $r20[16]; unset($r20);
		$offsets = substr($restl, 12, 10); 
		$offset = floatval($offsets); unset($offsets);
		
		if (!$exv) unset($valc, $restl); unset($exv);
	
		$vars = get_defined_vars();
		
		return $vars;

	}
	
}

if (didCLICallMe(__FILE__)) new nistLogToDBCl();
