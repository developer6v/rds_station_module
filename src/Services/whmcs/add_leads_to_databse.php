<?php

use WHMCS\Database\Capsule;

function sr_rds_insert_lead($email, $conversion_type) {
    $now = date('Y-m-d H:i:s');
    Capsule::table('sr_rds_leads')->insert([
        'email' => $email,
        'conversion_type' => $conversion_type,
        'created_at' => $now,
        'updated_at' => $now
    ]);
}

?>
