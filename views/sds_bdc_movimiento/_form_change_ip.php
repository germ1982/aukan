<div class="row">
    <div class="col-md-11" style="padding: 0;">
        <div class="col-md-6">
            <?= $form->field($model, 'ip_anterior')->textInput(['readonly'=>true, 'placeholder'=>'- Debe seleccionar equipo -'])?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'ip_nueva')->textInput(['maxlength' => true, 'pattern'=>'^((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])$', 'placeholder'=>'111.111.111.111']) ?>
        </div>
    </div>
</div>
<span class="text-info "><i class="fas fa-info-circle"></i> Para quitar la IP de un equipo debe dejar el campo <b>IP Nueva</b> vacio</span>

<?php
$script = <<<  JS
$(document).ready(function(){
    $('#all-equipos').change(function(){
        getIp($(this).val());
    });
    getIp($('#all-equipos').val());
});

function getIp(pk){
    $.post("index.php?r=sds_bdc_movimiento/get_ip_equipo&pk="+pk, function(data){
        if(data!=''){
            $('#sds_bdc_movimiento-ip_anterior').val(data);
        }else{
            $('#sds_bdc_movimiento-ip_anterior').val('');
            $('#sds_bdc_movimiento-ip_anterior').attr('placeholder', '- Sin IP asignada -');
        }
    });
}
JS;
$this->registerJs($script);
?>