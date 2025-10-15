<?php
include_once('../../../../../init.php');
require_once __DIR__ . "/../Services/authentication/refresh_token.php";

use WHMCS\Database\Capsule;

echo refresh_token();