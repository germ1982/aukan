<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img src="https://desasur.neuquen.gov.ar/familia/web/img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
        <div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
            <h4 style="margin: 0; font-weight: bold;">REPORTE DEL ESTADO DE LA POSTULACIÓN</h4>
            <p><span> Postulación Concurso </span></p>
            <hr style="margin: 0 0 10px 0">
        </div>

        <table style="border-spacing: 0 5px; border-collapse: separate;">
            <tr style="background-color: #dddddd;">
                <th colspan="2" class="titulo">
                    <h5>DATOS PERSONALES: </h5>
                </th>
            </tr>
            <tr>
                <td colspan="1" valign="top"><b>Nombre: </b><span><?= mb_strtoupper($model->postulacion0->solicitud->nombre) ?></span></td>
                <td colspan="1" valign="top"><b>Apellido: </b><span><?= mb_strtoupper($model->postulacion0->solicitud->apellido) ?></span></td>
            </tr>
            <tr>
                <td colspan="1" valign="top"><b>Documento: </b><span><?= $model->postulacion0->solicitud->documento ?></span></td>
                <td colspan="1" valign="top"><b>Legajo: </b><span><?= $model->postulacion0->solicitud->legajo ?></span></td>
            </tr>
            <tr>
                <td colspan="1" valign="top"><b>Correo electrónico: </b><span><?= $model->postulacion0->solicitud->mail ?></span></td>
                <td colspan="1" valign="top"><b>Teléfono: </b><span><?= $model->postulacion0->solicitud->telefono ?></span></td>
            </tr>
            <tr>
                <td colspan="2" valign="top"><b>Domicilio Fiscal: </b><span><?= $model->postulacion0->solicitud->domicilio_fiscal ?></span></td>
            </tr>
            <tr style="background-color: #dddddd;">
                <th class="titulo" colspan="2">
                    <h5>DETALLE DEL ESTADO DE LA POSTULACIÓN #<?= $model->idpostulacion ?>:</h5>
                </th>
            </tr>
            <?php if (isset($model->postulacion0->vacante)) : ?>
                <tr>
                    <?php if (isset($model->postulacion0->vacante->categoria0)) : ?>
                        <td colspan="1" valign="top"><b>Categoría: </b><?= $model->postulacion0->vacante->categoria0->descripcion ?></td>
                    <?php endif; ?>
                </tr>
            <?php endif; ?>
            <tr>
                <?php if ($model->estado_nuevo) : ?>
                    <td colspan="1" valign="top"><b>Estado nuevo: </b><?= $estadoNuevo ?></td>
                <?php endif; ?>
                <?php if (isset($puntaje)) : ?>
                    <td colspan="1" valign="top"><b>Puntaje: </b> <?= $puntaje ?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <?php if ($model->estado_anterior) : ?>
                    <td colspan="1" valign="top"><b>Estado anterior: </b> <?= $estadoAnterior  ?></td>
                    <?php endif; ?>
                    <td colspan="1" valign="top"><b>Fecha: </b> <?= $fecha ?></td>
            </tr>
            <tr>
                <td colspan="2" valign="top"><b>Usuario cambio de estado: </b> <?= $usuarioCarga ?></td>
            </tr>
            <?php if (isset($motivosImpugnacion)) : ?>
                <tr>
                    <td colspan="2" valign="top" style="text-align: justify;"><b>Motivo impugnación: </b> <?= $motivosImpugnacion ?></td>
                </tr>
            <?php endif; ?>
            <?php if (isset($model->observacion)) : ?>
                <tr>
                    <td colspan="2" valign="top" style="text-align: justify;"><b>Observación: </b><?= $model->observacion ?></td>
                </tr>
            <?php endif; ?>
            <?php if (isset($model->observacion_publica)) : ?>
                <tr>
                    <td colspan="2" valign="top" style="text-align: justify;"><b>Observación pública: </b><?= $model->observacion_publica ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>

</html>