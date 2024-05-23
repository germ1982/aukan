<?php

#funcion comparar password
function django_password_verify(string $password, string $djangoHash): bool
{
    $pieces = explode('$', $djangoHash);
    if (count($pieces) !== 4) {
        throw new Exception("Illegal hash format");
    }
    list($header, $iter, $salt, $hash) = $pieces;
    // Get the hash algorithm used:
    if (preg_match('#^pbkdf2_([a-z0-9A-Z]+)$#', $header, $m)) {
        $algo = $m[1];
    } else {
        throw new Exception(sprintf("Bad header (%s)", $header));
    }
    if (!in_array($algo, hash_algos())) {
        throw new Exception(sprintf("Illegal hash algorithm (%s)", $algo));
    }

    $calc = hash_pbkdf2(
        $algo,
        $password,
        $salt,
        (int) $iter,
        32,
        true
    );
    return hash_equals($calc, base64_decode($hash));
}

?>