<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif; font-size: 8">
        <img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
        <div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
            <h4 style="margin: 0; font-weight: bold;">Reporte último requerimiento sin contestar</h4>
            <h5>Total: <?= $caratulasCount ?></h5>
            <p><span> </span></p>
            <hr style="margin: 0 0 10px 0">
        </div>

        <?php foreach ($caratulas as $index => $caratula) : ?>
            <?php if ($caratula->oficios) :
                $oficio = $caratula->oficios;
            ?>
                <table autosize="0">
                    <tr style="background-color: #dddddd;">
                        <th class="titulo" valign="top" colspan="12">
                            <h5>Caratula: "<?= $caratula->caratula ?>" </h5>
                        </th>
                    </tr>

                    <?php
                    $fechaRecepcion = "No tiene";
                    if ($oficio->fecha_recepcion) {
                        $fechaRecepcion = date_create($oficio->fecha_recepcion);
                        $fechaRecepcion = date_format($fechaRecepcion, 'd-m-Y');
                    } ?>
                    <?php
                    $fechaPlazo = "No tiene";
                    if ($oficio->fecha_plazo) {
                        $fechaPlazo = date_create($oficio->fecha_plazo);
                        $fechaPlazo = date_format($fechaPlazo, 'd-m-Y');
                    } ?>
                    <tr>
                        <td valign="top" colspan="2"><b>#</b><span><?= $oficio->idlegalesoficio ?></span></td>
                        <td valign="top" colspan="2"><b>Recepción: </b><span><?= $fechaRecepcion ?></span></td>
                        <td valign="top" colspan="2"><b>Tipo: </b><span><?= $oficio->tipoOficio ? $oficio->tipoOficio->descripcion : '' ?></span></td>
                        <td valign="top" colspan="2"><b>Área: </b><span><?= $oficio->areaOficio ? $oficio->areaOficio->descripcion : '' ?></span></td>
                        <td valign="top" colspan="2"><b>Vencimiento: </b><span><?= $fechaPlazo ?></span></td>
                        <td valign="top" colspan="1"><b>Rtas generadas: </b><span><?= $oficio->getTotalRespuestasGeneradas() ?></span></td>
                        <td valign="top" colspan="1"><b>Rtas enviadas: </b><span><?= count($oficio->getRespuestasAprobadas()) ?></span></td>
                    </tr>
                </table>
                <hr style="margin: 10px 0 10px 0">
            <?php endif; ?>

        <?php endforeach; ?>
    </div>
</body>

</html>