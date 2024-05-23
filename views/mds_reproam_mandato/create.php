<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_reproam_mandato */

$this->title = 'Crear ReProAM Mandato';
$this->params['breadcrumbs'][] = ['label' => 'Mds Reproam Mandatos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-reproam-mandato-create">

    <?php $form = ActiveForm::begin(['id' => 'formReproamMandato', 'action' => ['mds_reproam_mandato/store'], 'options' => ['enctype' => 'multipart/form-data', 'form-reproam-mandato']]); ?>

    <?= $this->render('_form', [
        'action' => $action,
        'model' => $model,
        'form' => $form,
        'registros' => $registros,
        'puedeEliminar' => $puedeEliminar,
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
