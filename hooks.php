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

        // Opcional: log no activity log do WHMCS
    try {
        $code = is_array($resp) && isset($resp['code']) ? $resp['code'] : null;
        $body = is_array($resp) && isset($resp['body']) ? $resp['body'] : (string)$resp;
        logActivity('RD CONVERSION AfterShoppingCartCheckout: order '.$orderId.' | code='.$code.' | body='.substr((string)$body,0,500));
    } catch (\Throwable $e) {
        logActivity('erro log activity');
    }


});


add_hook('ClientClose', 1, function(array $vars) {
    $uid = (int) ($vars['userid'] ?? 0);
    if ($uid <= 0) return;
    $client = Capsule::table('tblclients')->where('id', $uid)->first();
    if (!$client || empty($client->email)) return;
    rd_send_api_cliente_cancelado((string) $client->email);
});

add_hook('ClientDelete', 1, function(array $vars) {
    $uid = (int) ($vars['userid'] ?? 0);
    if ($uid <= 0) return;
    $client = Capsule::table('tblclients')->where('id', $uid)->first();
    if (!$client || empty($client->email)) return;
    rd_send_api_cliente_cancelado((string) $client->email);
});