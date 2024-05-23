<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">Detalle</h2>
            </header>
            <div class="panel-body">
                <?php
                if ($model->observaciones != null && strlen($model->observaciones) > 0) :
                ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?= "<b>Observaciones:</b>
                        <br>" . nl2br($model->observaciones) ?>
                        </div>
                    </div>
                <?php endif;
                if ($model->rendiciones_pendientes != null && strlen($model->rendiciones_pendientes) > 0) : ?>
                    <div class="row">
                        <br>
                        <div class="col-md-7">
                            <?= "<b>Pendientes de Rendición:</b>
                            <br>" . $model->rendiciones_pendientes ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>