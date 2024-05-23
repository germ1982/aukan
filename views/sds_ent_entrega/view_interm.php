<?php

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_entrega */

use app\models\Mds_org_informe;
use app\models\Mds_org_organismo_externo;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_ent_entrega;
use app\models\Sds_ent_tipo;
use yii\bootstrap\Collapse;
use yii\jui\Accordion;

$this->title = "Consulta de Entrega";
if (!Yii::$app->request->isAjax) :
?>
    <header class="page-header">
        <h2><?= $this->title ?></h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span><?= $this->title ?></span></li>
            </ol>

            <div class="sidebar-right-toggle"></div>
        </div>
    </header>
<?php endif; ?>

<!-- start: page -->
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Receptor: <?= Sds_com_configuracion::findOne($model->receptor)->descripcion ?></h2>
            </header>
            <div class="panel-body">
                <div class="row">
                    <?php
                    //Trampita para que anden los accordion del template con yii ;)
                    echo Collapse::widget([]); ?>
                    <div class="col-md-12">
                        <div class="panel-group" id="accordion_entrega">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#entrega">
                                            Datos Entrega
                                        </a>
                                    </h4>
                                </div>
                                <div id="entrega" class="accordion-body collapse in">
                                    <div class="panel-body">
                                        <?php
                                        $usuario = Mds_seg_usuario::findOne($model->idusuario);
                                        ?>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <?= "<b>Cantidad: </b>" . $model->cantidad; ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= "<b>Tipo: </b>" . Sds_ent_tipo::findOne($model->idtipo)->descripcion; ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <?= "<b>Usuario: </b>" . $usuario->user; ?>
                                                </div>
                                                <div class="col-md-5">
                                                    <?php
                                                    $emisor = "";
                                                    if ($model->emisor != null) {
                                                        $entrega = Sds_ent_entrega::findOne($model->emisor);
                                                        $fc = date_create($entrega->fecha_hora);
                                                        $fc = date_format($fc, 'd/m/Y H:i');
                                                        $receptor = Sds_com_configuracion::findOne($entrega->receptor);
                                                        $emisor = $fc . ' - ' . $receptor->descripcion;
                                                    } else {
                                                        $emisor = "Primer Ingreso";
                                                    }
                                                    echo "<b>Emisor: </b>" .  $emisor;
                                                    ?>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <?= $model->proveedor != null ? "<b>Proveedor: </b>" .  Sds_com_configuracion::findOne($model->proveedor)->descripcion : ""; ?>
                                                </div>
                                                <div class="col-md-7">
                                                    <?= $model->oc != null ? "<b>N° Orden Compra: </b>" .  $model->oc : ""; ?>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= "<b>Detalle: </b>" . nl2br($model->observaciones) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div style="display:<?= $model->acta ? "block" : "none" ?>">
                            <div class="panel-group" id="accordion_fotos_dni">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#fotos_dni">
                                                Archivo de Acta
                                            </a>
                                        </h4>
                                    </div>
                                    <?php if (Mds_org_informe::getExtension($model->acta) != 'image') : ?>
                                        <div class="row">
                                            <div class='col-md-12' style="padding: 1rem;text-align:center;">
                                                <object width="80%" height="600px" type="application/pdf" data="<?php echo $model->acta; ?>">
                                                    <p>Archivo Adjunto no disponible.</p>
                                                </object>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="row">
                                            <div class='col-md-12' style="padding: 1rem">
                                                <img id="acta" src="<?= $model->acta ?>" alt="" height="200px" />
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!Yii::$app->request->isAjax) : ?>
                    <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>