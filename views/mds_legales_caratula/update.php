<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_reproam_mandato */

$this->title = "Actualizar carátula #$model->idlegalescaratula";

?>
<?php $form = ActiveForm::begin(['action' => ["mds_legales_caratula/update", 'id' => $model->idlegalescaratula], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-legales-caratula-update">
    <?= $this->render('_form', [
        'action' => $action,
        'form' => $form,
        'model' => $model,
        'puedeEliminar' => $puedeEliminar,
    ]) ?>
</div>
<?php ActiveForm::end(); ?>