<?php

function getChronyParserPath() {
	if (!isAWS()) return __DIR__ . '/../../nss/chronyParsed.php';
}

