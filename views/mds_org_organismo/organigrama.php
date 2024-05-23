<?php

use app\models\Mds_org_organismo;
use johnitvn\ajaxcrud\CrudAsset;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;


$this->title = "Organigrama";
CrudAsset::register($this);

?>
<header class="page-header" style="margin-bottom: 10px !important; padding-left:0px !important;">

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
<div class="row" style="overflow: auto">
    <div class="col-md-6">
        <section class="panel">
            <div class="panel-body">
                <?php
                $model = new Mds_org_organismo();
                $form = ActiveForm::begin(['enableClientValidation' => false]);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'descripcion')->textInput(['placeholder' => 'Buscar...'])->label(false); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <div class="row">
                    <div id="treeBasic" class="col-md-12">
                        <!-- La parte de UI del árbol se genera a partir del javascript 
                            de la pagina de ejemplo template/javascripts/ui-elements/examples.treeview.js
                            ya agregados en AppAsset-->
                        <ul>
                            <?php
                            /* $organismo_raiz = Mds_org_organismo::getOrganismoRaiz();
                        echo Mds_org_organismo::getArbolOrganigrama($organismo_raiz,); */
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-6">
        <section class="panel">
            <div class="panel-body">
                <div id="dispositivos" class="col-md-6">
                    <h4>Dispositivos</h4>
                    <p>Seleccione organismo de su izquierda. </p>
                </div>
                <div id="contactos" class="col-md-6">
                    <h4>Contactos</h4>
                    <p>Seleccione dispositivo. </p>
                </div>
            </div>
        </section>
    </div>
</div>


<!-- <script type="text/javascript">  !-->
<?php
// barrio 8 de octubre , calle vieja, mna 14, lote 9, porton verde taller lago.
//Marisol de la fuente
//<!-- alert('clicked node: ' + idorganismo);!-->
$this->registerJs(
    //$(document).ready(function(){ 
    "
        $('#treeBasic').on('activate_node.jstree', function (e, data) 
             {
                if (data == undefined || data.node == undefined || data.node.id == undefined)
                        return;
                document.getElementById('contactos').innerHTML='<h4>Contactos</h4><p>Seleccione dispositivo.</p>';        
                var idorganismo=data.node.id;                               
                ver_dispositivos(idorganismo);
            }
        );
        $('#dispositivos').on('click', 'section', function (event) {
            var iddispositivo= event.target.id;           
            $(\"#dispositivos section\").each(function(section){                
                //id_li= $(this).attr('id');
                $(this).find('div').attr('style', 'background-color:transparent;cursor:pointer;');
                $(this).find('div .button').attr('style', 'float:right;background-color: transparent');
            });
            event.target.style.background=\"#b3e5ff\";     
            ver_contactos(iddispositivo);
        });"
    //}
    //);
);

?>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>
<!-- </script> !-->

<?php

$this->registerJs(
    "$(document).ready(function() {
        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });"
);
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    })"
);
$this->registerJs("$('#mds_org_organismo-descripcion').keyup(function(){        
    cargarArbol();
});");

$this->registerJs("$(document).ready(function () {
    cargarArbol();
});");

