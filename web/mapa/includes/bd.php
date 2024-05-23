<?php
$host = env('DB_SUR_HOST');
$port = "80";
$data = env('DB_SUR_NAME');
$user = env('DB_SUR_USERNAME');
$pass = env('DB_SUR_PASSWORD');


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