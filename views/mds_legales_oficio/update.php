<?php

use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_legales_oficio*/

$this->title = "Actualizar requerimiento #{$model->idlegalesoficio}";
?>
<?php $form = ActiveForm::begin(['action' => ["mds_legales_oficio/update", 'id' => $model->idlegalesoficio], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-legales-oficio-update">

    <?= $this->render('_form', [
        'model' => $model,
        'form' => $form,
        'action' => $action,
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
//$this->registerJsFile('@web/js/dropzone/main.js', ['position' => \yii\web\View::POS_END,'var max = "'. $model['idlegalesoficio'].'"']);
//$archivo_oficio = json_encode(['nombre_adjunto'=>$model->archivo_oficio_nombre,'adjunto'=>$model->archivo_oficio]);
$archivo_oficio = \yii\helpers\Json::encode($model->getAdjuntosByTipo('oficio'));
$otrosAdjuntosOficio = \yii\helpers\Json::encode($model->getAdjuntosByTipo('otros'));
$parametros = "var archivo_oficio = '$archivo_oficio'";
$parametrosDos = "var adjuntos_oficio ='$otrosAdjuntosOficio';";

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);
/*Se llama a la funcion js obtenerAdjuntosOficio*/
$this->registerJs($parametros, \yii\web\View::POS_END, 'obtenerAdjuntoOficio');
$this->registerJs($parametrosDos, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');

?>