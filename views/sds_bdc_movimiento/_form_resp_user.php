<?php
use app\models\Sds_bdc_movimiento;
use kartik\select2\Select2;
?>
<div class="row" id='ultimo-responsable'>
    <div class="col-md-11">
        <?= $form->field($model, 'responsable_anterior')->widget(Select2::class, [
            'data' => $all_contactos_personas,
            'options' => [
                'placeholder' => '- Responsable Actual -',
            ],
            'pluginOptions' => [
                'allowClear' => false,
                'disabled' => true,
            ],
        ])->label('Responsable Actual');
        ?>
    </div>
</div>
<div class="row" id="new_resp_user">
    <div class="col-md-5">
        <?= $form->field($model, 'responsable_nuevo')->widget(Select2::class, [
            'data' => $all_contactos_personas,
            'options' => [
                'placeholder' => '- Seleccionar Nuevo Responsable -'
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>
    </div>
    <div class="col-md-5 col-md-offset-1">
        <?= $form->field($model, 'usuario_nuevo')->widget(Select2::class, [
            'data' => $all_contactos_personas,
            'options' => [
                'placeholder' => '- Seleccionar Nuevo Usuario -'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
    <span class="text-warning col-md-12"><i><b>*Si selecciona Responsable y/o Usuario nuevo serán aplicados a todos los equipos 
        seleccionados</b></i></span>
</div>
<?php
$baja=Sds_bdc_movimiento::MOV_BAJA;
$alta=Sds_bdc_movimiento::MOV_ALTA;
$cambio_responsable=Sds_bdc_movimiento::MOV_CAM_RESPONSABLE;
$reparacion=Sds_bdc_movimiento::MOV_REPARACION;
$home_office=Sds_bdc_movimiento::MOV_HOME_OFFICE;
$entrega_rep=Sds_bdc_movimiento::MOV_ENT_REPARACION;
$cambio_ip=Sds_bdc_movimiento::MOV_CAM_IP;

$script = <<<  JS
$(document).ready(function() {
    //Al cambiar el valor del tipo de movimiento, muestro los campos correspondientes:
    $('#tipo-movimiento').change(function(){
        //Reinicio el valor de responsable_nuevo y usuario_nuevo
        $('#sds_bdc_movimiento-responsable_nuevo').val(null);
        //Obtengo y reinicio el select2 de responsable_nuevo
        settings = $('#sds_bdc_movimiento-responsable_nuevo').attr('data-krajee-select2');
        settings = window[settings];
        $('#sds_bdc_movimiento-responsable_nuevo').select2(settings);
        $('#sds_bdc_movimiento-usuario_nuevo').val(null);
        //Obtengo y reinicio el select2 de usuario_nuevo
        settings = $('#sds_bdc_movimiento-usuario_nuevo').attr('data-krajee-select2');
        settings = window[settings];
        $('#sds_bdc_movimiento-usuario_nuevo').select2(settings);

        if($(this).val()==$baja){
            $('#ultimo-responsable').hide();
            $('#new_resp_user').hide();
        }
        if($(this).val()==$alta){
            $('#ultimo-responsable').hide();
            $('#new_resp_user').show();
        }
        if($(this).val()==$cambio_responsable){
            $('#ultimo-responsable').show();
            $('#new_resp_user').show();
        }
        if($(this).val()==$reparacion){
            $('#ultimo-responsable').hide();
            $('#new_resp_user').hide();
        }
        if($(this).val()==$entrega_rep){
            $('#ultimo-responsable').hide();
            $('#new_resp_user').hide();
        }
        if($(this).val()==$home_office){
            $('#ultimo-responsable').hide();
            $('#new_resp_user').hide();
        }
    });

    $('#all-equipos').change(function(){
        if($('#tipo-movimiento').val()==$cambio_responsable){
            //Verifico la cantidad de equipos seleccionados
            var count_selected=($(this).val()).length;
            if(count_selected<2){
                $.post("index.php?r=sds_bdc_movimiento/get_equipos_responsable&equipo="+$(this).val()+"&tipo="+$cambio_responsable, function(data){
                    data = $.parseJSON(data);
                    var options='';
                    for(const property in data){//Recorro el objeto devuelto
                        if($('#all-equipos').val()==property){//Para mantener seleccionado el primer id
                            options += '<option selected value="'+property+'">'+data[property]+'</option>';
                        }else{
                            options += '<option value="'+property+'">'+data[property]+'</option>';
                        }
                    }

                    //Seteo al Select equipos las nuevas opciones
                    $('#all-equipos').html(options);
                    
                    //Obtengo y reinicio el select2 de equipos
                    settings = $("#all-equipos").attr('data-krajee-select2');
                    settings = window[settings];
                    $("#all-equipos").select2(settings);
                });

                //Consulto Responsable anterior si el select de equipos no esta vacio
                if($('#all-equipos').val()!=0){
                    $.post("index.php?r=sds_bdc_movimiento/get_responsable&equipo="+(count_selected!=0 ? $('#all-equipos').val():0), function(responsable){
                        responsable = $.parseJSON(responsable);
                        var textOption=responsable.persona.nombre+' '+responsable.persona.apellido+' - Leg.: '+responsable.contacto.legajo;
                        var selectResponsable='<option value="'+responsable.contacto.idcontacto+'">'+textOption+'</option>';
                        $('#sds_bdc_movimiento-responsable_anterior').html(selectResponsable);
                    });
                }else{
                    $('#sds_bdc_movimiento-responsable_anterior').val('');
                }

                //Obtengo y reinicio el select2 de responsable
                settings = $('#sds_bdc_movimiento-responsable_anterior').attr('data-krajee-select2');
                settings = window[settings];
                $('#sds_bdc_movimiento-responsable_anterior').select2(settings);
            }
        }
    });
});
JS;
$this->registerJs($script);
?>