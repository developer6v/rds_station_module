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
            "client_secret" =>"b3ea673b573e4c1289a201ada1ca6010",
            "client_id" => "3ff21d23-122e-49ad-bc22-ed7a818e5780",
            "access_token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaS5yZC5zZXJ2aWNlcyIsInN1YiI6ImhWMGZWdXc4cE9TTnAzSDY2MU1mVTR3Qy01RnBabFY2R0tOX3VyMV9yclFAY2xpZW50cyIsImF1ZCI6Imh0dHBzOi8vYXBwLnJkc3RhdGlvbi5jb20uYnIvYXBpL3YyLyIsImFwcF9uYW1lIjoiSW50ZWdyYcOnw6NvIFdITUNTIiwiZXhwIjoxNzYwNTg5Mjk1LCJpYXQiOjE3NjA1MDI4OTUsInNjb3BlIjoiIn0.qO88Pu9pc7_GpiF0SLg0AUi-IbfIQ0Syd2fr8hZb8SLqnPtvt9LQip02LAWj-bOJ1k-kmzk1ZBtHz1P8_w1ggqmbPQ6jnfK2pG4xIvvsBXmkpotvVnaYJDmWnNQvhM4EmvszeQEIi7eg15nAvCuX0u82I5XHj6vaWb_L_lqqnFE1Avwo4lIGnswaSS8WtO-pFEBixp6ByogkPyrXkYTaECabuwg5jXs-f9lDc-So3NQv8EgKkVh_s355dFLiBVIxgwaHT59v08KkXoS5PT_XPnIsmgyaBc2pNm5hv6R8EQDjDZalFeS5gsuYVAtSq3LdY6Qg5XIQpribHEP0TAewnw",
            "refresh_token" => "9SMVzP_hurrqIZObx_vrHGyQmNdOB--v-4LZNCyNt4w"
        ]);
    }

    if (!Capsule::schema()->hasTable('sr_rds_leads')) {
        Capsule::schema()->create('sr_rds_leads', function ($table) {
            $table->increments('id');
            $table->text('email')->nullable();
            $table->text('conversion_type')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }


}

    

?>