<?php

use app\models\Mds_seg_permiso;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cap_capacitacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Devolver requerimiento #{$derivacion->idlegalesoficio}";
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;
$permisos = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)")->all();

CrudAsset::register($this);

?>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['action' => ['mds_legales_oficio/rechazaroficiostore'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <input type="hidden" name="idlegalesderivacion" value="<?php echo $derivacion->idlegalesderivacion  ?>">
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($derivacion, 'observaciones')->label('Motivo devolución')->widget(\bizley\quill\Quill::class, [
                            'allowResize' => true,
                            'options' => [
                                'id' => 'texto_repuesta_id',
                                'style' => 'height: 150px;',
                            ],
                        ]) ?>
                    </div>
                </div>
                <label><strong>Documentos (adjuntar de a UN archivo a la vez)</strong></label>
                <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                <div>
                    <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </div>
                </div>
                <br>
                <a class="btn btn-info" href="index.php?r=mds_legales_oficio/index">Volver </a>
                <?= Html::submitButton("Devolver", ['class' => 'btn btn-success', 'id' => 'btnResponder']) ?>

                <?php ActiveForm::end(); ?>

            </div>
        </section>
    </div>
</div>

<?php
$this->registerJs(
    "
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    })

    $(document).ready(function() {  
        $('#btnResponder').click(function(e){
            const texto_repuesta =  $('#texto_repuesta_id').val();
            
            const parser = new DOMParser();
            const { textContent } = parser.parseFromString(texto_repuesta, 'text/html').documentElement;
            textoRespuestaSinHTML = textContent.trim();

            if (!texto_repuesta || texto_repuesta.length < 1 || !textoRespuestaSinHTML){
                alert('Debe completar el motivo');
                e.preventDefault();
            }
        })
    });"
);

Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]);
Modal::end();

$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);
//$this->registerJsFile('@web/js/dropzone/main.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);

$parametrosDos = "var adjuntos_oficio ='';";
$this->registerJs($parametrosDos, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');
?>