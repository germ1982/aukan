<?php
$host = "";
//$host = "localhost";
$port = "";
$data = "";
//$user = "postgres"; //usuario de postgres
$user = ""; //usuario de postgres
$pass = ""; //password de usuario de postgres

$conn_string = "host=". $host . " port=" . $port . " dbname= " . $data . " user=" . $user . " password=" . $pass;
$dbconn = pg_connect($conn_string) or die;
//echo $conn_string;
// validar la conexión
if(!$dbconn) {
    echo "Error al conectar a la Base de datos\n";
    exit;
}
else {
    //echo "Conectado Correctamente <br>";
}