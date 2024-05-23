<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion */

$this->title = 'Crear Certificación';

?>

<?php $form = ActiveForm::begin(['id' => 'formCertificacion', 'action' => ['mds_certificacion/guardar_solicitud', 'area' => $area], 'options' => ['enctype' => 'multipart/form-data', 'form-certificacion']]); ?>

<div class="mds-certificacion-create">

    <!-- <h1><= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'form' => $form,
        'username' => $username,
        'model' => $model,
        'generos' => $generos,
        'nacionalidades' => $nacionalidades,
        'tiposDocumentos' => $tiposDocumentos,
        'CARACTER_VIA_RAPIDA' => $CARACTER_VIA_RAPIDA,
        'TIPO_JUBILACION_OTRO' => $TIPO_JUBILACION_OTRO,
        'PARENTESCO_OTRO_OPTION' => $PARENTESCO_OTRO_OPTION,
        'PARENTESCO_TITULAR' => $PARENTESCO_TITULAR,
        'localidades' => $localidades,
        'programas' => $programas,
        'caracteres' => $caracteres,
        'tipos_jubilacion' => $tipos_jubilacion,
        'organismo_solicitante' => $organismo_solicitante,
        'direcciones' => $direcciones,
        'niveles_autorizacion' => $niveles_autorizacion,
        'parentesco' => $parentesco,
        'tipo_responsable' => $tipo_responsable,
        'model_responsable' => $model_responsable,
        'model_certificacion_monto' => $model_certificacion_monto,
        'ID_NIVEL4' => $ID_NIVEL4,
        'cantNiveles' => $cantNiveles,
        'adjuntos' => $adjuntos,
        'ID_INCREMENTO' =>$ID_INCREMENTO,
        'token' => $token
    ]) ?>

</div>
<?php ActiveForm::end(); ?>
<?php

$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);

/*Se llama a la funcion js obtenerAdjuntos*/
$paramAdjunto = "let adjuntos =''; let adjuntos_oficio = ''";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');
