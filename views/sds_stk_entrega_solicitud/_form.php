<?php

use app\controllers\SiteController;
use app\models\Mds_org_contacto;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_entrega_solicitud */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-stk-entrega-solicitud-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php
    if (Yii::$app->session->hasFlash('save_solicitud')) : ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i> ¡Excelente! Guardado Correctamente</h4>
        </div>
    <?php endif;
    if (Yii::$app->session->hasFlash('fail_save_solicitud')) : ?>
        <div class="alert alert-danger alert-dismissable" id="alert-fail-save">
            <h4><i class="icon fas fa-times"></i> ¡UPS! Algo no esta bien...</h4>
            <?= Yii::$app->session->getFlash('fail_save_solicitud') ?>
        </div>
    <?php endif; ?>

    <?php function ($contacto) {
        return $contacto->nombre;
    } ?>
    <?php
    if ($model->isNewRecord) {
        $model->fecha_hora = date('d/m/Y H:i:s');
    } else {
        $model->fecha_hora = date('d/m/Y H:i:s', strtotime($model->fecha_hora));
        $nombre = Sds_com_persona::findOne($model->idpersona);
    } ?>
    <?= $form->field($model, 'fecha_hora')->widget(DatePicker::class, [
        'disabled' => true,
        'language' => 'es',
        'layout' => '{picker}{input}',
        'options' => [
            'class' => 'form-control input-md',
            'placeholder' => 'DD / MM / YYYY'
        ],
        'pluginOptions' => [
            //'value' => date('d/m/Y'),
            'format' => 'dd/mm/yyyy',
            'endDate' => date('d/m/Y'),
            'todayHighlight' => true,
            'autoclose' => true,
        ]
    ]);
    ?>
    <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            Mds_org_contacto::findBySql(
                "SELECT c.*, CONCAT(p.apellido,', ', p.nombre) nombre FROM mds_org_contacto c
                JOIN sds_com_persona p ON c.idpersona=p.idpersona"
            )->all(),
            'idcontacto',
            function ($model) {
                return $model->legajo . ' - ' . $model->nombre;
            }
        ),
        'options' => [
            'placeholder' => '- Responsable -',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'disabled' => false,
        ]
    ]);
    ?>
    <div class="row">
        <!-- Linea de busqueda -->
        <div class="col-md-12">
            <div class="input-group">
                <?= $form->field($model, 'dni')->textInput(['placeholder' => "DNI -Sin puntos ni espacios-", 'maxlength' => 10]) ?>
                <span class="input-group-btn " style="padding-top:27px;">
                    <div class="btn btn-primary" id="getPersona">
                        <i class="glyphicon glyphicon-search"></i>
                    </div>
                </span>
            </div>
        </div>
        <div class="col-md-12" style="padding-top:27px;" id="txt_mensaje_destinatario"></div>
    </div>
    <div class="lds-ellipsis btn btn-primary" style="padding:0 0 0 200px; text-align:center; " id="load-btn">
        <div></div>
        <div></div>
        <div></div>
        <div></div>

    </div>


    <?php $request = Yii::$app->request; ?>
    <?php $display = 'none';
    if (isset($persona)) : //&& $model->isNewRecord) :
        if ($persona->getErrors() || ($model->getErrors() && $persona->getErrors()) || $model->getErrors()) :
            $display = 'block';
        endif;
    endif; ?>

    <div id="_form_persona" class="row" style="display:<?= $display ?>;">
        <?php include('_form_persona.php'); ?>
    </div>

    <?= $form->field($model, 'idpersona')->hiddenInput()->label(false) ?>
    <?php
    // if($model->isNewRecord && $persona->idpersona==null){
    //     $persona=Sds_com_persona::findOne(77624);
    //     print_r($model->attributes);
    // }
    //$persona=Sds_com_persona::findOne(77624);
    ?>
    <div style="border: 1px solid #ccc;border-radius:5px; display:<?= $model->isNewRecord ? 'none' : 'block' ?>" id="destinatario_datos">
            <div class="row" style="margin: 0 0 10px 0;font-size: 20px;background-color:#ebebeb;color:#08c;border-top-left-radius:5px;border-top-right-radius:5px;padding:4px;">
                <div class="col-md-4"></div>
                <div class="col-md-4" style="text-align: center;font-size: 16px;font-weight:bold">Datos Destinatario</div>
            </div>
            <div style="margin-left: 5px;">
            <div class="row">
                <div class="col-md-5"><b>Nombre:</b>
                    <p id="nombre"><?= strtoupper($persona->nombre) ?></p>
                </div>
                <div class="col-md-4"><b>Apellido:</b>
                    <p id="apellido"><?= strtoupper($persona->apellido) ?></p>
                </div>
                <div class="col-md-3"><b>Género:</b>
                    <p id="genero"><?= Sds_com_configuracion::findOne($persona->genero) == null ? '' : strtoupper(Sds_com_configuracion::findOne($persona->genero)->descripcion) ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5"><b>País: </b>
                    <p id="pais"><?= Sds_com_configuracion::findOne($persona->nacionalidad) == null ? '' : Sds_com_configuracion::findOne($persona->nacionalidad)->descripcion ?></p>
                </div>
                <div class="col-md-4"><b>Domicilio:</b>
                    <?php //print_r($persona->attributes); 
                    ?>
                    <p id="calle"><?= $persona->domicilio_calle != null ? $persona->domicilio_calle . "-" . $persona->domicilio_numero : '-SIN DATOS-' ?></p>
                </div>
                <div class="col-md-3"><b>Fecha Nacimiento </b>
                    <p id="fecha_nacimiento"><?= $persona->fecha_nacimiento == '1900-01-01' ? '-SIN DATOS-' : date('d/m/Y', strtotime($persona->fecha_nacimiento)) ?></p>
                </div>
            </div>
        </div>
    </div>
    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>


