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



add_hook('ServiceEdit', 1, function(array $vars) {
        logActivity("service eddit chamado ");
    $serviceId = (int) ($vars['serviceid'] ?? 0);
    $userId    = (int) ($vars['userid']    ?? 0);
    if ($serviceId <= 0 || $userId <= 0) return;

    $service = Capsule::table('tblhosting')->where('id', $serviceId)->first();
    if (!$service) return;
        logActivity("service encontrado ");

    $status = (string) ($service->domainstatus ?? '');
    if (!in_array($status, ['Cancelled','Terminated'], true)) {
        return;
    }

    $client = Capsule::table('tblclients')->where('id', $userId)->first();
    if (!$client || empty($client->email)) return;

    $hasOtherServices = Capsule::table('tblhosting')
        ->where('userid', $userId)
        ->where('id', '!=', $serviceId)
        ->whereIn('domainstatus', ['Active','Suspended'])
        ->exists();

    $hasActiveAddons = Capsule::table('tblhostingaddons')
        ->join('tblhosting', 'tblhostingaddons.hostingid', '=', 'tblhosting.id')
        ->where('tblhosting.userid', $userId)
        ->where('tblhostingaddons.status', 'Active')
        ->exists();

    $hasActiveDomains = Capsule::table('tbldomains')
        ->where('userid', $userId)
        ->where('status', 'Active')
        ->exists();

    $hasAnythingActive = $hasOtherServices || $hasActiveAddons || $hasActiveDomains;
    if ($hasAnythingActive) return;
        logActivity("tem outros ativo ");

    send_to_script_wprocketelementor($userId);

    // Dedupe simples (se existir)
    if (function_exists('sr_rds_already_has_lead') &&
        sr_rds_already_has_lead($client->email, 'API_Cliente_Cancelado')) return;

    if (function_exists('rd_send_api_cliente_cancelado')) {
        rd_send_api_cliente_cancelado((string) $client->email);
    }
    if (function_exists('sr_rds_insert_lead')) {
        sr_rds_insert_lead($client->email, 'API_Cliente_Cancelado');
    }
});

add_hook('ServiceEdit', 1, function(array $vars) {
    logActivity('ServiceEdit: disparou. serviceid=' . ($vars['serviceid'] ?? ''));
});
add_hook('AfterModuleTerminate', 1, function(array $vars) {
    $params    = $vars['params'] ?? [];
    $serviceId = (int) ($params['serviceid'] ?? 0);
    $userId    = (int) ($params['userid']    ?? 0);
    if ($serviceId <= 0 || $userId <= 0) return;

    $service = Capsule::table('tblhosting')->where('id', $serviceId)->first();
    if (!$service) return;

    $status = (string) ($service->domainstatus ?? '');
    if (!in_array($status, ['Cancelled','Terminated'], true)) {
        return;
    }
    $client = Capsule::table('tblclients')->where('id', $userId)->first();
    if (!$client || empty($client->email)) return;

    $hasOtherServices = Capsule::table('tblhosting')
        ->where('userid', $userId)
        ->where('id', '!=', $serviceId)
        ->whereIn('domainstatus', ['Active','Suspended'])
        ->exists();

    $hasActiveAddons = Capsule::table('tblhostingaddons')
        ->join('tblhosting', 'tblhostingaddons.hostingid', '=', 'tblhosting.id')
        ->where('tblhosting.userid', $userId)
        ->where('tblhostingaddons.status', 'Active')
        ->exists();

    $hasActiveDomains = Capsule::table('tbldomains')
        ->where('userid', $userId)
        ->where('status', 'Active')
        ->exists();

    $hasAnythingActive = $hasOtherServices || $hasActiveAddons || $hasActiveDomains;
    if ($hasAnythingActive) return;
    send_to_script_wprocketelementor($userId);

    if (function_exists('sr_rds_already_has_lead') &&
        sr_rds_already_has_lead($client->email, 'API_Cliente_Cancelado')) return;

    if (function_exists('rd_send_api_cliente_cancelado')) {
        rd_send_api_cliente_cancelado((string) $client->email);
    }
    if (function_exists('sr_rds_insert_lead')) {
        sr_rds_insert_lead($client->email, 'API_Cliente_Cancelado');
    }
});