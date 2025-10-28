<?php

// Garante que o WHMCS esteja inicializado
$init = realpath(__DIR__ . '/../../../../../init.php');
if ($init && file_exists($init)) {
    require_once $init;
} else {
    die("init.php não encontrado.\n");
}

// Inclui sua função personalizada
require_once __DIR__ . "/../Services/wprocketelementor/sendtoscript.php";

// Teste: use um ID de cliente real (ex: 1)
$testUserId = 1;

$result = send_to_script_wprocketelementor($testUserId);

echo "Resposta:\n";
var_dump($result);
