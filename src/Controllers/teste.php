<?php
include_once('../../../../../init.php');
require_once __DIR__ . "/../Services/rds_station/conversao/novo.php";

use WHMCS\Database\Capsule;


$r1 = rd_send_conversion(
    'Lead Teste',
    'ana.teste@example.com',
    'Ana Teste',
    '1133334444',
    null,
    'tag1,tag2',
    'google',
    'google',
    'cpc',
    'campanha_teste',
    'termo_teste',
    'criativo_teste',
    'Varginha',
    'MG',
    'BR',
    'Sourei Digital',
    'Product Owner',
    'https://sourei.com.br'
);

echo "rd_send_conversion:\n";
var_dump($r1);

echo "\n\nrd_send_api_cliente_cancelado:\n";
$r2 = rd_send_api_cliente_cancelado('cancelado@example.com');
var_dump($r2);
