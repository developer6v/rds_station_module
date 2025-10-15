<?php
include_once('../../../../../init.php');
require_once __DIR__ . "/../Services/rds_station/authentication/refresh_token.php";

use WHMCS\Database\Capsule;

echo refreshToken();