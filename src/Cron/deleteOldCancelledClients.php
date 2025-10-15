<?php
include_once('../../../../../init.php');

use WHMCS\Database\Capsule;

require_once __DIR__ . '/../Services/rds_station/core/delete_lead.php';

header('Content-Type: text/plain; charset=utf-8');

$limitDate = date('Y-m-d H:i:s', strtotime('-90 days'));

$leads = Capsule::table('sr_rds_leads')
    ->where('conversion_type', 'API_Cliente_Cancelado')
    ->whereNotNull('created_at')
    ->where('created_at', '<', $limitDate)
    ->pluck('email');

if (empty($leads)) {
    echo "Nenhum lead elegível para exclusão.\n";
    exit;
}

foreach ($leads as $email) {
    $res = rd_delete_contact_by_email((string)$email);
    $code = is_array($res) && isset($res['code']) ? $res['code'] : null;
    echo $email . " => HTTP " . ($code ?? '0') . "\n";
}
