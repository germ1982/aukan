<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">Observaciones</h2>
            </header>
            <div class="panel-body">
                <?= str_replace(PHP_EOL, '<br>', $model->observaciones) ?>
            </div>
        </section>
    </div>
</div>