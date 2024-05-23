<?php

use app\models\Sds_his_registro_familia;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
?>

<style>
    #accordion-historico .panel-heading h4 a {
        color: #fff;
        text-decoration: none;
    }

    #accordion-historico .panel-heading h4 a:hover {
        text-decoration: none;
        color: rgba(100, 50, 50, 0.7);
    }

    #accordion-historico .panel-heading h4 a:visited {
        color: red;
    }

    .avatar-renaper {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        flex-shrink: 0;
        -o-object-fit: cover;
        object-fit: cover;
    }

    #crud-datatable-entrega .panel-heading {
        width: 40%;
        float: left;
        padding: 0;
    }

    #crud-datatable-subsidio .panel-heading {
        width: 40%;
        float: left;
        padding: 0;
    }

    #crud-datatable-entrega .panel-heading .pull-right {
        float: none !important;
    }

    #crud-datatable-subsidio .panel-heading .pull-right {
        float: none !important;
    }

    .kv-panel-before {
        width: 100%;
    }
</style>
<?php $this->title = 'Cruce Historico de Datos MDS'; ?>
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

<?php if ($dni != null): ?>
    <div class="panel-heading" style="padding-bottom: 0 !important;">
        <div class="row">
            <!-- Input Buscar DNI -->
            <div class="col-md-4">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <?php echo Html::a(
                                '<i class="glyphicon glyphicon-search"></i>',
                                null,
                                [
                                    'name' => 'btn_dni',
                                    'data-request-method' => 'post',
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-primary',
                                    'title' => Yii::t(
                                        'app',
                                        'Consultar Historico de DNI'
                                    ),
                                    'onclick' =>
                                        'location.href ="index.php?r=site/cruce_historico&dni=' .
                                        '"+$("#txtDniHistorico").val()',
                                ]
                            ); ?>
                        </span>
                        <input type="text" class="form-control" id="txtDniHistorico" placeholder="DNI..." onchange='location.href ="index.php?r=site/cruce_historico&dni="+$("#txtDniHistorico").val()' value=<?= isset(
                            $dni
                        )
                            ? $dni
                            : '' ?>>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11" style="background-color: #eee; margin: 15px 0 0 15px; border: 1px solid #ccc; border-radius: 5px; padding:5px 0; text-align:center;">
                        <?php $legajo = Sds_his_registro_familia::find()
                            ->where('dni=' . $dni)
                            ->one(); ?>
                        <h4><span class="label label-primary">Legajo Registro de Familia: <?= isset(
                            $legajo->legajo
                        )
                            ? ' <b>' . $legajo->legajo . '</b>'
                            : 'S/D' ?></span></h4>
                    </div>
                </div>
                <br>
                <div class="col-md-12" style="border: 1px solid #ccc; border-radius: 5px; padding:5px 5px 15px 5px; margin-bottom: 15px;">
                    <h4 style="text-align:center;">Imprimir Reporte</h4>
                    <?= Html::beginForm(
                        Url::to(['/site/reporte_historico', 'dni' => $dni]),
                        'post',
                        ['target' => '_blank'],
                        ['enctype' => 'multipart/form-data']
                    ) ?>
                    <?= Html::hiddenInput('post-img-renaper', '', [
                        'id' => 'post-img-renaper',
                    ]) ?>
                    <?= Html::hiddenInput('post-nombre-renaper', '', [
                        'id' => 'post-nombre-renaper',
                    ]) ?>
                    <?= Html::hiddenInput('post-apellido-renaper', '', [
                        'id' => 'post-apellido-renaper',
                    ]) ?>
                    <?= Html::hiddenInput('post-cuil-renaper', '', [
                        'id' => 'post-cuil-renaper',
                    ]) ?>
                    <?= Html::hiddenInput('post-fnacimiento-renaper', '', [
                        'id' => 'post-fnacimiento-renaper',
                    ]) ?>
                    <?= Html::hiddenInput('post-domicilio-renaper', '', [
                        'id' => 'post-domicilio-renaper',
                    ]) ?>
                    <?= Html::hiddenInput('post-localidad-renaper', '', [
                        'id' => 'post-localidad-renaper',
                    ]) ?>
                    <?= Html::hiddenInput('post-nacionalidad-renaper', '', [
                        'id' => 'post-nacionalidad-renaper',
                    ]) ?>

                    <?= Html::checkBoxList(
                        'opciones',
                        ['entregas', 'subsidios'],
                        [
                            'entregas' => 'Incluir Entregas',
                            'subsidios' => 'Incluir Subsidios',
                        ]
                    ) ?>
                    <div class="lds-ellipsis btn btn-primary" id="load-btn">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <?= Html::submitButton(
                        '<span class= "fas fa-print"></span> Imprir Reporte',
                        [
                            'value' => 'Imprimir',
                            'type' => 'submit',
                            'class' => 'btn btn-primary col-md-12',
                            'style' => 'display:none;',
                            'id' => 'imprimir-btn',
                        ]
                    ) ?>
                    <?= Html::endForm() ?>
                </div>
            </div>
            <!-- Fin input DNI -->
            <!-- Renaper -->
            <div class="panel panel-default col-md-6 col-md-offset-2" style="margin-bottom: 5px;">
                <div class="panel-heading" style="background:#0088cc; color:#fff; padding:5px;">
                    <h4 style="margin: 0; padding:0;">
                        <img _ngcontent-xwa-c8="" alt="" class="avatar-renaper" src="https://portalmds.neuquen.gov.ar/api/images/servicios/integrabilidad.png">
                        <b>RENAPER</b>
                    </h4>
                </div>
                <div class="panel-body" style="border: 1px solid rgba(100, 50, 50, 0.3); border-top: none;">
                    <div class="" id="datos-renaper">
                        <div id="txt_mensaje"></div>
                        <div id="load-animated" class="load-animated"></div>
                        <img id="img-renaper" src="" alt="" height="155px" />
                        <ul class="col-md-9" id="list-data-renaper" style="display: none;">
                            <li id="nombre-renaper">Nombres: </li>
                            <li id="apellido-renaper">Apellido: </li>
                            <li id="cuil-renaper">CUIL: </li>
                            <li id="fecha_nacimiento-renaper"> Fecha de nacimiento: </li>
                            <li id="domicilio-renaper">Domicilio: </li>
                            <li id="localidad-renaper">Localidad: </li>
                            <li id="nacionalidad-renaper">Nacionalidad: </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Fin Renarper -->
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <!-- Acordion Entregas/Subsidios -->
                    <div class="panel-group" id="accordion-historico" role="tablist" aria-multiselectable="true">
                        <!-- Entregas -->
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingEntregas" style="background:#0088cc;">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion-historico" href="#collapseEntregas" aria-expanded="true" aria-controls="collapseEntregas">
                                        <b>-</b>
                                        Entregas
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseEntregas" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEntregas">
                                <div class="panel-body">
                                    <!-- Grid Entregas -->
                                    <div class="sds-his-entrega-index">
                                        <div id="ajaxCrudDatatable">
                                            <?= GridView::widget([
                                                'id' =>
                                                    'crud-datatable-entrega',
                                                'dataProvider' => $dataProviderEntrega,
                                                'pjax' => true,
                                                'columns' => require Yii::$app
                                                    ->basePath .
                                                    '/views/sds_his_entrega/_columns.php',
                                                'toolbar' => [
                                                    [
                                                        'content' =>
                                                            Html::a(
                                                                '<i class="glyphicon glyphicon-repeat"></i>',
                                                                [
                                                                    'cruce_historico',
                                                                    'dni' => $dni,
                                                                ],
                                                                [
                                                                    'data-pjax' => 1,
                                                                    'class' =>
                                                                        'btn btn-default',
                                                                    'title' =>
                                                                        'Recargar Datos',
                                                                ]
                                                            ) . '{export}',
                                                    ],
                                                ],
                                                'striped' => true,
                                                'condensed' => true,
                                                'responsive' => true,

                                                'panel' => [
                                                    'before' => '',
                                                    'after' => '',
                                                ],
                                            ]) ?>
                                        </div>
                                    </div>
                                    <!-- Fin Grid Entregas -->
                                </div>
                            </div>
                        </div>
                        <!-- Fin entregas -->
                        <!-- Subsidios -->
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingSubsidios" style="background:#0088cc;">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion-historico" href="#collapseSubsidios" aria-expanded="false" aria-controls="collapseSubsidios">
                                        <b>-</b> Subsidios
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseSubsidios" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSubsidios">
                                <div class="panel-body">
                                    <!-- Grid Subsidios -->
                                    <div class="sds-his-entrega-index">
                                        <div id="ajaxCrudDatatable">
                                            <?= GridView::widget([
                                                'id' =>
                                                    'crud-datatable-subsidio',
                                                'dataProvider' => $dataProviderSubsidio,
                                                'pjax' => true,
                                                'columns' => require Yii::$app
                                                    ->basePath .
                                                    '/views/sds_his_admix/_columns.php',
                                                'toolbar' => [
                                                    [
                                                        'content' =>
                                                            Html::a(
                                                                '<i class="glyphicon glyphicon-repeat"></i>',
                                                                [
                                                                    'cruce_historico',
                                                                    'dni' => $dni,
                                                                ],
                                                                [
                                                                    'data-pjax' => 1,
                                                                    'class' =>
                                                                        'btn btn-default',
                                                                    'title' =>
                                                                        'Recargar Datos',
                                                                ]
                                                            ) . '{export}',
                                                    ],
                                                ],
                                                'striped' => true,
                                                'condensed' => true,
                                                'responsive' => true,

                                                'panel' => [
                                                    'before' => '',
                                                    'after' => '',
                                                ],
                                            ]) ?>
                                        </div>
                                    </div>
                                    <!-- Fin Grid Subsidios -->
                                </div>
                            </div>
                        </div>
                        <!-- Fin subsidios -->
                    </div>
                    <!-- Fin Acordion Entregas -->
                </div>
            </section>
        </div>
    </div>

