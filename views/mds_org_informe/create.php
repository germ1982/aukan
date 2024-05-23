<?php



/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_informe */
use app\models\Mds_org_informe;

?>
<div class="mds-org-informe-create">
    <?= $this->render('_form', [
        'model' => $model,
        'usuarios' => $usuarios,
        'tiposInforme' => $tiposInforme,
        'organismos' => $organismos,
        'compartidos' => [],
        'cantMaxCompartidos' => Mds_org_informe::CANT_MAX_COMPARTIDOS,
        'urlAnterior' => $urlAnterior
    ]) ?>
</div>

<?php
$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);


$this->registerJsFile('@web/js/dropzone/mds_legales_respuesta/create.js', ['position' => \yii\web\View::POS_END]);

$adjuntos = \yii\helpers\Json::encode($model->getAdjuntos());
$parametros = "var adjuntosRespuestaObservada = '$adjuntos'";


$this->registerJs($parametros, \yii\web\View::POS_END, 'obtenerAdjuntosRespuestaObservada');


?>