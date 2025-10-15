<?php

require_once __DIR__ . "/src/Services/index.php";
use WHMCS\Database\Capsule;




add_hook('ClientAdd', 1, function(array $vars) {

    // Vars entregues pelo hook:
    $userId   = (int)($vars['userid'] ?? 0);
    $email    = (string)($vars['email'] ?? '');
    $name     = trim(($vars['firstname'] ?? '') . ' ' . ($vars['lastname'] ?? ''));
    $phone    = (string)($vars['phonenumber'] ?? '');
    $company  = (string)($vars['companyname'] ?? '');
    $city     = (string)($vars['city'] ?? '');
    $state    = (string)($vars['state'] ?? '');
    $country  = (string)($vars['country'] ?? '');

    if ($userId <= 0 || $email === '') {
        return;
    }


    $conversionIdentifier = 'API_Cliente_Novo';

    $resp = rd_send_conversion(
        $conversionIdentifier,
        $email,
        $name ?: null,
        $phone ?: null,
        ['whmcs'],
        'whmcs',
        null,           
        null,            
        null,        
        null,            
        null,         
        $city ?: null,
        $state ?: null,
        $country ?: null,
        $company ?: null,
        null       
    );

    sr_rds_insert_lead($email, 'API_Cliente_Novo');

});

add_hook('ClientClose', 1, function(array $vars) {
    $uid = (int) ($vars['userid'] ?? 0);
    if ($uid <= 0) return;
    $client = Capsule::table('tblclients')->where('id', $uid)->first();
    if (!$client || empty($client->email)) return;
    rd_send_api_cliente_cancelado((string) $client->email);
    sr_rds_insert_lead($client->email, 'API_Cliente_Cancelado');
});

add_hook('ClientDelete', 1, function(array $vars) {
    $uid = (int) ($vars['userid'] ?? 0);
    if ($uid <= 0) return;
    $client = Capsule::table('tblclients')->where('id', $uid)->first();
    if (!$client || empty($client->email)) return;
    rd_send_api_cliente_cancelado((string) $client->email);
    sr_rds_insert_lead($client->email, 'API_Cliente_Cancelado');
});