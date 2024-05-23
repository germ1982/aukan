<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Crear Intervención';
$this->params['breadcrumbs'][] = ['label' => 'Crear Intervención', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_intervencion */
$idllamada = null;
if (isset($_GET['idllamada'])) {
    $idllamada = $_GET['idllamada'];
}
$origen = null;
if (isset($_GET['origen'])) {
    $origen = $_GET['origen'];
}
?>
<div class="sds-vio-intervencion-create">

    <?php $form = ActiveForm::begin(['action' => ['sds_vio_intervencion/create', 'llamada' => $idllamada, 'origen' => $origen], 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $this->render('_form', [
        'action' => $action,
        'form' => $form,
        'model' => $model,
        'vioChecked' => $vioChecked,
        'vioFisicaSelectOptions' => $vioFisicaSelectOptions,
        'vioPsicologicaSelectOptions' => $vioPsicologicaSelectOptions,
        'vioSexualSelectOptions' => $vioSexualSelectOptions,
        'vioEconomicapatrimonialSelectOptions' => $vioEconomicapatrimonialSelectOptions,
        'vioSimbolicaSelectOptions' => $vioSimbolicaSelectOptions,
        'vioAmbientalSelectOptions' => $vioAmbientalSelectOptions,
        'vioNegligenciaAbandonoSelectOptions' => $vioNegligenciaAbandonoSelectOptions,
        'listProvincias' => $listProvincias,
        'listLocalidades' => $listLocalidades,
        'listLocalidadesOriunda' => $listLocalidadesOriunda,
        'listLocalidadesHecho' => $listLocalidadesHecho,
        'modelFrecuencia' => $modelFrecuencia,
        'vioFrecuenciaSelect' => $vioFrecuenciaSelect,
        'vioOcurrenciasSelect' => $vioOcurrenciasSelect,
        'sexoOptions' => $sexoOptions,
        'generoOptions' => $generoOptions,
        'nacionalidadOptions' => $nacionalidadOptions,
        'tipoIntervOptions' => $tipoIntervOptions,
        'derivacionOptions' => $derivacionOptions,
        'modalidadOptions' => $modalidadOptions
    ]) ?>
    <?php ActiveForm::end(); ?>

</div>