<?php

require_once(__DIR__ . '/config.php');

class nist_insert extends dao_generic_3 {
	
	private function __construct() {
		parent::__construct  (callSNTPConfig::dbname   );
		$this->creTab(['c' => callSNTPConfig::collname]);
	}
	
	private function pre() {
		
	}
	
}
