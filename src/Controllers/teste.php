<?php
include_once('../../../../../init.php');

use WHMCS\Database\Capsule;

function rd_send_conversion_min(
    string $conversion_identifier,
    string $email,
    ?string $name = null,
    ?string $phone = null,
    array $tags = [],
    ?string $utm_source = null,
    ?string $utm_medium = null,
    ?string $utm_campaign = null,
    ?string $utm_term = null,
    ?string $utm_content = null,
    ?string $traffic_source = null
) {
    $cfg = Capsule::table('sr_rds_station_config')->where('id', 1)->first();
    if (!$cfg) return "CFG_MISSING";

    $token = (string)($cfg->access_token ?? '');
    $payload = [
        'conversion_identifier' => $conversion_identifier,
        'email' => $email
    ];
    if ($name) $payload['name'] = $name;
    if ($phone) $payload['phone'] = $phone;
    if (!empty($tags)) $payload['tags'] = array_values($tags);
    if ($traffic_source) $payload['traffic_source'] = $traffic_source;
    if ($utm_source) $payload['utm_source'] = $utm_source;
    if ($utm_medium) $payload['utm_medium'] = $utm_medium;
    if ($utm_campaign) $payload['utm_campaign'] = $utm_campaign;
    if ($utm_term) $payload['utm_term'] = $utm_term;
    if ($utm_content) $payload['utm_content'] = $utm_content;

    $body = json_encode([
        'event_type' => 'CONVERSION',
        'event_family' => 'CDP',
        'payload' => $payload
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $ch = curl_init('https://api.rd.services/platform/events?event_type=conversion');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'accept: application/json',
            'content-type: application/json',
            'authorization: Bearer ' . $token
        ],
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_TIMEOUT => 20
    ]);
    $res = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    return "HTTP $code\n" . ($err ? "cURL: $err\n" : "") . ($res ?: "");
}

header('Content-Type: text/plain; charset=utf-8');

echo "TEST CONVERSION (mínimo e campos válidos):\n";
echo rd_send_conversion_min(
    'Conversao_Teste_Simples',
    'ana.teste@example.com',
    'Ana Teste',
    '11999999999',
    ['tag1','tag2'],
    'google',
    'cpc',
    'campanha_teste',
    'termo_teste',
    'criativo_teste',
    'google'
);
