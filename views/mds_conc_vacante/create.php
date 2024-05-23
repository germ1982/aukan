<?php

use yii\widgets\ActiveForm;

$this->title = 'Crear nueva Vacante';

?>

<?php $form = ActiveForm::begin(['id' => 'formConcursos', 'action' => ['mds_conc_vacante/create'], 'options' => ['enctype' => 'multipart/form-data', 'form-vacante']]); ?>
<div class="mds-conc-vacante-create">

    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'categoriaOptions' => $categoriaOptions,
        'concursoOptions' => $concursoOptions,
    ]) ?>

</div>
<?php ActiveForm::end(); ?>