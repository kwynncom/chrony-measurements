<?php

require_once('back/main.php');
require_once('front/format.php');

function getTemplateINITGV() { return chrony_readouts_formatting::get(chrony_analysis::get()); }
function sendJSON() {
	if (!isset($_REQUEST['json'])) return;
}

sendJSON();