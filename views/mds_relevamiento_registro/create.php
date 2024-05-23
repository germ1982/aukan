<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Nuevo registro';
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
            <li><span>Nuevo registro</span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="mds-relevamiento-registro-create">

    <?php $form = ActiveForm::begin(['id' => 'formRelevamientoRegistro', 'action' => ['mds_relevamiento_registro/create'], 'options' => ['enctype' => 'multipart/form-data', 'form-relevamiento-registro']]); ?>

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
$paramAdjunto = "let adjuntos =''; let adjuntos_oficio = ''";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');
