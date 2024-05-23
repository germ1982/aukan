<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">Detalle</h2>
            </header>
            <div class="panel-body">
                <?php

                use app\models\Sds_com_configuracion;

                if ($model->observaciones != null && strlen($model->observaciones) > 0) :
                ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?= "<b>Observaciones:</b>
                        <br>" . nl2br($model->observaciones) ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <br>
                    <div class="col-md-5">
                        <?= $model->proveedor != null ? "<b>Proveedor: </b>" .  Sds_com_configuracion::findOne($model->proveedor)->descripcion : ""; ?>
                    </div>
                    <div class="col-md-7">
                        <?= $model->oc != null ? "<b>N° Orden Compra: </b>" .  $model->oc : ""; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>