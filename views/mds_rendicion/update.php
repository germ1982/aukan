<?php

use yii\widgets\ActiveForm;

$this->title = "Actualizar rendición #{$model->idrendicion}";

$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);


/*Se llama a la funcion js obtenerAdjuntos*/
$otrosAdjuntos = \yii\helpers\Json::encode($adjuntos);
$paramAdjunto = "let adjuntos_oficio = '$otrosAdjuntos'";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');

?>

<?php $form = ActiveForm::begin(['action' => ["mds_rendicion/update", 'id' => $model->idrendicion], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-rendicion-update">
    <!-- <h1><= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'username' => $username,
        'titleright' => "Actualizar rendición #{$model->idrendicion}",
        'listTipoDocumento' => $listTipoDocumento,
        'listUsuario' => $listUsuario,
        'listTipoRendicion' => $listTipoRendicion,
        'listCapa' => $listCapa,
        'listLugar' => $listLugar,
        // 'adjuntos' => $adjuntos,
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