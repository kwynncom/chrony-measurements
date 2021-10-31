<?php

require_once('back/main.php');
require_once('front/format.php');

$KW_G_TIMEA = chrony_readouts_formatting::get(chrony_analysis::get());
require_once('front/template.php');
