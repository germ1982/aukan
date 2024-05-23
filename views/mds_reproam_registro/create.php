<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_reproam_registro */

$this->title = 'Crear ReProAM Registro';
$this->params['breadcrumbs'][] = ['label' => 'Mds Reproam Registros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-reproam-registro-create">

    <?php $form = ActiveForm::begin(['id' => 'formReproamRegistro', 'action' => ['mds_reproam_registro/store'], 'options' => ['enctype' => 'multipart/form-data', 'form-reproam-registro']]); ?>

    <?= $this->render('_form', [
        'action' => $action,
        'model' => $model,
        'form' => $form,
        'ID_LOCALIDAD_NEUQUEN_CAPITAL' => $ID_LOCALIDAD_NEUQUEN_CAPITAL,
        'listaZonas' => $listaZonas,
        'localidades' => $localidades,
        'puedeEliminar' => $puedeEliminar,
        'listaSituacionHabitacional' => $listaSituacionHabitacional,
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