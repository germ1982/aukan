<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_gerontologia */

$this->title = 'Crear registro de gerontología';
$this->params['breadcrumbs'][] = ['label' => 'Mds gerontologia', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-gerontologia-create">

    <?php $form = ActiveForm::begin(['id' => 'formGerontologia', 'action' => ['mds_gerontologia/store'], 'options' => ['enctype' => 'multipart/form-data', 'form-gerontologia']]); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'model_evaluacion' => $model_evaluacion,
        'form' => $form,
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
    <?php ActiveForm::end(); ?>
</div>
<?php

$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);

/*Se llama a la funcion js obtenerAdjuntos*/
$paramAdjunto = "let adjuntos =''; let adjuntos_oficio = ''";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerAdjuntos');
