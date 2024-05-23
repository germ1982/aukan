<?php

use yii\widgets\ActiveForm;

$this->title = "Actualizar usuario #{$model->idcertificaciondirector}";
?>

<?php $form = ActiveForm::begin(['action' => ["mds_certificacion_director/update", 'id' => $model->idcertificaciondirector], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-certificacion_director-update">
    <?= $this->render('_form', [
        'model' => $model,
        'listUsuarios' => $listUsuarios,
        'listFunciones' => $listFunciones
    ]) ?>
</div>
<?php ActiveForm::end(); ?>