<?php

include_once __DIR__ . '/hooks.php';
include_once __DIR__ . '/src/Config/database.php';

function rds_station_module_config() { 
    return array(
        'name' => 'RDS Station Integration For WHCMS',
        'description' => 'Módulo responsável pela integração do RDS Station com o WHMCS.',
        'version' => '1.0',
        'author' => 'Sourei',
        'fields' => array()
    );
}

function rds_station_module_activate() {
    sr_rds_station_databse ();
    return array('status' => 'success', 'description' => 'Módulo ativado com sucesso!');
}

function rds_station_module_deactivate() {
    return array('status' => 'success', 'description' => 'Módulo desativado com sucesso!');
}

function rds_station_module_output() {
    //echo config();
}






?>