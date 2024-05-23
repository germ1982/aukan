<?php

use yii\widgets\ActiveForm;

$this->title = "Actualizar registro #{$model->idcertificacionprogramamonto}";
?>
<?php $form = ActiveForm::begin(['action' => ["mds_certificacion_programa_monto/update", 'id' => $model->idcertificacionprogramamonto], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-certificacion-programa-monto-update">
    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'listDirecciones'=> $listDirecciones,
        'listProgramas'=> $listProgramas
    ]) ?>
</div>
<?php ActiveForm::end(); ?>

