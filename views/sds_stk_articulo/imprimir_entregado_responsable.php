<?php

function get_mes($mes)
{
    switch ($mes) {
        case "1":
            $mes = "Enero";
            break;

        case "2":
            $mes =  "Febrero";
            break;

        case "3":
            $mes =  "Marzo";
            break;

        case "4":
            $mes =  "Abril";
            break;
        case "5":
            $mes = "Mayo";
            break;

        case "6":
            $mes =  "Junio";
            break;

        case "7":
            $mes =  "Julio";
            break;

        case "8":
            $mes =  "Agosto";
            break;
        case "9":
            $mes = "Septiembre";
            break;

        case "10":
            $mes =  "Octubre";
            break;

        case "11":
            $mes =  "Noviembre";
            break;

        case "12":
            $mes =  "Diciembre";
            break;
    }
    return $mes;
}

function crear_linea($label, $contenido)
{
    echo "<br><b> $label: </b><span style='font-size: 12px;'>$contenido</span>";
}
function crear_titulo_recuadro($label, $ancho)
{
    echo "<div class='row'><div class='col-xs-$ancho'> 
                <div style='border-radius: 5px; box-shadow: 5px 5px black;background-color: black;'>
                <div style='border-radius: 5px;background-color: #c3c3c3; padding:3px;'>$label</div></div></div></div>";
}
?>




<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;font-size: 10pt;">
        <div class="row" style="text-align:center;">
            <img src="img/membrete_nuevo_pri.jpg" width="70%" alt="Subsecretaría de Desarrollo Social">
        </div>
        <div class="row" style="text-align:right;padding-top: 2%;font-size: 11pt;">
            Neuquén, <?= date('d')." de ". get_mes(date('m'))." de ".date('Y')." ".date('H:i'); ?> hs.
        </div>
        <div class="row" style="text-align:center;padding-bottom: 1%;">
            <div class="col-xs-12">
                <h2><b> Entrega de insumos a responsables <?=$periodo?> </b></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="div_grilla">
                <?php
                echo $tabla;
                ?>
            </div>
        </div>
    </div>
</body>

</html>