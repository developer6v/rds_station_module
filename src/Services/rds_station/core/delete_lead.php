<?php

use WHMCS\Database\Capsule;

function rd_delete_contact_by_email(string $email) {
    $cfg = Capsule::table('sr_rds_station_config')->where('id', 1)->first();
    if (!$cfg) return ['code'=>0,'body'=>'CFG_MISSING'];
    $token = (string) ($cfg->access_token ?? '');

    $url = 'https://api.rd.services/platform/contacts/email:' . rawurlencode($email);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'accept: application/json',
            'authorization: Bearer ' . $token
        ],
        CURLOPT_TIMEOUT => 20
    ]);
    $res = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code'=>$code,'body'=>$res ?: ''];
}

?>
