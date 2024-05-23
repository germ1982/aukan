<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_relevamiento_registro */

$this->title = 'Actualizar relevamiento #' . $model->idrelevamientoregistro . ' - ' . $model->capaitem->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Mds Relevamiento Registros', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idrelevamientoregistro, 'url' => ['view', 'id' => $model->idrelevamientoregistro]];
$this->params['breadcrumbs'][] = 'Update';
?>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Relevamiento #<?= $model->idrelevamientoregistro ?></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="mds-relevamiento-registro-update">
    <?php $form = ActiveForm::begin(['action' => ["mds_relevamiento_registro/update", 'id' => $model->idrelevamientoregistro], 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $this->render('_form', [
        'model' => $model,
        'model_respuesta' => $model_respuesta,
        'form' => $form,
        'agrupadores' => $agrupadores,
        'edificiosFilter' => $edificiosFilter
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
$otrosAdjuntos = \yii\helpers\Json::encode($model->getOtrosAdjuntos());
$paramAdjunto = "let adjuntos_oficio = '$otrosAdjuntos'";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');

?>