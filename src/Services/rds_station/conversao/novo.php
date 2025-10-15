<?php

use WHMCS\Database\Capsule;

function rd_send_conversion(
    string $conversion_identifier,
    string $email,
    ?string $name = null,
    ?string $phone = null,
    $tags = null,
    ?string $traffic_source = null,
    ?string $utm_source = null,
    ?string $utm_medium = null,
    ?string $utm_campaign = null,
    ?string $utm_term = null,
    ?string $utm_content = null,
    ?string $city = null,
    ?string $state = null,
    ?string $country = null,
    ?string $company = null,
    ?string $job_title = null
) {
    $cfg = Capsule::table('sr_rds_station_config')->where('id', 1)->first();
    if (!$cfg) return ['code'=>0,'body'=>'CFG_MISSING'];
    $token = (string) ($cfg->access_token ?? '');

    if (is_string($tags)) {
        $tags = array_values(array_filter(array_map('trim', explode(',', $tags)), fn($v)=>$v!==''));
    } elseif (!is_array($tags)) {
        $tags = [];
    }

    $payload = array_filter([
        'conversion_identifier' => "API_Cliente_Novo",
        'email' => $email,
        'name' => $name,
        'phone' => $phone,
        'traffic_source' => $traffic_source,
        'utm_source' => $utm_source,
        'utm_medium' => $utm_medium,
        'utm_campaign' => $utm_campaign,
        'utm_term' => $utm_term,
        'utm_content' => $utm_content,
        'city' => $city,
        'state' => $state,
        'country' => $country,
        'company' => $company,
        'job_title' => $job_title
    ], fn($v) => $v !== null && $v !== '');

    if (!empty($tags)) $payload['tags'] = array_values($tags);

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
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code'=>$code,'body'=>$res ?: ''];
}
