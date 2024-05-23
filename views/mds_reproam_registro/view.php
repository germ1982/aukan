<?php

use yii\helpers\Html;
use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Url;


$this->title = "Ver Registro #{$model->idregistro}";
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
</style>
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
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">N° Legajo ReProAM</label>
                        <input type="text" class="form-control" value="<?php echo $model->numero_legajo_reproam ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" value="<?php echo $model->nombre ?>" readonly>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Localidad</label>
                        <input type="text" class="form-control" value="<?php echo $model->localidad->descripcion ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Barrio</label>

                        <input type="text" class="form-control" <?php
                                                                if ($model->barrio != null) { ?> value="<?php echo $model->barrio->nombre ?>" <?php } ?> readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Zona</label>
                        <input type="text" class="form-control" value="<?php echo $model->zona->descripcion ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" value="<?php echo $model->direccion ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Situación Habitacional de la Organización/Grupo</label>
                        <input type="text" class="form-control" value="<?php echo $model->situacionHabitacional ? $model->situacionHabitacional->descripcion : '' ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?php echo $model->mail ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Teléfono Fijo</label>
                        <input type="text" class="form-control" value="<?php echo $model->telefono ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Celular</label>
                        <input type="text" class="form-control" value="<?php echo $model->telefono_movil ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Presidente</label>
                        <input type="text" class="form-control" value="<?php echo $model->nombre_presidente ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Vicepresidente</label>
                        <input type="text" class="form-control" value="<?php echo $model->nombre_vicepresidente ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre Secretario</label>
                        <input type="text" class="form-control" value="<?php echo $model->nombre_secretario ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Personería Jurídica</label>
                        <input type="text" class="form-control" value="<?php echo ($model->personeria_juridica === 1) ? 'Si' : 'No' ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Personería Jurídica Resolución</label>
                        <input type="text" class="form-control" value="<?php echo $model->personeria_juridica_resolucion ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Personería Jurídica Fecha Vencimiento</label>

                        <input type="text" class="form-control" <?php
                                                                if (($model->personeria_juridica_fecha_vencimiento) != null) { ?> value="<?php
                                                                                                                                            $fr = date_create($model->personeria_juridica_fecha_vencimiento);
                                                                                                                                            $fr = date_format($fr, 'd-m-Y');
                                                                                                                                            echo $fr ?>" <?php } ?> readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Constancia Inscripción Entregada</label>
                        <input type="text" class="form-control" value="<?php echo ($model->entrega_constancia_inscripcion == 1) ? 'Si' : 'No'; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Responsable Entrega Constancia </label>
                        <input type="text" class="form-control" value="<?php echo $model->entrega_constancia_inscripcion_nombre ?>" readonly>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" style="min-height: 30vh" readonly><?php echo $model->observaciones ?></textarea>
                    </div>
                </div>

                <?php
                $adjuntos = $model->getAdjuntos();
                ?>
                <?php
                if (count($adjuntos) > 0) : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <br />
                            <label>Otros archivos</label>
                            <ul style="list-style: none">
                                <?php
                                foreach ($adjuntos as $adjunto) : ?>
                                    <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . '/' . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                            <br>
                        </div>
                    </div>
                <?php endif ?>

                <br>
                <div class="card-footer" id="botones">
                    <a class="btn btn-info" href="index.php?r=mds_reproam_registro/index">Volver </a>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCrearMandato">
                        Agregar Mandato de consejero
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalVerMandatos">
                        Ver Mandatos
                    </button>
                    <button type="button" class="btn btnPrint">
                        <?php $url =  Url::to(['/mds_reproam_registro/detalle_registro', 'idregistro' => $model->idregistro]); ?>
                        <a href="<?php echo $url ?>" target="_blank">Exportar PDF</a>
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
require 'modal_crear_mandato.php';
require 'modal_ver_mandatos.php';

$this->registerJs(
    "$(document).ready(function() {  
        $('#boton-guardar-mandato').prop('disabled', true);

        
        ;(function($){
            $.fn.datepicker.dates['es'] = {
                days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                daysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                daysMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                today: 'Hoy',
                monthsTitle: 'Meses',
                clear: 'Borrar',
                weekStart: 1,
                format: 'dd/mm/yyyy'
            };
        }(jQuery));

        $('#divDatepickerFin').datepicker.defaults.language = 'es';

        function validarFechas() {
            fechaDesde = $('#fechaInicio').val();
            fechaFin = $('#fechaFin').val();
            fechaInicioDias = fechaDesde.substr(0,2);
            fechaInicioMes = fechaDesde.substr(3,2);
            fechaInicioAño = fechaDesde.substr(6,4);
            fechaFinDias = fechaFin.substr(0,2);
            fechaFinMes = fechaFin.substr(3,2);
            fechaFinAño = fechaFin.substr(6,4);
            const fecha_desde = new Date(fechaInicioAño,fechaInicioMes,fechaInicioDias);
            const fecha_hasta = new Date(fechaFinAño,fechaFinMes,fechaFinDias);
        
            if(fechaDesde && fechaFin) {
                if(fecha_desde < fecha_hasta) {
                    $('#boton-guardar-mandato').prop('disabled', false);
                    $('#divFechaFin').css('color', '#777');
                    $('#fechaFin').css('border-color', '#ccc');
                    $('#smallFin').text('');
                    $('#divFechaInicio').css('color', '#777');
                    $('#fechaInicio').css('border-color', '#ccc');
                    $('#smallInicio').text('');
                } else {
                    $('#boton-guardar-mandato').prop('disabled', true);
                    $('#divFechaFin').css('color', 'red');
                    $('#fechaFin').css('border-color', 'red');
                    $('#smallFin').text('Fecha Hasta debe ser mayor a Fecha Desde.');
                }
            } else if (fechaDesde) {
                $('#boton-guardar-mandato').prop('disabled', false);
                $('#divFechaFin').css('color', '#777');
                $('#fechaFin').css('border-color', '#ccc');
                $('#smallFin').text('');
                $('#divFechaInicio').css('color', '#777');
                $('#fechaInicio').css('border-color', '#ccc');
                $('#smallInicio').text('');
            }
        }

        function validarFechaVacia() { 
            fechaDesde = $('#fechaInicio').val();

            if (fechaDesde) {
                $('#boton-guardar-mandato').prop('disabled', false);
                $('#divFechaFin').css('color', '#777');
                $('#fechaFin').css('border-color', '#ccc');
                $('#smallFin').text('');
            } else {
                $('#boton-guardar-mandato').prop('disabled', true);
                $('#divFechaInicio').css('color', 'red');
                $('#fechaInicio').css('border-color', 'red');
                $('#smallInicio').text('Fecha Desde no puede ser vacio.');
            }
        }

        $('#divDatepickerFin').on('changeDate', function(e) {
            validarFechas();
        });

        $('#divDatepickerFin').on('change', function(e) {
            fechaFin = $('#fechaFin').val();
            if (fechaFin === '') {
                validarFechaVacia();
            }
        });

        $('#divDatepickerInicio').on('changeDate', function(e) {
            validarFechas();
        });

        $('#divDatepickerInicio').on('change', function(e) {
            fechaDesde = $('#fechaInicio').val();
            if (fechaDesde === '') {
                validarFechaVacia();
            }
        });


    });

    
        $('#boton-guardar-mandato').click(function() {
            const idregistro = $('#idRegistro').val();
            const fecha_desde = $('#fechaInicio').val();
            const fecha_hasta = $('#fechaFin').val();
            const titularOption = $('#titular').val();
            const mandatos = $('#tieneMandatos').val();
            const activoOption = $('#activo').val();
            var titular = 1;
            var deleted_at = '';
            if (titularOption === 'Titular') {
                titular = 1;
            } else {
                titular = 0;
            }
            if (activoOption === 'Si') {
                deleted_at = '1';
            } else {
                deleted_at = '0';
            }
            const observaciones = $('#observaciones').val();
            $.ajax({
                    type: 'POST',
                    url: '" . Url::to(['/mds_reproam_mandato/store']) . "', 
                    data: { idregistro,
                            fecha_desde, 
                            fecha_hasta,
                            titular,
                            observaciones,
                            deleted_at },

                    success: function (data) {
                        parseData = JSON.parse(data);
                        if(parseData.id) {
                            alert(parseData.message);
                            $('#fechaFin').val('');
                            $('#fechaInicio').val('');
                            $('#modalCrearMandato').modal('toggle');
                            var titular = '';
                            if (parseData?.model?.titular === 1) {
                                titular = 'Titular';
                             } else {
                                titular = 'Suplente';
                             }
                             if(parseData && !parseData.model.deleted_at) {
                                var textoMandato = '';
                                 if (parseData.model.fecha_hasta) {
                                    textoMandato = '<li> <b>Periodo:</b> Desde ' + parseData.model.fecha_desde + ' - Hasta: ' + parseData.model.fecha_hasta + '<br/>' + '<b>Carácter: </b>' + titular + '<br/> <b> Observaciones: </b>' + parseData.model.observaciones + '</li> <hr>';
                                } else {
                                    textoMandato = '<li> <b>Periodo:</b> Desde ' + parseData.model.fecha_desde + ' - Hasta: <br/>' + '<b>Carácter: </b>' + titular + '<br/> <b> Observaciones: </b>' + parseData.model.observaciones + '</li> <hr>';
                                }
                                 $('#listaMandatos').prepend(textoMandato);
                                 if(mandatos === '0') {
                                     $('#parrafoMandatos').replaceWith('');
                                     const mandatos = $('#tieneMandatos').val('1');
                                }
                            }
                        } else {
                            alert(parseData.message);
                        }
                    },
                    error: function (errormessage) {
                        console.log(errormessage);
                        alert('not working');
        
                    }
                });
        
    })
    
    "
);


?>