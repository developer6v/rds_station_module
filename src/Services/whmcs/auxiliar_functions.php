<?php
use WHMCS\Database\Capsule;


function sr_has_other_active_services(int $clientId, ?int $exceptServiceId = null): bool {
    $q = Capsule::table('tblhosting')
        ->where('userid', $clientId)
        ->where('domainstatus', 'Active');

    if ($exceptServiceId) {
        $q->where('id', '!=', $exceptServiceId);
    }
    return $q->exists();
}
