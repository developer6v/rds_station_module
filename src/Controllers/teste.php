<?php
include_once('../../../../../init.php');
require_once __DIR__ . "/../Services/rds_station/conversao/novo.php";

header('Content-Type: text/plain; charset=utf-8');

$r = rd_send_conversion(
    'Conversao_Teste_Simples',
    'ana.teste@example.com',
    'Ana Teste',
    '11999999999',
    ['tag1','tag2'],
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
    'PO'
);

if (is_array($r) && isset($r['code'])) {
    echo "HTTP {$r['code']}\n{$r['body']}\n";
} else {
    var_dump($r);
}