<?php
$script_call_renaper = <<<JS
function datos_renaper(dni){
    $("#load-animated").show();
    $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
        if (data.status == "error") {
            $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
            $("#load-animated").hide();
            $("#list-data-renaper").hide();
        } else {
            var nombre = "";
            var apellido = "";
            var domicilio = "";
            var localidad = "";
            var fecha_nacimiento = null;
            var nacionalidad = "";
            var cuil = "";
            $.each(data, function(ind, elem) {
                if (ind == 'records') {
                    nombre = elem[0].result.nombres;
                    apellido = elem[0].result.apellido;
                    cuil = elem[0].result.cuil;
                    domicilio = elem[0].result.calle + " " + elem[0].result.numero;
                    localidad = elem[0].result.ciudad;
                    nacionalidad = elem[0].result.pais;
                    fecha_nacimiento = elem[0].result.fecha_nacimiento;
                }
            });
            if (fecha_nacimiento != null) {
                $("#load-animated").hide();
                $("#nombre-renaper").append('<b>'+corregir_palabra(nombre)+'</b>');
                $("#apellido-renaper").append('<b>'+corregir_palabra(apellido)+'</b>');
                $("#fecha_nacimiento-renaper").append('<b>'+fecha_nacimiento+'</b>');
                $("#nacionalidad-renaper").append('<b>'+nacionalidad+'</b>');
                $("#cuil-renaper").append('<b>'+cuil+'</b>');
                $("#domicilio-renaper").append('<b>'+domicilio+'</b>');
                $("#localidad-renaper").append('<b>'+corregir_palabra(localidad)+'</b>');
                loadPostData(nombre, apellido, fecha_nacimiento, nacionalidad, cuil, domicilio, localidad);
                $("#list-data-renaper").show();
                $("#load-btn").hide();
                $("#imprimir-btn").show();
                $('#txt_mensaje').html("");
            }
        }      
    });
}

