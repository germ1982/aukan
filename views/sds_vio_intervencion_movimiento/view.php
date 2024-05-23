<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;


$this->title = "Ver Movimiento #{$model->idmovimiento}";
$this->params['breadcrumbs'][] = $this->title;

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
</style>

<?php $form = ActiveForm::begin(); ?>

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
<?php endif ?>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">

                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Movimiento</label>
                        <input type="text" class="form-control" value="<?php echo $movimiento ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha</label>
                        <input type="text" class="form-control" value="<?php echo $fecha ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <?= $form->field($model, 'profesionales_intervinientes')->textInput(['rows' => 6, 'readOnly' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'detalle')->textarea(['rows' => 6, 'readOnly' => true]) ?>
                        <!-- <label class="form-label">Detalle</label> -->
                        <!-- <textarea class="form-control" style="height: 131px;" readonly><php echo $model->detalle ?></textarea> -->
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="form-label">Usuario de alta</label>
                        <input type="text" class="form-control" value="<?php echo $username ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha Alta</label>
                        <input type="text" class="form-control" value="<?php echo $fecha_alta ?>" readonly>
                    </div>
                </div>

                <?php if (!Yii::$app->request->isAjax) : ?>
                    <div class="card-footer" id="botones">
                        <a class="btn btn-info" href="index.php?r=sds_vio_intervencion_movimiento/index">Volver </a>
                    </div>
                <?php endif ?>

            </div>
        </section>
    </div>
</div>

<?php ActiveForm::end(); ?>