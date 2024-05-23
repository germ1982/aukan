<?php
$nombre= "soraya"; 
$email = "palacios.soraya@gmail.com"; 
$titulo= "hola"; 
$mensaje = "hola este es un mensaje de Sistema de "; 

//$headers .= "MIME-Version: 1.0\n";  
//$headers .= "Content-type: text/html; charset=iso-8859-1\n";  
//$headers .= "From: $_POST[nombre] <$_POST[email]>";
        $mailheaders = "MIME-Version: 1.0 \r\n"; 
    $mailheaders .= "Content-type: text/html; charset=iso-8859-1 \r\n"; 
    $mailheaders .= "From: $nombre <$email> \r\n"; 
    $mailheaders .= "Return-path: $nombre <$email> \r\n";
    $mailheaders .= "X-Priority: 1 \r\n"; 
    $mailheaders .= "X-MSMail-Priority: High \r\n"; 
    $mailheaders .= "X-Mailer: PHP/".phpversion()." \n"; 

if (isset($email)): 
# la dirección electrónica a la que enviar el email 
$target="palacios.soraya@gmail.com"; 

mail($target, 
     $titulo, 
     "Nombre: ".$nombre. 
     "\nEmail: ".$email. 
     "\nTítulo: ".$titulo. 
     "\nMensaje: ".$mensaje, 
     $headers); 
endif;  
?>
