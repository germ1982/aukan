<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_acomp_asistencia */

$this->title = "Actualizar Asistencia #{$model->idasistencia}";

?>
<?php $form = ActiveForm::begin(['action' => ["mds_acomp_asistencia/update", 'id' => $model->idasistencia], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-legales-oficio-update">
    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
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
</div>
<?php ActiveForm::end(); ?>
