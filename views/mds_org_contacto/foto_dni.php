<?php

use yii\widgets\ActiveForm; ?>
<style>
    @media screen and (min-width: 676px) {
        .modal-dialog {
            max-width: 300px;
            /* New width for default modal */
        }
    }
</style>
<div class="mds-org-contacto-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <div class="row">
        <div class="col-md-12" style="text-align: center;">
            <img id="renaper_foto" src="" alt="" height="250px" />
        </div>
    </div> -->

    <?= $form
        ->field($model, 'foto_dni')
        ->hiddenInput()
        ->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
    var dni = "$model->documento"!="" ? "$model->documento" : "0";
   
    $(document).ready(function() {
        datos_renaper(dni)
    });

    function datos_renaper(dni) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
            if (data.status == "error") {
                /*$("#txt_mensaje").html("<b>Error!</b><i>" + data.message + "</i>"); */
                /* limpiarDatos(); */
            } else {
                var nombre = "";
                var apellido = "";
                var domicilio = "";
                var localidad = "";
                var fecha_nacimiento = null;
                //var sexo = "";
                var nacionalidad = "";
                $.each(data, function(ind, elem) {
                    if (ind == 'records') {
                        nombre = elem[0].result.nombres;
                        apellido = elem[0].result.apellido;
                        domicilio = elem[0].result.calle + " " + elem[0].result.numero;
                        localidad = elem[0].result.ciudad;
                        //foto = elem[0].result.foto;
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                    }
                });
                if (fecha_nacimiento != null) {
                    /* $("#mds_org_contacto-idpersona").val('0');
                    $("#mds_org_contacto-nombre").val(corregir_palabra(nombre));
                    $("#mds_org_contacto-apellido").val(corregir_palabra(apellido));
                    $("#mds_org_contacto-fecha_nacimiento").val(fecha_nacimiento);
                    $("#mds_org_contacto-nacionalidad").val('');
                    $("#mds_org_contacto-sexo").val('');
                    $("#mds_org_contacto-domicilio").val(domicilio);
                    $("#mds_org_contacto-localidad").val(''); */
                    /* $("#renaper_foto").attr("src", foto);      */                   
                    $("#mds_org_contacto-foto_dni").val(foto);
                    /* $('#txt_mensaje').html(""); */
                }
            }          
        });
    }
    
    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }

JS;

$this->registerJs($script);