function loadPostData(nombre, apellido, fecha_nacimiento, nacionalidad, cuil, domicilio, localidad){
    $("#post-nombre-renaper").val(corregir_palabra(nombre));
    $("#post-apellido-renaper").val(corregir_palabra(apellido));
    $("#post-fnacimiento-renaper").val(fecha_nacimiento);
    $("#post-nacionalidad-renaper").val(nacionalidad);
    $("#post-cuil-renaper").val(cuil);
    $("#post-domicilio-renaper").val(domicilio);
    $("#post-localidad-renaper").val(corregir_palabra(localidad));    
}

function corregir_palabra(palabra) {
    palabra = palabra.replace("?N_", "ÉN");
    palabra = palabra.replace("ï¿½", "É");
    palabra = palabra.replace(/_/g, " ");
    palabra = palabra.replace("É?", "Á");
    palabra = palabra.replace("ï¿½?", "Ñ");
    palabra = palabra.replace("�", "");
    return palabra;
}

datos_renaper($dni);

JS;
$this->registerJS($script_call_renaper);
else: ?>
    <div class="site-index">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12">
                <section class="panel">
                    <div class="panel-heading ">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="col-md-12 text-primary " style="text-align: center;">
                                    <h4>Consultar Histórico</h4>
                                </div>
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <?php echo Html::a(
                                                '<i class="glyphicon glyphicon-search"></i>',
                                                null,
                                                [
                                                    'name' => 'btn_dni',
                                                    'data-request-method' =>
                                                        'post',
                                                    'data-toggle' => 'tooltip',
                                                    'class' =>
                                                        'btn btn-primary',
                                                    'title' => Yii::t(
                                                        'app',
                                                        'Consultar DNI en Cruce'
                                                    ),
                                                    'onclick' =>
                                                        'location.href ="index.php?r=site/cruce_historico&dni=' .
                                                        '"+$("#txtDniHistorico").val()',
                                                ]
                                            ); ?>
                                        </span>
                                        <input type="text" class="form-control" id="txtDniHistorico" placeholder="DNI..." onchange='location.href ="index.php?r=site/cruce_historico&dni="+$("#txtDniHistorico").val()'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
<?php endif; ?>
