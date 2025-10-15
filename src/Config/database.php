<?php 

use WHMCS\Database\Capsule;

function sr_rds_station_databse () {
    if (!Capsule::schema()->hasTable('sr_rds_station_config')) {
        Capsule::schema()->create('sr_rds_station_config', function ($table) {
            $table->increments('id'); 
            $table->text('access_token')->nullable(); 
            $table->text('refresh_token')->nullable(); 
            $table->text('client_id')->nullable(); 
            $table->text('client_secret')->nullable();
        });

        Capsule::table("sr_rds_station_config")->insert([
            "client_secret" =>"",
            "client_id" => "",
            "access_token" => "",
            "refresh_token" => ""
        ]);
    }

}

    

?>