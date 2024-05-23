<?php
use yii\widgets\ActiveForm;

$this->title = "Actualizar Vacante #{$model->idvacante}";

?>

<?php $form = ActiveForm::begin(['id' => 'formConcursos', 'action' => ['mds_conc_vacante/update', 'id' => $model->idvacante], 'options' => ['enctype' => 'multipart/form-data', 'form-vacante']]); ?>
<div class="mds-conc-vacante-update">

    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'categoriaOptions' => $categoriaOptions,
        'concursoOptions' => $concursoOptions,
    ]) ?>

</div>
<?php ActiveForm::end(); ?>
