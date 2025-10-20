<?php

use WHMCS\Database\Capsule;
require_once __DIR__ . "/../authentication/refresh_token.php";

function rd_send_api_cliente_cancelado(string $email) {
    $cfg = Capsule::table('sr_rds_station_config')->where('id', 1)->first();
    if (!$cfg) return false;
    $token = (string) ($cfg->access_token ?? '');

    $body = json_encode([
        'event_type' => 'CONVERSION',
        'event_family' => 'CDP',
        'payload' => [
            'conversion_identifier' => 'API_Cliente_Cancelado',
            'email' => $email
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $do = function($t) use ($body) {
        $ch = curl_init('https://api.rd.services/platform/events?event_type=conversion');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'content-type: application/json',
                'authorization: Bearer ' . $t
            ],
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_TIMEOUT => 20
        ]);
        $res = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [$code, $res];
    };

    [$code, $res] = $do($token);
    if ($code === 401) {
        if (function_exists('refresh_token')) refresh_token();
        $cfg = Capsule::table('sr_rds_station_config')->where('id', 1)->first();
        $token = (string) ($cfg->access_token ?? '');
        [$code, $res] = $do($token);
    }

    return $code === 200 ? $res : false;
}

?>
