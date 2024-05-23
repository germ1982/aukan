<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cap_capacitacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Respuesta a requerimiento #' . $oficio->idlegalesoficio;
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;

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
                <?php $form = ActiveForm::begin(['action' => ['mds_legales_oficio/storerespuesta'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <input type="hidden" name="idlegalesoficio" value="<?php echo $oficio->idlegalesoficio  ?>">
                <input type="hidden" name="idrespuestacorreccion" value="<?php echo  Yii::$app->getRequest()->getQueryParam('idrespuesta')  ?>">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Entidad requirente</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->lugar_libramiento ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Localidad</label>
                        <input type="text" class="form-control" id="donde_se_tramita" value="<?php echo $oficio->donde_tramita ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Carátula</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caratula ?>" readonly>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Plazo (días)</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->tiempo_respuesta ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dni de personas vinculadas</label>
                        <textarea class="form-control" rows="3" readonly><?php echo $oficio->dni_legajo_vinculado ?></textarea>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Nro expediente</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->numero_expediente ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Año expediente</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->anio_expediente ?>" readonly>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Caso</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caso ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Providencia</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->providencia ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo de requerimiento</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->tipo_oficio ?>" readonly>
                    </div>
                </div>
                <br>
                <?php
                $oficioAdjunto = $oficio->getAdjuntosByTipo('oficio');

                if ($oficioAdjunto) { ?>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Archivo adjunto de requerimiento</label>
                            <ul style="list-style: none">
                                <li><a><i class="fas fa-paperclip"></i><?= Html::a($oficioAdjunto[0]->nombre, Url::base() . "/" . $oficioAdjunto[0]->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                            </ul>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Respuesta</label>
                        <div class="form-group">
                            <textarea name="texto_respuesta" rows="8" class="form-control"><?php echo ($respuestaObervada != null) ? $respuestaObervada->texto_repuesta : ''   ?></textarea>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <input type="file" name="adjuntos" multiple>
                    </div>
                </div>
                <br>

                <?= Html::submitButton("Responder", ['class' => 'btn btn-success']) ?>

                <?php ActiveForm::end(); ?>

            </div>
        </section>
    </div>
</div>

<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })"
);
?>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>