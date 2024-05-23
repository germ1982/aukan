<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img src="https://desasur.neuquen.gov.ar/familia/web/img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
        <div style="margin: 0 60px 0 60px;">
            <div class="textRight fechaHeader"><?= $fechaHeader ?></div>
            <div class="textRight"><b>Nota N° <?= $nota->numero ?> / <?= $nota->anio ?> .-</b></div>
            <div class="textRight"><?= $nota->referencia ?> </div>
            <br />
            <div><b><?= $nota->destinatario_nombre ?><br /><?= $nota->destinatario_direccion ?></b></div>
            <div><u><b><span class="customSpacing">S / </span>D</b></u></div>
            <div class="nota"><?= $nota->nota ?></div>
        </div>
    </div>
    </div>
</body>

</html>