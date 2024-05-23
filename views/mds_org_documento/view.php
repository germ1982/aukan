<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_documento;
use app\models\Sds_com_configuracion;
use yii\helpers\Html;
use app\models\Sds_com_persona;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_documento */
?>


<style>
    #base64image {
        display: block;
        border: ridge 1px;
        padding: 8px;
        border-color: #E6E6E6;
        max-width: 40%;
    }

    .campo {
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<div class="mds-org-documento-view">

    <div class="row">
        <div class="col-md-6">

            <div class="col-md-12">
                <h5><b>Fecha de carga: </b></h5>
                <p class="campo">
                    <?php echo date_format(date_create($model->fecha), 'd/m/Y') ?>
                </p>
            </div>

            <div class="col-md-12">
                <h5><b>Usuario: </b></h5>
                <p class="campo">
                    <?php echo $model->idusuario0->nombre . ' ' . $model->idusuario0->apellido ?>
                </p>
            </div>

            <div class="col-md-12">
                <h5><b>Tipo</b></h5>
                <p class="campo">
                    <?php echo $model->tipo0->descripcion ?></p>
            </div>

            <div class="col-md-12">
                <h5><b>Nombre</b></h5>
                <p class="campo"><?php echo $model->nombre ?></p>
            </div>
            <div class="col-md-12">
                <h5><b>Persona</b></h5>
                <p class="campo">
                    <?php
                    $contacto = Mds_org_contacto::findOne($model->idcontacto);
                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                    echo "$persona->apellido, $persona->nombre";
                    ?></p>
            </div>

        </div>

        <div class="col-md-6">
            <?php
            if ($model->path) {
                echo "<h5><b>Archivo Adjunto:</b></h5>";
                $contacto  = Mds_org_contacto::findOne($model->idcontacto);
                $persona = Sds_com_persona::findOne($contacto->idpersona);
                $ruta = '@web/' . $model->path;

                if (Mds_org_documento::getExtension($model->path) != 'image') {
                    echo Html::a($model->path, Url::to($ruta, true), ['target' => '_blank']);
                } else {
                    echo Html::img($ruta, ['alt' => $ruta, 'id' => 'base64image']);
                    if ($model->path) {
                        echo Html::a("Abrir Archivo Adjunto", Url::to($ruta, true), ['target' => '_blank', 'data-pjax' => "0", 'class' => 'btn btn-success', 'style' => 'width:80%']);
                    }
                }
            }
            ?>
        </div>

    </div>
    
    <?php if($model->medicina == 1) : ?>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-12">
                <h5><b>Estado</b></h5>
                <?php $estado = Sds_com_configuracion::findOne($model->estado);
                    if($estado!=null){
                        $estado = $estado->descripcion;
                    } ?>
                <p class="campo"><?php echo $estado ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <h5><b>Detalle</b></h5>
                <p class="campo"><?php echo $model->detalle ?></p>
            </div>
        </div>
    </div>

    <?php
