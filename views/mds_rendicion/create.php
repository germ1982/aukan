<?php

use yii\widgets\ActiveForm;

$this->title = 'Crear Rendición';

$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);

/*Se llama a la funcion js obtenerAdjuntos*/
$paramAdjunto = "let adjuntos_oficio = ''";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');

?>

<?php $form = ActiveForm::begin(['id' => 'formRendicion', 'action' => ['mds_rendicion/create'], 'options' => ['enctype' => 'multipart/form-data', 'form-rendicion']]); ?>
<div class="mds-rendicion-create">

    <!-- <h1><= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'username' => $username,
        'listUsuario' => $listUsuario,
        'listTipoRendicion' => $listTipoRendicion,
        'listCapa' => $listCapa,
        'listLugar' => $listLugar,
        'TIPO_COMBUSTIBLE' => $TIPO_COMBUSTIBLE,
        'TIPO_AUH' => $TIPO_AUH,
        'TIPO_ALIMENTAR' => $TIPO_ALIMENTAR,
        'model_persona' => $model_persona,
        'tiposDocumentos'  => $tiposDocumentos,
        'generos'  => $generos,
        'nacionalidades' => $nacionalidades,
        'token' => $token
    ]) ?>

</div>
<?php ActiveForm::end(); ?>