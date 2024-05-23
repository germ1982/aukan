<?php

use yii\widgets\ActiveForm;
use yii\web\ForbiddenHttpException;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion */

$this->title = "Actualizar certificación #{$model->idcertificacion}  de {$model->beneficiario->apellido} {$model->beneficiario->nombre}";

parse_str(Yii::$app->request->headers['referer'], $params);
if (array_key_exists('area', $params)) {
    $area = $params['area'];
} else {
    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
}
?>

<?php $form = ActiveForm::begin(['action' => ["mds_certificacion/update", 'id' => $model->idcertificacion, 'sector' => $area], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-certificacion-update">
    <!-- <h1><= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'form' => $form,
        'username' => $username,
        'model' => $model,
        'titleright' => "Actualizar certificación #{$model->idcertificacion}",
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
        'organismo_solicitante' => $organismo_solicitante,
        'direcciones' => $direcciones,
        'niveles_autorizacion' => $niveles_autorizacion,
        'tipos_jubilacion' => $tipos_jubilacion,
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
$otrosAdjuntos = \yii\helpers\Json::encode($model->getOtrosAdjuntos());
$paramAdjunto = "let adjuntos_oficio = '$otrosAdjuntos'";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');

?>