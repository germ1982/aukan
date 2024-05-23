<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_odontologia */

$this->title = 'Crear registro odontológico';
$this->params['breadcrumbs'][] = ['label' => 'Odontología', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-acomp-asistencia-create">
    <?php $form = ActiveForm::begin(['id' => 'formOdontologia', 'action' => ['mds_odontologia/store'], 'options' => ['enctype' => 'multipart/form-data', 'form-odontologia']]); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'form' => $form,
        'username' => $username,
        'tiposGeneros' => $tiposGeneros,
        'tiposNacionalidades' => $tiposNacionalidades,
        'tiposDocumentos' => $tiposDocumentos,
        'tiposVisitas' => $tiposVisitas,
        'tiposEscolaridad' => $tiposEscolaridad,
        'tiposVacunasCovid' => $tiposVacunasCovid,
        'tiposIntervenciones' => $tiposIntervenciones,
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
