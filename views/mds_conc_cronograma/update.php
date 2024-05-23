<?php

use yii\widgets\ActiveForm;

$this->title = "Actualizar Etapa #{$model->idetapa}";

?>

<?php $form = ActiveForm::begin(['id' => 'formConcursos', 'action' => ['mds_conc_cronograma/update', 'id' => $model->idetapa], 'options' => ['enctype' => 'multipart/form-data', 'form-etapa']]); ?>
<div class="mds-conc-etapa-update">

    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'concursoOptions' => $concursoOptions,
    ]) ?>

</div>
<?php ActiveForm::end(); ?>
