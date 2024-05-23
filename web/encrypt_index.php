<?php
error_reporting(0);

require 'funciones.php';

#Uusuario conocido
$user = '8k';
#password conocido
$pass = 'zGmJm#K1/D';

#conectar a bd
$dbconn = pg_connect("host=localhost dbname=sigah_postgis user=postgres password=postgres") or die('No se ha podido conectar: ' . pg_last_error());

// Realizando una consulta SQL
$query = "SELECT password FROM auth_user WHERE username = '$user'";
$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

$r = pg_fetch_array($result);
echo 'Password desde base de datos segun usuario conocido '.$user.': '.$r['password'].'</br>';


$passwordDjango = explode('$', $r['password']);
if($passwordDjango[0] == 'pbkdf2_sha256'){
    for($i = 0; $i < count($passwordDjango); $i++){
        switch($i){
            case 0: echo 'tipo de hash: '.$passwordDjango[$i].'</br>';
            break;
            case 1: echo 'Iteraciones: '.$passwordDjango[$i].'</br>';
            break;
            case 2: echo 'Sal: '.$passwordDjango[$i].'</br>';
            break;
            case 3: echo 'hash: '.$passwordDjango[$i].'</br>';
            break;
        }        
    }    
}else{
    for($i = 0; $i < count($passwordDjango); $i++){
        switch($i){
            case 0: echo 'tipo de hash: '.$passwordDjango[$i].'</br>';
            break;
            case 1: echo 'Sal: '.$passwordDjango[$i].'</br>';
            break;
            case 2: echo 'hash: '.$passwordDjango[$i].'</br>';
            break;
        }
        
    } 
}

/******************************************************
 * ESTO TE COMPARA DEL TIPO 
 * sha256
 * DEBERIAS REALIZAR UN SWITCH POR SI TE ENCONTRAS
 * UN PASSWORD EN SHA1,
 * DE TODAS MANERAS LOS PASSWORD SHA1 ESTAN OBSOLETOS
 */

if($passwordDjango[0] == 'pbkdf2_sha256'){
    echo 'es: ' . $passwordDjango[0];
    #comparacion de passwords
    if (django_password_verify($pass, $r['password'])) {
        echo '</br> ¡La contraseña es válida!';
    } else {
        echo '</br> La contraseña no es válida.';
    }  
}else{
    echo 'es: ' . $passwordDjango[0];
}


