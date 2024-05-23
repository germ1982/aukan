<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_gerontologia */

$this->title = "Actualizar registro de: {$model->persona->apellido} {$model->persona->nombre} ({$model->persona->documento})";

?>
<?php $form = ActiveForm::begin(['action' => ["mds_gerontologia/update", 'id' => $model->idgerontologia], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-legales-oficio-update">
    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'model_evaluacion' => $model_evaluacion,
        'nacionalidades' => $nacionalidades,
        'tiposDocumentos' => $tiposDocumentos,
        'username' => $username,
        'generos' => $generos,
        'escolaridad' => $escolaridad,
        'obrasocial' => $obrasocial,
        'estadocivil' => $estadocivil,
        'vivienda' => $vivienda,
        'vacunacovid19' => $vacunacovid19,
        'abvdLavadoSelect' => $abvdLavadoSelect,
        'abvdLavadoSelectOptions' => $abvdLavadoSelectOptions,
        'abvdVestidoSelect' => $abvdVestidoSelect,
        'abvdVestidoSelectOptions' => $abvdVestidoSelectOptions,
        'abvdBanioSelect' => $abvdBanioSelect,
        'abvdBanioSelectOptions' => $abvdBanioSelectOptions,
        'abvdMovilizacionSelect' => $abvdMovilizacionSelect,
        'abvdMovilizacionSelectOptions' => $abvdMovilizacionSelectOptions,
        'abvdContinenciaSelect' => $abvdContinenciaSelect,
        'abvdContinenciaSelectOptions' => $abvdContinenciaSelectOptions,
        'abvdAlimentacionSelect' => $abvdAlimentacionSelect,
        'abvdAlimentacionSelectOptions' => $abvdAlimentacionSelectOptions,
        'aivdCapacidadTelefonoSelect' => $aivdCapacidadTelefonoSelect,
        'aivdCapacidadTelefonoSelectOptions' => $aivdCapacidadTelefonoSelectOptions,
        'aivdComprasSelect' => $aivdComprasSelect,
        'aivdComprasSelectOptions' => $aivdComprasSelectOptions,
        'aivdPreparacionComidaSelect' => $aivdPreparacionComidaSelect,
        'aivdPreparacioncomidaSelectOptions' => $aivdPreparacionComidaSelectOptions,
        'aivdCuidadoCasaSelect' => $aivdCuidadoCasaSelect,
        'aivdCuidadoCasaSelectOptions' => $aivdCuidadoCasaSelectOptions,
        'aivdLavadoRopaSelect' => $aivdLavadoRopaSelect,
        'aivdLavadoRopaSelectOptions' => $aivdLavadoRopaSelectOptions,
        'aivdUsoTransporteSelect' => $aivdUsoTransporteSelect,
        'aivdUsoTransporteSelectOptions' => $aivdUsoTransporteSelectOptions,
        'aivdResponsabilidadMedicacionSelect' => $aivdResponsabilidadMedicacionSelect,
        'aivdResponsabilidadMedicacionSelectOptions' => $aivdResponsabilidadMedicacionSelectOptions,
        'aivdManejoAsuntosEconomicosSelect' => $aivdManejoAsuntosEconomicosSelect,
        'aivdManejoAsuntosEconomicosSelectOptions' => $aivdManejoAsuntosEconomicosSelectOptions,
        'situacionFamiliarSelect' => $situacionFamiliarSelect,
        'situacionFamiliarSelectOptions' => $situacionFamiliarSelectOptions,
        'relacionesSocialesSelect' => $relacionesSocialesSelect,
        'relacionesSocialesSelectOptions' => $relacionesSocialesSelectOptions,
        'redSocialSelect' => $redSocialSelect,
        'redSocialSelectOptions' => $redSocialSelectOptions,
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
$adjuntos = \yii\helpers\Json::encode($model->getAdjuntos());

// Se llama a la funcion js obtenerAdjuntos
$paramAdjunto = "let adjuntos =''; let adjuntos_oficio = '$adjuntos'";
// $parametrosDos = "var adjuntos_oficio ='$adjuntos';";

$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerAdjuntos');
