<?php

require_once __DIR__ . "/src/Services/index.php";
use WHMCS\Database\Capsule;




add_hook('AfterShoppingCartCheckout', 1, function(array $vars) {

    $orderId = (int) ($vars['OrderID'] ?? 0);
    if ($orderId <= 0) {
        return;
    }

    // Pedido -> cliente
    $order = Capsule::table('tblorders')->where('id', $orderId)->first();
    if (!$order) {
        return;
    }

    $client = Capsule::table('tblclients')->where('id', $order->userid)->first();
    if (!$client) {
        return;
    }

    $email = (string) ($client->email ?? '');
    if ($email === '') {
        return;
    }

    $conversionIdentifier = 'API_Cliente_Novo'; 
    $name  = trim(($client->firstname ?? '') . ' ' . ($client->lastname ?? ''));
    $phone = (string) ($client->phonenumber ?? '');

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
        (string) ($client->city ?? null),
        (string) ($client->state ?? null),
        (string) ($client->country ?? null),
        (string) ($client->companyname ?? null),
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
    sr_rds_insert_lead($email, 'API_Cliente_Cancelado');
});

add_hook('ClientDelete', 1, function(array $vars) {
    $uid = (int) ($vars['userid'] ?? 0);
    if ($uid <= 0) return;
    $client = Capsule::table('tblclients')->where('id', $uid)->first();
    if (!$client || empty($client->email)) return;
    rd_send_api_cliente_cancelado((string) $client->email);
    sr_rds_insert_lead($email, 'API_Cliente_Cancelado');
});