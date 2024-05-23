<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<script>
    function validar_numeros()
        {
            var msj="";
            var aux = "";

            aux = validar_numero('primer_valor_subred');
            if (!(aux==""))
                msj = msj + aux; 

            aux = validar_numero('segundo_valor_subred');
            if (!(aux==""))
                msj = msj + aux; 

            aux = validar_numero('tercer_valor_subred');
            if (!(aux==""))
                msj = msj + aux; 
                
            aux = validar_numero('valor_inicial_ip');
            if (!(aux==""))
                msj = msj + aux; 
            aux = validar_numero('valor_final_ip');
            if (!(aux==""))
                msj = msj + aux; 


            if (($("#valor_inicial_ip").val())>($("#valor_final_ip").val()))
                msj = msj + "\nasegurese que el valor de inicio de rango de ip sea menor o igual al de final de rango";
            
            if (msj=="")
                {generar_rango();}
            else
                {
                    //alert (msj);
                    log.innerHTML = "<font color='red'> ATENCION: <br>"+msj + "</font>";
                    return false;
                }
        }

    function validar_numero(input)
        {
            var numero = $("#" + input ).val();
            var ban = "";
            //alert (input + ": " + numero + " ban: "+ ban );
            
            if (numero=="")
                {ban="\ningrese un valor en " + input + "<br>";}
            if (numero<1)
                {ban="\ningrese un valor mayor a 0 en " + input + "<br>";}
            if (numero>255)
                {ban="\ningrese un valor menor o igual a 255 en " + input + "<br>";}        
            return ban;
                
        }

    function generar_rango()
        {
            //encapsulo los parametros a guardar.
            var parametros = {
                "primer_valor_subred": $('#primer_valor_subred').val(),    
                "segundo_valor_subred": $('#segundo_valor_subred').val(),  
                "tercer_valor_subred": $('#tercer_valor_subred').val(),            
                "valor_inicial_ip": $("#valor_inicial_ip").val(),
                "valor_final_ip": $("#valor_final_ip").val(),
            };

            $.ajax({
                data: parametros, //datos que se envian a traves de ajax
                url: 'consultas/sds_reg_ip_alta.php', //php que recibe la peticion
                type: 'post', //método de envio
                beforeSend: function() {
                    log.innerHTML = "Procesando, espere por favor...";
                },
                success: function(response) { //aca recibe el json del php que guarda o dice si ya existia

                    var obj = jQuery.parseJSON(response); //pareo el json
                    log.innerHTML = obj.anuncio;
                    
                }
            });

        }
        
</script>

<div id="form_principal" class="sds-reg-ip-form">

<?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-12">
                <?=  Html::label('Sub Red (Ejemplo: 10.1.73)', 'label_subred', ['id' => 'label_subred']) ?>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12">
                <input type="number" id="primer_valor_subred" name="primer_valor_subred" value="255" min="1" max="255" style='width:50px;border:1px solid #BEBEBE;' maxlength=3/> .
                <input type="number" id="segundo_valor_subred" name="segundo_valor_subred" value="255" min="1" max="255" style='width:50px;border:1px solid #BEBEBE;' maxlength=3/> .
                <input type="number" id="tercer_valor_subred" name="tercer_valor_subred" value="255" min="1" max="255" style='width:50px;border:1px solid #BEBEBE;' maxlength=3/> 
            </div>
        </div>
        <br>
        <div class="row">

            <div class="col-md-2">
                <?=  Html::label('Ip Inicial:', 'label_inicio', ['id' => 'label_inicio']) ?>
                <input type="number" id="valor_inicial_ip" name="valor_inicial_ip" value="255" min="1" max="255" style='width:50px;border:1px solid #BEBEBE;' maxlength=3/>          
            </div>
            <div class="col-md-2">
                <?=  Html::label('Ip Final:', 'label_final', ['id' => 'label_inicio']) ?>
                <input type="number" id="valor_final_ip" name="valor_final_ip" value="255" min="1" max="255" style='width:50px;border:1px solid #BEBEBE;' maxlength=3/>             
            </div>
            <div class="col-md-7">
            </div>
        </div>

        <div class="row">
            <div class="col-md-1">
                <?=  Html::label('Estado: ', 'label_estado', ['id' => 'label_estado']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="log" name="log" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;">
                </div>


            </div>
        </div>
</div>
