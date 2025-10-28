<?php

$init = realpath(__DIR__ . '/../../../../../init.php');

require_once __DIR__ . "/../Services/wprocketelementor/sendtoscript.php";

send_to_script_wprocketelementor("te");