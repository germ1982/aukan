<?php

    function validar_public_hash($json, $private_key) {
        
        $public_key = $json['public_key'];
        $public_hash = $json['public_hash'];
        if ($public_hash == generar_public_hash($public_key, $private_key)) {
            return true;
        }
        else {
            return false;
        }
    }

    function generar_public_key() {
        $dec_timestamp = mktime();
        return sha1($dec_timestamp);
    }

    function generar_public_hash($public_key, $private_key) {
        $hash = sha1(sha1($public_key).":".sha1($private_key));
        return $hash;
    }

    // Ejemplo de funcionamiento

 /*   $api_key = "3%MUFY+pAVMUQ=AhdI5E%SkE9RPPsZu4^YbnaCknYq=IJBA=KRfru8Wr1dgP";
    $public_key = generar_public_key();
    $public_hash = generar_public_hash($public_key, $api_key);

    $json_valido = json_encode(
        array(
            'public_key' => $public_key,
            'public_hash' => $public_hash,
            'id' => 123,
            'profesional_id' => 456
        )
    );
    $json_invalido = json_encode(
        array(
            'public_key' => $public_key,
            'public_hash' => $public_hash."22",
            'id' => 123,
            'profesional_id' => 456
        )
    );

    if (validar_public_hash($json_valido, $api_key)) {
        echo "Autenticado";
    }
    else {
        echo "No autenticado";
    }
*/
?>
