<?php

use yii\widgets\ActiveForm;

$this->title = "Actualizar registro #{$model->idcertificaciondireccion}";
?>
<?php $form = ActiveForm::begin(['action' => ["mds_certificacion_direccion/update", 'id' => $model->idcertificaciondireccion], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-legales-oficio-update">
    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        //'model_director' => $model_director,
        'listDirecciones' => $listDirecciones,
        'listDirectores' => $listDirectores,
        'listNivelAutorizacion' => $listNivelAutorizacion,
        'listFuncion' => $listFuncion
    ]) ?>
</div>
<?php ActiveForm::end(); ?>

