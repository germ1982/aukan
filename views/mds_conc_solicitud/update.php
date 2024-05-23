<?php

use yii\widgets\ActiveForm;

$this->title = "Actualizar Solicitud #{$model->idsolicitud}";

?>

<?php $form = ActiveForm::begin(['id' => 'formConcursos', 'action' => ['mds_conc_solicitud/update', 'id' => $model->idsolicitud], 'options' => ['enctype' => 'multipart/form-data', 'form-solicitud']]); ?>
<div class="mds-conc-solicitud-update">

    <!-- <h1><= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'initialPreview' => $initialPreview,
        'tituloRequerido' => $tituloRequerido,
        'concursoOptions' => $concursoOptions,
    ]) ?>

</div>
<?php ActiveForm::end(); ?>
