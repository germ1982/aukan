<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion */

$this->title = "Actualizar Registro #{$model->idregistro}";

?>
<?php $form = ActiveForm::begin(['action' => ["mds_reproam_registro/update", 'id' => $model->idregistro], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-legales-oficio-update">
    <?= $this->render('_form', [
        'action' => $action,
        'barrios' => $barrios,
        'form' => $form,
        'model' => $model,
        'listaZonas' => $listaZonas,
        'localidades' => $localidades,
        'puedeEliminar' => $puedeEliminar,
        'listaSituacionHabitacional' => $listaSituacionHabitacional,
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
