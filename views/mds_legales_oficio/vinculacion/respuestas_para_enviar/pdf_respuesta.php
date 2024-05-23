<html>
<body>
<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
    <img src="img/MEMBRETE FAMILIA.jpg" width="100%" alt="Subsecretaría de Desarrollo Social">
    <div class="row" style="padding-top: 1%;">
        <?php
           setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
           $date = iconv('ISO-8859-2', 'UTF-8', strftime(" %d de %B de %Y", strtotime(date($respuesta['fecha_carga']))));
        ?>
        <br>
        <div class="col-xs-offset-7 col-xs-5" style="text-align: right;"><?php echo "Neuquén, ". $date ?></div>
    </div>
    <div class="row">
        <div class="col-xs-12" style="padding-top: 2%">
            <b>Referencia: </b><span><?php echo $oficio['caratula'] ?></span>
        </div>
    </div>
   <!-- <div class="row">
        <div class="col-xs-12" style="padding-top: 2%">
            <b>Número de expediente: </b><span><?php echo $oficio['numero_expediente'] ?></span>
        </div>
    </div>-->
    <div class="row">
        <div class="col-xs-12" style="padding-top: 2%">
            <b>Número: </b><span><?php echo $oficio['caso'] ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12" style="padding-top: 2%">
            <b>Destinatario: </b><span><?php echo $oficio->lugar_libramiento ?></span>
        </div>
    </div>
   <!-- <div class="row">
        <div class="col-xs-12" style="padding-top: 2%">
            <b>Año de expediente: </b><span><?php echo $oficio['anio_expediente'] ?></span>
        </div>
    </div>-->

    <div class="row">
        <div class="col-xs-12" style="padding-top: 2%">
            <b>Generador/a de la respuesta: </b><span><?php echo mb_strtoupper($respuesta->usuario['apellido']). ", " . mb_strtoupper($respuesta->usuario['nombre'])?></span>
        </div>
    </div>
    <div class="row" style="text-align:center;padding-top: 2%;padding-bottom: 2%;font-size: 12pt;">
        <div class="col-xs-12">
            <b>Respuesta</b><br>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <p><?php echo $respuesta['texto_repuesta'] ?></p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <?php
                foreach($respuesta->getProfesionalesIntervinientes() as $profesionalInterviniente)
                { ?>
                    <p style="text-align: right; margin-bottom: 30px"><?php echo  mb_strtoupper($profesionalInterviniente->usuario->apellido) . ", ". mb_strtoupper($profesionalInterviniente->usuario->nombre)  ?></p>

               <?php     
                }
            ?>
        </div>
    </div>
</div>
</body>
</html>