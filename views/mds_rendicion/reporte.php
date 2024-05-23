<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img src="https://desasur.neuquen.gov.ar/familia/web/img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
        <div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
            <h4 style="margin: 0; font-weight: bold;">REPORTE DE RENDICIÓN</h4>
            <p><span> Rendición </span></p>
            <hr style="margin: 0 0 10px 0">
        </div>

        <?php
        $size = count($arrayRendiciones);
        $pages = 1;
        ?>

        <?php foreach ($arrayRendiciones as $model) { ?>
            <table>
                <tr style="background-color: #dddddd;">
                    <th class="titulo">
                        <h5>DATOS DE CARGA RENDICION #<?= $model->idrendicion ?>:</h5>
                    </th>
                </tr>
                <tr>
                    <td valign="top"><b>Fecha de carga: </b><span><?= $model->fechaCarga ?></span></td>
                    <td valign="top"><b>Usuario de carga: </b><span><?= $model->idusuario_carga ? strtoupper($model->usuarioCarga->apellido) . ' ' . strtoupper($model->usuarioCarga->nombre) : ""  ?></span></td>
                </tr>
                <?php if ($model->updated_at) { ?>
                    <tr>
                        <td valign="top"><b>Fecha de última modificación: </b><span><?= $model->fechaModifica ?></span></td>
                        <td valign="top"><b>Usuario de última modificación: </b><span><?= $model->idusuario_modifica ? strtoupper($model->usuarioModifica->apellido) . ' ' . strtoupper($model->usuarioModifica->nombre) : ""  ?></span></td>
                    </tr>
                <?php } ?>
                <tr style="background-color: #dddddd;">
                    <th class="titulo">
                        <h5>DATOS DE RENDICIÓN: </h5>
                    </th>
                </tr>
                <tr>
                    <td valign="top"><b>Tipo de rendición: </b><span><?= $model->tipo->descripcion ?></span></td>
                </tr>
                <?php if ($model->idpersona) { ?>
                    <tr>
                        <td valign="top"><b>Tipo de Documento: </b><span><?= $model->persona->documentoTipo->descripcion ?></span></td>
                        <td valign="top"><b>Nro de Documento: </b><span><?= $model->persona->documento ?></span></td>
                    </tr>
                    <tr>
                        <td valign="top"><b>Apellido y Nombre: </b><span><?= strtoupper($model->persona->apellido) ?> <?= strtoupper($model->persona->nombre) ?></span></td>
                        <td valign="top"></td>
                    </tr>
                <?php } ?>

                <?php if ($model->idusuario_comprobante) { ?>
                    <tr>
                        <td valign="top"><b>Usuario: </b><?= strtoupper($model->usuarioComprobante->apellido) ?> <?= strtoupper($model->usuarioComprobante->nombre) . ' (' . $model->usuarioComprobante->dni . ')' ?></span></td>
                        <td valign="top"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td valign="top"><b>Sector: </b><span><?= $model->capa->descripcion ?></span></td>
                    <td valign="top"><b>Lugar: </b><span><?= $model->lugar->descripcion ?></span></td>
                </tr>
                <tr>
                    <td valign="top"><b>Monto Total: </b><span><?= $model->monto ?></span></td>
                </tr>
                <tr>
                    <?php if ($model->fecha_comprobante) { ?>
                        <td valign="top"><b>Fecha de factura/recibo/comprobante: </b><span><?= $model->fechaComprobante ?></span></td>
                    <?php } ?>
                    <?php if ($model->fecha_vale) { ?>
                        <td valign="top"><b>Fecha de entrega del vale: </b><span><?= $model->fechaVale ?></span></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:justify" valign="top"><b>Observaciones: </b><span><?= $model->observaciones ?></span></td>
                </tr>
                <tr>
                    <td valign="top">
                        <b>Posee documentación adjunta: </b><span><?= count($model->getOtrosAdjuntos()) > 0 ? 'Si' : 'No' ?></span>
                    </td>
                </tr>
            </table>

            <?php if ($model->comprobantes) : ?>
                <table>
                    <tr style="background-color: #dddddd;">
                        <th class="titulo">
                            <h5>COMPROBANTES: </h5>
                        </th>
                    </tr>
                </table>
                <?php foreach ($model->comprobantes as $key => $model_comprobante) : ?>
                    <table>
                        <tr>
                            <td valign="top"><b>Fecha de carga: </b><span><?= $model_comprobante->fechaCarga ?></span></td>
                            <td valign="top"><b>Usuario de carga: </b><span><?= $model_comprobante->idusuario_carga ? strtoupper($model_comprobante->usuarioCarga->apellido) . ' ' . strtoupper($model_comprobante->usuarioCarga->nombre) : ""  ?></span></td>
                        </tr>
                        <tr>
                            <td valign="top"><b>Fecha Desde: </b><span><?= $model_comprobante->fechaDesde ?></span></td>
                            <td valign="top"><b>Fecha Hasta: </b><span><?= $model_comprobante->fechaHasta ?></span></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:justify" valign="top"><b>Observaciones: </b><span><?= $model_comprobante->observaciones ?></span></td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <b>Posee documentación adjunta: </b><span><?= count($model_comprobante->getOtrosAdjuntos()) > 0 ? 'Si' : 'No' ?></span>
                            </td>
                        </tr>
                    </table>
                    <hr style="margin: 10px 0 10px 0">
                <?php endforeach; ?>
            <?php endif ?>

            <?php if ($pages < $size) { ?>
                <div class="saltopagina"></div>
            <?php }
            $pages++;
            ?>
        <?php } ?>
    </div>
</body>

</html>