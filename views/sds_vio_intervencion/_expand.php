<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">Detalle</h2>
            </header>
            <div class="panel-body">
                <?= $model->detalle ?>
            </div>
        </section>
    </div>
</div>
<?php
if ($model->abordaje_complementario != null && strlen($model->abordaje_complementario) > 0) :
?>
    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <h2 class="panel-title">Abordajes Complementarios</h2>
                </header>
                <div class="panel-body">
                    <?= $model->abordaje_complementario ?>
                </div>
            </section>
        </div>
    </div>
<?php endif; ?>