<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_agresor */

$this->title = "Ver persona #{$model->idlegalesoficiovinculado} vinculada al requerimiento #{$model->idlegalesoficio}";
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;

CrudAsset::register($this);

?>

<style>
    .table>thead>tr>td.info,
    .table>tbody>tr>td.info,
    .table>tfoot>tr>td.info,
    .table>thead>tr>th.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>tbody>tr.info>td,
    .table>tfoot>tr.info>td,
    .table>thead>tr.info>th,
    .table>tbody>tr.info>th,
    .table>tfoot>tr.info>th {
        color: #777;
        background-color: #fafafa !important;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
    }

    .btnPrint {
        background: grey !important;
    }

    .btnPrint a {
        color: white !important;
        text-decoration: none !important;
    }

    .alert-detalle {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
        max-height: 300px;
        overflow-y: auto;
    }
</style>

<?php if (!Yii::$app->request->isAjax) : ?>
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

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>
<?php endif ?>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?php if ($model->idpersona || $model->documento) : ?>
                    <div class="row form-group">
                        <div class="col-md-6 col-sm-12 form-group">
                            <label class="form-label">Tipo de documento</label>
                            <?php
                            $tipoDocumento = "";
                            if ($model->idtipodocumento || $model->idpersona) {
                                $tipoDocumento = $model->idtipodocumento ? $model->tipoDocumento->descripcion : ($model->idpersona ? $model->persona->documentoTipo->descripcion : '');
                                $tipoDocumentoPointStart = strpos($tipoDocumento, ".") ? strpos($tipoDocumento, ".") + 1 : 0;
                                $tipoDocumento = substr($tipoDocumento, $tipoDocumentoPointStart);
                            }
                            ?>
                            <input type="text" class="form-control" value="<?= $tipoDocumento ? $tipoDocumento : "" ?>" readonly>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="form-label">Nro. de documento</label>
                            <input type="text" class="form-control" value="<?= $model->idpersona ? $model->persona->documento : $model->documento ?>" readonly>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row form-group">
                    <div class="col-md-12 col-sm-12">
                        <label class="form-label">Parentesco</label>
                        <?php
                        if ($model->parentesco) {
                            $parentesco = $model->parentesco->descripcion;
                            $parentescoPointStart = strpos($parentesco, ".") ? strpos($parentesco, ".") + 1 : 0;
                            $parentesco = substr($parentesco, $parentescoPointStart);
                        }
                        ?>
                        <input type="text" class="form-control" value="<?= $model->parentesco ? $parentesco : "" ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6 col-sm-12 form-group">
                        <label class="form-label">Apellido</label>
                        <input type="text" class="form-control" value="<?= $model->idpersona ? $model->persona->apellido : $model->apellido ?>" readonly>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" value="<?= $model->idpersona ? $model->persona->nombre : $model->nombre ?>" readonly>
                    </div>
                </div>
                <?php if ($model->idpersona) : ?>
                    <div class="row form-group">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="form-label">Género</label>
                            <input type="text" class="form-control" value="<?= $model->persona->genero0 ? $model->persona->genero0->descripcion : '' ?>" readonly>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="form-label">Nacionalidad</label>
                            <input type="text" class="form-control" value="<?= $model->persona->nacionalidad0 ? $model->persona->nacionalidad0->descripcion : '' ?>" readonly>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="form-label">Fecha Nacimiento</label>
                            <input type="text" class="form-control" value="<?= $model->persona->fecha_nacimiento ? date_format(date_create($model->persona->fecha_nacimiento), 'd/m/Y') : '' ?>" readonly>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row form-group">
                    <div class="col-md-6 col-sm-12 form-group">
                        <label class="form-label">Domicilio Calle</label>
                        <input type="text" class="form-control" value="<?= $model->idpersona ? $model->persona->domicilio_calle : $model->domicilio_calle ?>" readonly>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label">Domicilio Número</label>
                        <input type="text" class="form-control" value="<?= $model->idpersona ? $model->persona->domicilio_numero : $model->domicilio_numero ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6 col-sm-12 form-group">
                        <label class="form-label">Mail</label>
                        <input type="text" class="form-control" value="<?= $model->mail ?>" readonly>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" value="<?= $model->telefono ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label class="control-label">Observaciones</label>
                        <div class="alert alert-detalle" role="alert">
                            <p><?= $model->observaciones;  ?></p>
                        </div>
                    </div>
                </div>

                <?php if (!Yii::$app->request->isAjax) : ?>
                    <br>
                    <div class="card-footer" id="botones">
                        <a class="btn btn-info" href="index.php?r=sds_vio_agresor/index">Volver </a>
                    </div>
                <?php endif ?>
            </div>
        </section>
    </div>
</div>