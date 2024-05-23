<?php

$this->title = 'Crear Intervención';
$this->params['breadcrumbs'][] = ['label' => 'Crear Intervención', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-cor-intervencion-create">
    <?= $this->render('_form', [
        'model' => $model,
        'listProvincias' => $listProvincias,
        'listLocalidades' => $listLocalidades,
        'articulaciones' => [],
        'problemas' => [],
        'consumos' => [],
        'listArticulaciones' => $listArticulaciones,
        'listProblemas' => $listProblemas,
        'listConsumos' => $listConsumos,
    ]) ?>
</div>