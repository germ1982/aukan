<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_reproam_mandato */

$this->title = "Actualizar Mandato #{$model->idmandato}";

?>
<?php $form = ActiveForm::begin(['action' => ["mds_reproam_mandato/update", 'id' => $model->idmandato], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-legales-oficio-update">
    <?= $this->render('_form', [
        'action' => $action,
        'registros' => $registros,
        'form' => $form,
        'model' => $model,
        'puedeEliminar' => $puedeEliminar,
    ]) ?>
</div>
<?php ActiveForm::end(); ?>