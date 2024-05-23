<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_acomp_asistencia */

$this->title = 'Crear Asistencia';
$this->params['breadcrumbs'][] = ['label' => 'Mds Acomp Asistencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-acomp-asistencia-create">

    <?php $form = ActiveForm::begin(['id' => 'formAcompAsistencia', 'action' => ['mds_acomp_asistencia/store'], 'options' => ['enctype' => 'multipart/form-data', 'form-acomp-asistencia']]); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'form' => $form,
        'ID_LOCALIDAD_NEUQUEN_CAPITAL' => $ID_LOCALIDAD_NEUQUEN_CAPITAL,
        'localidades' => $localidades,
        'riesgos' => $riesgos,
        'nacionalidades' => $nacionalidades,
        'tiposDocumentos' => $tiposDocumentos,
        'username' => $username,
        'generos' => $generos,
        'hasRolGlobal' => $hasRolGlobal,
        'hasRolAdminGeneral' => $hasRolAdminGeneral,
        'token' => $token
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>