</div>
<script>
    $(document).ready(function() {
        $("#load-btn").hide();
        if (<?= $model->isNewRecord ? 'false' : 'true' ?> && <?= (isset($persona_datos) && $persona_datos != null) ? 'false' : 'true' ?>) {
            console.log('caso 1');
            call_renaper();
        } else {
            // $("#destinatario_datos").hide();
            // $("#_form_persona").show();
            // $('#sds_stk_entrega_solicitud-idpersona').val(null);
            // $('#sds_com_persona-nombre').val(null);
            // $('#sds_com_persona-apellido').val(null);
            // $('#sds_com_persona-genero').val(null);
            // $('#sds_com_persona-nacionalidad').val(null);
            // $('#sds_com_persona-fecha_nacimiento').val(null);
            // $('#sds_com_persona-domicilio_calle').val(null);
            // $('#sds_com_persona-domicilio_numero').val(null);
            //Obtengo y reinicio el select2 de Genero
            //$("#sds_com_persona-genero").select2(window[$("#sds_com_persona-genero").attr('data-krajee-select2')]);
            //Obtengo y reinicio el select2 de Nacionalidad
            //$("#sds_com_persona-nacionalidad").select2(window[$("#sds_com_persona-nacionalidad").attr('data-krajee-select2')]);

        }
        $('#getPersona').click(function() {
            $('#sds_stk_entrega_solicitud-idpersona').val(null);
            call_renaper();
        });
    });


    $("#sds_stk_entrega_solicitud-dni").change(function() {
        $('#sds_stk_entrega_solicitud-idpersona').val(null);
        if ($(this).val().length > 7) {
            call_renaper();
        } else {
            $("#btn-submit").hide();
        }
    });

    function call_renaper() {
        console.log('call');
        $("#load-btn").show();
        $("#destinatario_datos").hide();
        var dni = $('#sds_stk_entrega_solicitud-dni').val();
        if (dni == '' || dni == null) {
            $('#sds_stk_entrega_solicitud-dni').css('box-shadow', 'inset 0 1px 1px rgb(0 0 0 / 8%), 0 0 6px #ce8483');
            $('#sds_stk_entrega_solicitud-dni').css('border', '1px solid #843534');
            $("#load-btn").hide();
            return;
        }
        var url = '<?= Url::to(['sds_stk_entrega_solicitud/get_persona']) ?>' + '&dni=' + dni;
        $.get(url, function(data) {
            if (!data) {
                $('#btn-submit').hide();
                $.ajax({
                    data: {
                        'servicio': '*refactorizar get_renaper',
                        'auditoria': 'motu',
                        'usuario_auditoria': 'motu',
                        'filtro': 'documento=' + dni,
                        'tipo': 0
                    },
                    type: "POST",
                    dataType: "json",
                    url: "https://apisur.neuquen.gov.ar/index.php",
                    success: function(data) {
                        console.log(data.status, 'ld');
                        if (data.status == 'error') {
                            $("#load-btn").hide();
                            $('#btn-submit').show();
                            $("#destinatario_datos").hide();
                            $("#_form_persona").show();
                            if (<?= $model->isNewRecord ? '0==1' : '1==1' ?>) {
                                $('#sds_stk_entrega_solicitud-idpersona').val(null);
                                $('#sds_com_persona-nombre').val(null);
                                $('#sds_com_persona-apellido').val(null);
                                $('#sds_com_persona-genero').val(null);
                                $('#sds_com_persona-nacionalidad').val(null);
                                $('#sds_com_persona-fecha_nacimiento').val(null);
                                $('#sds_com_persona-domicilio_calle').val(null);
                                $('#sds_com_persona-domicilio_numero').val(null);

                                //Obtengo y reinicio el select2 de Genero
                                $("#sds_com_persona-genero").select2(window[$("#sds_com_persona-genero").attr('data-krajee-select2')]);

                                //Obtengo y reinicio el select2 de Nacionalidad
                                $("#sds_com_persona-nacionalidad").select2(window[$("#sds_com_persona-nacionalidad").attr('data-krajee-select2')]);

                            }
                        }
                        $.each(data, function(ind, elem) {
                            if (ind == 'records') {
                                var url = '<?= Url::to(['sds_stk_entrega_solicitud/set_persona']) ?>';
                                var element = [elem[0].result];
                                console.log(element);
                                if (elem[0].result.pais == null) {
                                    elem[0].result.pais = null;
                                }
                                if (elem[0].result.calle == null) {
                                    elem[0].result.calle = null;
                                }
                                if (elem[0].result.numero == null) {
                                    elem[0].result.numero = null;
                                }
                                if (elem[0].result.ciudad == null) {
                                    elem[0].result.ciudad = null;
                                }
                                $.ajax({
                                    data: {
                                        dni: elem[0].result.dni,
                                        pais: elem[0].result.pais,
                                        genero: elem[0].result.genero,
                                        fecha_nacimiento: elem[0].result.fecha_nacimiento,
                                        nombre: elem[0].result.nombres,
                                        apellido: elem[0].result.apellido,
                                        calle: elem[0].result.calle,
                                        numero: elem[0].result.numero,
                                        localidad: elem[0].result.ciudad
                                    },
                                    type: 'GET',
                                    dataType: "json",
                                    url: url,
                                    success: function(data) {
                                        console.log(data);
                                        $("#destinatario_datos").show();
                                        $("#_form_persona").hide();
                                        $("#load-btn").hide();
                                        $('#sds_stk_entrega_solicitud-idpersona').val(data);
                                        $('#sds_stk_entrega_solicitud-dni').css('border', '1px solid #2b542c');
                                        $('#sds_stk_entrega_solicitud-dni').css('box-shadow', 'inset 0 1px 1px rgb(0 0 0 / 8%), 0 0 6px #67b168');
                                    }
                                });
                            }
                        });
                    }
                });
            } else {
                console.log(data, 'it');
                $("#destinatario_datos").show();
                $('#nombre').html(data.nombre);
                $('#apellido').html(data.apellido);
                $('#fecha_nacimiento').html(data.fecha_nacimiento);
                $('#pais').html(data.pais);
                $('#genero').html(data.genero);
                if (data.calle == '' && data.numero == '') {
                    $('#calle').html('-SIN DATOS-');
                } else {
                    $('#calle').html(data.calle + ' N°' + data.numero);
                }
                $("#load-btn").hide();
                $('#btn-submit').show();
                $("#_form_persona").hide();
                $('#sds_stk_entrega_solicitud-idpersona').val(data.idpersona);
                $('#sds_stk_entrega_solicitud-dni').css('border', '1px solid #2b542c');
                $('#sds_stk_entrega_solicitud-dni').css('box-shadow', 'inset 0 1px 1px rgb(0 0 0 / 8%), 0 0 6px #67b168');
            }
            
            $('#btn-submit').attr('type', 'submit');
            $('#btn-submit').attr('class', 'btn-success');
            console.log($('#btn-submit').attr('type'));
            
        });
    }
</script>