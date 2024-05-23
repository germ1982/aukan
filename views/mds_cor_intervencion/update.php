<?php

/* @var $this yii\web\View */
/* @var $model app\models\Mds-cor-intervencion */
$this->title = "Actualizar intervención #{$model->idintervencion}";

?>
<div class="mds-cor-intervencion-update">

    <?= $this->render('_form', [
        'model' => $model,
        'listProvincias' => $listProvincias,
        'listLocalidades' => $listLocalidades,
        'articulaciones' => $articulaciones,
        'listArticulaciones' => $listArticulaciones,
        'consumos' => $consumos,
        'listConsumos' => $listConsumos,
        'problemas' => $problemas,
        'listProblemas' => $listProblemas,
    ]) ?>

</div>