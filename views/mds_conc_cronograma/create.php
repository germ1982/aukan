<?php

use yii\widgets\ActiveForm;

$this->title = 'Crear nueva etapa';

?>

<?php $form = ActiveForm::begin(['id' => 'formConcursos', 'action' => ['mds_conc_cronograma/create'], 'options' => ['enctype' => 'multipart/form-data', 'form-etapa']]); ?>
<div class="mds-conc-etapa-create">

    <!-- <h1><= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'concursoOptions' => $concursoOptions,
    ]) ?>

</div>
<?php ActiveForm::end(); ?>