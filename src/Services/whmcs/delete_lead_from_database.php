<?php

use WHMCS\Database\Capsule;

function sr_rds_delete_lead_by_email(string $email) {
    return Capsule::table('sr_rds_leads')->where('email', $email)->delete();
}