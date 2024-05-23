<html>

<body>
    <img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
    <div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
        <h4 style="margin: 0; font-weight: bold;">REPORTE RELEVAMIENTO EDILICIO</h4>
    </div>
    <ul>
        <hr>
        <b>Fecha realizado: </b>
        <?php if ($model->fecha) {
            $fv = date_create($model->fecha);
            $fv = date_format($fv, 'd-m-Y');
            echo $fv;
        } ?>
        <br>
        <b>Lugar: </b><span><?= $model->idcapaitem ? $model->capaitem->descripcion : '' ?></span><br>
        <b>Dirección: </b><span><?= $model->idcapaitem ? $model->capaitem->direccion : '' ?></span><br>
        <b>Realizado por: </b><span><?= $model->idusuario_carga ? $model->usuarioCarga->apellido . '  ' . $model->usuarioCarga->nombre : '' ?></span>
        <b> </b><span> el dia
            <?php if ($model->created_at) {
                $fc = date_create($model->created_at);
                $fc = date_format($fc, 'd-m-Y');
                echo $fc;
            } ?></span><br>
        <b>Posee documentación adjunta: </b><span><?= count($adjuntos) ? 'Si' : 'No' ?></span>
    </ul>
    <?php foreach ($agrupadores as $agrupador) { ?>
        <ul>
            <hr>
            <div style="text-align: center"><b><?= $agrupador['titulo'] ?> </b></div>
            <?php foreach ($model_respuesta as $item) { ?>
                <?php if ($agrupador['idconfiguraciontipo'] == $item['idconfiguraciontipo']) { ?>
                    <li>
                        <b><?= $item['descripcion'] ?>: </b><span><?= $item['posee'] === null ? '' : ($item['posee'] == 1 ? 'Si' : 'No') ?></span>
                    </li>
                    Detalle:
                    <?php echo $item['detalle'] === null ? '' : $item['detalle'] ?>
                <?php } ?>

            <?php } ?>
        </ul>
    <?php } ?>
    <ul>
        <hr>
        <div style="text-align: center"><b>Observaciones</b></div>
        <br>
        <span><?= $model->observaciones ?></span>
    </ul>
</body>

</html>