?>
<script>
    function cargarArbol() {
        var descripcion = $('#mds_org_organismo-descripcion').val();
        $.post("index.php?r=mds_org_organismo/reload_organigrama&descripcion=" + descripcion, function(data) {
            $("#treeBasic").jstree(true).settings.core.data = data;
            $("#treeBasic").jstree(true).refresh();
        });
    }

    function ver_contactos(iddispositivo) {
        if (iddispositivo != '') {
            $.post("index.php?r=mds_org_organismo/buscar_personas&iddispositivo=" + iddispositivo, function(data) {
                data = $.parseJSON(data);
                if (data.length === 0) {
                    //alert('no encontro Organismos');
                } else {
                    personas = data[0];
                    total = data[1][0];
                    cadpersonas = "";
                    if (total == 0) {
                        encabezado = '<h4>Contactos</h4>';
                        cadenafinal = encabezado + 'No hay personas registradas';
                    } else {
                        let i = 0;
                        while (i < total) {
                            par_per = personas[i];
                            var cad_emp = "<section style=\"margin-bottom:2%;\" class=\"panel panel-featured-left panel-featured-info\" >" +
                                "<div class='panel-body'>" +
                                "<div class='col-md-10'>" + par_per[1] + "</div>" +
                                "<div class='col-md-2'>" +
                                "<div class='button' style=\"float:right;background-color: transparent\">" +
                                "<a href=\"index.php?r=mds_org_contacto/update&id=" + par_per[0] + "&organigrama=1" +
                                "\" title aria-label=\"Actualizar\" data-pjax=\"0\" role=\"modal-remote\" data-toggle=\"tooltip\" data-original-title=\"Editar\"><span class= \"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>" +
                                "</a>" +
                                "</div>" +
                                "</div>" +
                                "</div>" +
                                "</section>";
                            cadpersonas = cadpersonas + cad_emp;
                            i++;
                        }
                        encabezado = '<h4>Contactos (' + total + ')</h4>';
                        cadenafinal = encabezado + cadpersonas;

                    }

                    document.getElementById('contactos').innerHTML = cadenafinal;

                };
                //alert('salio de los ifff');
            });
        }
        //alert('al final');

    }

    function ver_dispositivos(idorganismo) {
        $.post("index.php?r=mds_org_organismo/buscar_dispositivos&idorganismo=" + idorganismo, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                //alert('no encontro Organismos');
            } else {
                dispositivos = data[0];
                total = data[1][0];

                if (total == 0) {
                    encabezado = '<h4 >Dispositivos </h4>';
                    cadenafinal = encabezado + 'No hay dispositivos registrados';
                } else {
                    let i = 0;
                    //ANOTEZE: Cambio lista de items por paneles
                    //cad_disp = '<ul id="listadispositivos">';
                    var cad_disp = '';
                    while (i < total) {
                        par_disp = dispositivos[i];
                        //cad_disp = cad_disp+'<p id="'+ par_disp[0] + '">'+ par_disp[1] + '</p>';

                        //cad_disp = cad_disp + "<li onmouseover='pintar(this)'  onmouseout=\"despintar(this)\"   id='" + par_disp[0] + "' >" + par_disp[1] + "</li>";
                        cad_disp = cad_disp + "<section style=\"margin-bottom:2%;\" class=\"panel panel-featured-left panel-featured-info\" >" +
                            "<div id='" + par_disp[0] + "' style='cursor: pointer;' class='panel-body' onmouseover='pintar(this)'  onmouseout=\"despintar(this)\" >" + par_disp[1] +
                            "<div class='button' style=\"float:right;background-color: transparent\">" +
                            "<a href=\"index.php?r=mds_org_dispositivo/update&id=" + par_disp[0] + "&organigrama=1" + "\" title aria-label=\"Actualizar\" data-pjax=\"0\" role=\"modal-remote\" data-toggle=\"tooltip\" data-original-title=\"Editar\"><span class= \"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></a>" +
                            "</div>" +
                            "</div>" +
                            "</section>";
                        i++;
                    }
                    //cad_disp = cad_disp + '</ul>';
                    encabezado = '<h4>Dispositivos (' + total + ')</h4>';
                    cadenafinal = encabezado + cad_disp;
                }
                $('#dispositivos').html(cadenafinal);
            };
        });
    }

    function componentFromStr(numStr, percent) {
        var num = Math.max(0, parseInt(numStr, 10));
        return percent ?
            Math.floor(255 * Math.min(100, num) / 100) : Math.min(255, num);
    }

    function rgbToHex(rgb) {
        var rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/;
        var result, r, g, b, hex = "";
        if ((result = rgbRegex.exec(rgb))) {
            r = componentFromStr(result[1], result[2]);
            g = componentFromStr(result[3], result[4]);
            b = componentFromStr(result[5], result[6]);

            hex = "0x" + (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1);
        }
        return hex;
    }

    function despintar(obj) {
        var color = obj.style.backgroundColor;
        var color2 = rgbToHex(color);
        if (color2 == '0xb3e5ff') {} else {
            obj.style.backgroundColor = "transparent";
        }
    }

    function pintar(obj) {
        var color = obj.style.backgroundColor;
        var color2 = rgbToHex(color);
        if (color2 == '0xb3e5ff') {} else {
            obj.style.backgroundColor = "#e6f7ff";
        }
    }
</script>