<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\mds_legales_caratula */

$this->title = 'Crear carátula';
$this->params['breadcrumbs'][] = ['label' => 'Mds Legales Carátula', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-legales-caratula-create">

    <?php $form = ActiveForm::begin(['id' => 'formLegalesCaratula', 'action' => ['mds_legales_caratula/store'], 'options' => ['enctype' => 'multipart/form-data', 'form-legales-caratula']]); ?>

    <?= $this->render('_form', [
        'action' => $action,
        'model' => $model,
        'form' => $form,
        'puedeEliminar' => $puedeEliminar,
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
