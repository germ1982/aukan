<?php

/* @var $this yii\web\View */
/* @var $model app\models\Mds_legales_oficio */
?>
<div class="mds-legales-respuesta-create">
    <?= $this->render('_form', [
        'model' => $model,
        'oficio' => $oficio,
        'derivacionOriginal' => $derivacionOriginal,
        'respuestaObservada' => $respuestaObservada,
        'listaProfesionales' => $listaProfesionales,
        'devoluciones' => $devoluciones,
    ]) ?>
</div>
<?php
$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);


$this->registerJsFile('@web/js/dropzone/mds_legales_respuesta/create.js', ['position' => \yii\web\View::POS_END]);
if ($respuestaObservada) {
    $adjuntos = \yii\helpers\Json::encode($respuestaObservada->getAdjuntos('dropzone'));
    $parametros = "var adjuntosRespuestaObservada = '$adjuntos'";
} else {
    $parametros = "var adjuntosRespuestaObservada = ''";
}

$this->registerJs($parametros, \yii\web\View::POS_END, 'obtenerAdjuntosRespuestaObservada');


?>