<?php


function send_to_script_wprocketelementor($userid) {
    $id = 1;
    $name = "teste";
    $email = "teste@teste.com.br";

    $ch = curl_init("https://script.google.com/macros/s/AKfycbx4AmmVdgNdCGBiaMngu-Ix70jeomQdl-h9ExZiiFccZ7G2Hv9bE0Tsm6611rIvaoLf/exec");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "id" => $id,
        "name" => $name,
        "email" => $email,
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($err || $code !== 200) {
        error_log("cURL error/HTTP $code: $err | $response");
        return false;
    }

    return $response;
}
