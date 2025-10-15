<?php
include_once('../../../../../init.php');
require_once __DIR__ . "/../Services/rds_station/core/delete_lead.php";

header('Content-Type: text/plain; charset=utf-8');

 rd_delete_contact_by_email("cancelado@example.com");
 rd_delete_contact_by_email("ana.teste@example.com");