<?php
include_once('../../../../../init.php');
require_once __DIR__ . "/../Services/rds_station/conversao/cancelado.php";

header('Content-Type: text/plain; charset=utf-8');

echo (rd_send_api_cliente_cancelado("cancelado@example.com"));