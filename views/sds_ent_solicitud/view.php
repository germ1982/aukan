<?php

use app\models\Mds_seg_usuario;
use app\models\Sds_ent_solicitud;
use app\models\Sds_ent_tipo;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_solicitud */
?>
<div class="sds-ent-solicitud-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'fecha_hora',
                'value' => function ($model) {
                    if ($model->fecha_hora != null) {
                        $fc = date_create($model->fecha_hora);
                        $fc = date_format($fc, 'd/m/Y H:i');
                        return $fc;
                    }
                    return "";
                },
            ],
            'cantidad',
            'dni',
            [
                'attribute' => 'idtipo',
                'value' => function ($model) {
                    $tipo = $model->idtipo;
                    return Sds_ent_tipo::findOne($tipo)->descripcion;
                },
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'idusuario',
                'value' => function ($model) {
                    $usuario = $model->idusuario;
                    if ($usuario != null) {
                        $user = Mds_seg_usuario::findOne($usuario);
                        return $user->user;
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'estado',
                'value' => function ($model) {
                    switch ($model->estado) {
                        case Sds_ent_solicitud::ESTADO_PENDIENTE:
                            return "Pendiente";
                        case Sds_ent_solicitud::ESTADO_APROBADO:
                            return "Aprobada";
                        case Sds_ent_solicitud::ESTADO_DESAPROBADO:
                            return "Desaprobada";
                        case Sds_ent_solicitud::ESTADO_ENTREGADO:
                            return "Entregada";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'observaciones', 'format' => 'html',
            ],
            [
                'attribute' => 'fecha_aprobacion',
                'value' => function ($model) {
                    if ($model->fecha_aprobacion != null) {
                        $fc = date_create($model->fecha_aprobacion);
                        $fc = date_format($fc, 'd/m/Y');
                        return $fc;
                    }
                    return "";
                },
            ],
        ],
    ]) ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion_renaper">
                <div class="panel panel-accordion">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#renaper">
                                Registro Renaper
                            </a>
                        </h4>
                    </div>
                    <div id="renaper" class="accordion-body collapse in">
                        <div class="panel-body" id="renaper_content">
                            <div class="row">
                                <!-- <div class="col-md-3" style="text-align: center;">
                                    <img id="renaper_foto" src="" alt="" height="100px" />
                                </div> -->
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12" id="renaper_nombre"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="renaper_apellido"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="renaper_domicilio"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="renaper_localidad"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(
    "$(document).ready(function() {        
        datos_renaper(true);
    });"
);
?>
<script>
    function datos_renaper(primera_vez = false) {
        var dni = <?php echo isset($model->dni) ? $model->dni : 0 ?>;
        if (dni > 0) {
            $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
                if (data.status != "error") {
                    var nombre = "";
                    var apellido = "";
                    var domicilio = "";
                    var localidad = "";
                    var foto = "";
                    $.each(data, function(ind, elem) {
                        console.log(ind);
                        if (ind == 'records') {
                            console.log(elem[0]);
                            nombre = elem[0].result.nombres;
                            apellido = elem[0].result.apellido;
                            domicilio = elem[0].result.calle + " " + elem[0].result.numero;
                            localidad = elem[0].result.ciudad;
                            //foto = elem[0].result.foto;
                        }
                    });

                    $("#renaper_nombre").html("<b>Nombre: </b>" + nombre);
                    $("#renaper_apellido").html("<b>Apellido: </b>" + apellido);
                    $("#renaper_domicilio").html("<b>Domicilio: </b>" + domicilio);
                    $("#renaper_localidad").html("<b>Localidad: </b>" + localidad.replace("ï¿½", "É").replace(/_/g, " "));
                   /*  $("#renaper_foto").attr("src", foto); */
                }
            });
        }

    }
</script>