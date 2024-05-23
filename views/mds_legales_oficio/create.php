<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_legales_oficio */

$this->title = 'Nuevo Requerimiento';
?>
<?php $form = ActiveForm::begin(['id' => 'formOficio', 'action' => ['mds_legales_oficio/store'], 'options' => ['enctype' => 'multipart/form-data', 'form-oficio']]); ?>

<div class="mds-legales-oficio-create">
    <?= $this->render('_form', [
        'model' => $model,
        'action' => $action,
        'form' => $form,
        'listParentesco' => $listParentesco,
        'listTipoDocumento' => $listTipoDocumento,
        'tipoGenero' => $tipoGenero,
        'tipoNacionalidad' => $tipoNacionalidad,
        'supervisoresByArea' => $supervisoresByArea,
        'ultimoRequerimientoByCaratula' => $ultimoRequerimientoByCaratula,
        'requerimientosCaratulaSeleccionada' => $requerimientosCaratulaSeleccionada,
        // 'urlReturn' => $urlReturn
    ]) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);
//$this->registerJsFile('@web/js/dropzone/main.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);
/*Se llama a la funcion js obtenerAdjuntosOficio*/
$parametros = "var archivo_oficio = ''";
$parametrosDos = "var adjuntos_oficio ='';";
$this->registerJs($parametros, \yii\web\View::POS_END, 'obtenerAdjuntoOficio');
$this->registerJs($parametrosDos, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');
?>