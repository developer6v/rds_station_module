<?php
// === LIGAR DEBUG O MAIS CEDO POSSÍVEL ===
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
ob_implicit_flush(true);

// Captura fatal errors também
register_shutdown_function(function(){
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR])) {
        file_put_contents('/tmp/rd_debug.log', "[FATAL] {$e['message']} @ {$e['file']}:{$e['line']}\n", FILE_APPEND);
        header('Content-Type: text/plain; charset=utf-8');
        echo "\n[FATAL] {$e['message']} @ {$e['file']}:{$e['line']}\nVeja /tmp/rd_debug.log";
    }
});

// Helper de log
function rd_dbg($label, $data = null){
    $line = '['.date('c')."] {$label}";
    if ($data !== null) {
        $line .= ' => ' . (is_string($data) ? $data : var_export($data, true));
    }
    file_put_contents('/tmp/rd_debug.log', $line . "\n", FILE_APPEND);
    // Se você quiser logar no WHMCS também, descomente:
    // if (function_exists('logActivity')) { logActivity(substr($line, 0, 2000)); }
}

header('Content-Type: text/plain; charset=utf-8');

rd_dbg('BEGIN debug_cancelado.php');

// 1) init WHMCS
$init = realpath(__DIR__ . '/../../../../../init.php');
rd_dbg('init path', $init);
if (!$init || !file_exists($init)) { echo "init.php NÃO encontrado em {$init}\n"; exit; }
require_once $init;

// 2) include do cancelado.php
$cancelado = realpath(__DIR__ . '/../Services/rds_station/conversao/novo.php');
rd_dbg('cancelado path', $cancelado);
if (!$cancelado || !file_exists($cancelado)) { echo "cancelado.php NÃO encontrado em {$cancelado}\n"; exit; }
require_once $cancelado;

// 3) sanity check de função
$fn = 'rd_send_api_cliente_cancelado';
if (!function_exists($fn)) {
    echo "Função {$fn} NÃO encontrada após include de cancelado.php\n";
    rd_dbg('function_missing', $fn);
    exit;
}
rd_dbg('function_exists', $fn);

// 4) chamada protegida
try {
    $emailTeste = 'teste@example.com';
    rd_dbg('calling', "{$fn}({$emailTeste})");
    $ret = $fn($emailTeste);

    echo "=== RETORNO BRUTO ===\n";
    var_export($ret);
    echo "\n\n=== TIPO ===\n";
    echo gettype($ret) . "\n";

    rd_dbg('return', $ret);
} catch (Throwable $t) {
    rd_dbg('EXCEPTION', $t->getMessage());
    echo "EXCEPTION: {$t->getMessage()}\n";
}

echo "\n\nVeja também o log: /tmp/rd_debug.log\n";
