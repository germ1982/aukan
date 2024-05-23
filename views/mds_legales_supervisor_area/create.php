<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\mds_legales_supervisor_area */

$this->title = 'Crear supervisor/a de área';
$this->params['breadcrumbs'][] = ['label' => 'Mds Legales Supervisor Area', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-legales-supervisor-area-create">

    <?php $form = ActiveForm::begin(['id' => 'formLegalesSupervisorArea', 'action' => ['mds_legales_supervisor_area/store'], 'options' => ['enctype' => 'multipart/form-data', 'form-legales-supervisor-area']]); ?>

    <?= $this->render('_form', [
        'action' => $action,
        'model' => $model,
        'form' => $form,
        'areas' => $areas,
        'usuariosSupervisores' => $usuariosSupervisores,
        'puedeEliminar' => $puedeEliminar,
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
