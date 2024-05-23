<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_padron */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-org-padron-form">

    <?php $form = ActiveForm::begin(['action' => ['mds_org_padron/importar'], 'id' => $model->formName()]); ?>

    <div class="row">
        <div class="col-md-8">
            <!-- periodo mes -->
            <?php
            $meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
            echo $form->field($model, 'mes')->dropDownList($meses);
            ?>
        </div>
        <div class="col-md-4">
            <!-- periodo año -->
            <?php
            $anios = array();
            $anios[] = '';
            $anio_actual = date('Y');
            for ($i = ($anio_actual - 10); $i <= $anio_actual; $i++) {
                $anios[$i] = $i;
            }
            echo $form->field($model, 'anio')->dropDownList($anios);
            ?>
        </div>
    </div>
    <div class="row">
        <div class='col-md-12'>
            <?php
            echo $form->field($model, 'temp_excel_import', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                ->widget(FileInput::classname(), [
                    'options' => ['accept' => '.xls,.xlsx', 'id' => 'excel_file'],
                    'language' => 'es',
                    'pluginOptions' => [
                        'allowedFileExtensions' => ['xls', 'xlsx'],
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'mainClass' => 'input-group-sm',
                        'maxFileSize' => 1000000
                    ]
                ])->label(false);
            ?>
        </div>
    </div>
    <div class="row">
        <div class='col-md-12'>
            <div id="resultado" style="height:300px;overflow: auto;">

            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


<?php
$script = <<<  JS
    var selectedFile = null;
    var resultado = new Array();

    $('#excel_file').on('change', function(event) {
        selectedFile = event.target.files[0];
    });
    $(document).ready(function() {        
        $("#btn_importar").click(function(){
            if (selectedFile != null) {
                let fileReader = new FileReader();
                fileReader.readAsBinaryString(selectedFile); 
                fileReader.onload = (event)=>{
                    let data = event.target.result;
                    let workbook = XLSX.read(data,{type:"binary", cellDates: true, dateNF: 'yyyy/mm/dd'});
                    let rows = new Array();        
                    let sheetObject = null;        
                    workbook.SheetNames.forEach(sheet => {
                        sheetObject = workbook.Sheets[sheet];
                        let rowObject = XLSX.utils.sheet_to_row_object_array(sheetObject);
                        varjson = JSON.stringify(rowObject,undefined,4);
                        rows.push(JSON.parse(varjson));
                    });
                    var resultado_html = "";
                    var index_fila = 0;
                    rows.forEach(sheet => {                            
                        sheet.forEach(fila =>{                                
                            //$("#resultado").html(index_fila+"/"+sheet.length);
                            procesarFila(sheetObject,fila);
                            index_fila++;
                        });                    
                    });                         
                    if (resultado.length>0){
                        $("#resultado").html(resultado.length+" datos pendientes para guardar.");
                        $("#resultado").html($("#resultado").html() + "<br>Guardando datos... <i class=\"fas fa-spinner fa-pulse\"></i>");
                        guardarResultado();
                    }                    
                    else {
                        $("#resultado").html(index_fila+" filas procesadas. No se encontraron datos para guardar.");
                    }
                }
            }
        });        
    });   
    
    function get_header_row(sheetObject) {
        var headers = [];
        var range = XLSX.utils.decode_range(sheetObject['!ref']);
        var C, R = range.s.r; /* start in the first row */
        /* walk every column in the range */
        for(C = range.s.c; C <= range.e.c; ++C) {
            var cell = sheetObject[XLSX.utils.encode_cell({c:C, r:R})] /* find the cell in the first row */

            var hdr = "SIN_NOMBRE_" + C; // <-- replace with your desired default 
            if(cell && cell.t) hdr = XLSX.utils.format_cell(cell);

            headers.push(hdr);
        }
        return headers;
    }

    function alphaToNum(alpha) {
        var i = 0,
            num = 0,
            len = alpha.length;

        for (; i < len; i++) {
            num = num * 26 + alpha.charCodeAt(i) - 0x40;
        }
        return num - 1;
    }

    function procesarFila(sheetObject,fila){
        var headers = get_header_row(sheetObject);
        var columnaP = fila[headers[alphaToNum('P')]];
        if (columnaP.includes('Activo')){
            //idunidadoperativa(B), Categoría(I), Apellido y Nombre (R), Sexo (S), DNI (U), CUIL (V), 
            //Fecha Nac (W), Fecha de Ingreso (BG), Antiguedad Administrativa (Y), Antiguedad Privada (AC), 
            //Antiguedad total (AD), Eventual (Columna N=28)
            var idunidadoperativa = fila[headers[alphaToNum('B')]];
            var legajo = fila[headers[alphaToNum('H')]];
            var categoria = fila[headers[alphaToNum('I')]];
            var apenom = fila[headers[alphaToNum('R')]];
            var sexo = fila[headers[alphaToNum('S')]];
            var dni = fila[headers[alphaToNum('U')]];
            var cuil = fila[headers[alphaToNum('V')]];
            var fecha_nac = fila[headers[alphaToNum('W')]];
            var fecha_ingr = fila[headers[alphaToNum('BH')]];
            var ant_adm = fila[headers[alphaToNum('Y')]];
            var ant_priv = fila[headers[alphaToNum('AC')]];
            var ant_total = fila[headers[alphaToNum('AD')]];
            var pr = fila[headers[alphaToNum('N')]];
            var titulo = fila[headers[alphaToNum('BF')]];
            var mes = get_mes($("#mds_org_padron-mes").val());
            var anio = $("#mds_org_padron-anio").val();
            var padron_elto = { 'idunidadoperativa':idunidadoperativa,
                                'categoria':categoria,
                                'legajo':legajo,
                                'apenom':apenom,
                                'sexo':sexo,
                                'dni':dni,
                                'cuil':cuil,
                                'fecha_nac':fecha_nac,
                                'fecha_ingr':fecha_ingr,
                                'ant_adm':ant_adm,
                                'ant_priv':ant_priv,
                                'ant_total':ant_total,
                                'pr': pr,
                                'titulo': titulo,
                                'mes':mes,
                                'anio':anio,
                            };
            resultado.push(padron_elto);
        }
    }

    function guardarResultado(){
        var parametros = {
            //"r": "mds_org_contacto/get_id_contacto_por_legajo",
            "registros": JSON.stringify(resultado)
        };
        $.ajax({
            data: parametros, //datos que se envian a traves de ajax
            url: "index.php?r=mds_org_padron/importar", //php que recibe la peticion
            type: 'post', 
            success: function(response) {
                console.log(response);
                $("#resultado").html($("#resultado").html().replace("<i class=\"fas fa-spinner fa-pulse\"></i>",""));
                if (response.guardados>0){
                    $("#resultado").html($("#resultado").html()+"<br><div class='text-success'>"+response.guardados+" registros guardados completamente. </div>");
                }
                if (response.errores.length>0){
                    $("#resultado").html($("#resultado").html()+"<br><div class='text-danger'>"+response.errores.length+" registros fallaron. Imprimiendo errores...</div>");
                    response.errores.forEach(function(error) {
                        if (Array.isArray(error)){
                            error.forEach(function(suberror) {
                                if (Array.isArray(suberror)){
                                    suberror.forEach(function(subsuberror) {
                                        $("#resultado").html($("#resultado").html()+"<div class=\"text-danger\">3- "+subsuberror+"</div>");
                                    });
                                }
                                else {
                                    $("#resultado").html($("#resultado").html()+"<div class=\"text-danger\">2- "+suberror+"</div>");
                                }
                            });
                        }
                        else {
                            if (error.legajo){
                                $("#resultado").html($("#resultado").html()+"<div class=\"text-danger\">1- "+error.legajo+"</div>");
                            }
                            else {
                                $("#resultado").html($("#resultado").html()+"<div class=\"text-danger\">1- "+error+"</div>");
                            }
                        }
                    });
                    window.scrollTo(0,300);
                }
            }
        });
       /*  $.post("index.php?r=mds_org_padron/importar&registros="+JSON.stringify(resultado), function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
               
            } else {
                                      
            }
        });*/
    }

    function get_mes(mes)
    {
        switch (mes)
        {
            case "Enero":
                mes = 1;
                break;

            case "Febrero":
                mes = 2;
                break;

            case "Marzo":
                mes = 3;
                break;

            case "Abril":
                mes = 4;
                break;
            case "Mayo":
                mes = 5;
                break;

            case "Junio":
                mes =  6;
                break;

            case "Julio":
                mes =  7;
                break;

            case "Agosto":
                mes =  8;
                break;
            case "Septiembre":
                mes = 9;
                break;

            case "Octubre":
                mes = 10;
                break;

            case "Noviembre":
                mes = 11;
                break;

            case "Diciembre":
                mes = 12;
                break;
        } 
        return mes;
    }

JS;

$this->registerJs($script);
