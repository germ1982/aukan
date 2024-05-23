<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_contacto */
?>
<div>
    <div class="row">
        <?php $form = ActiveForm::begin(['action' => ['pase_planta']]); ?>
            <div class="col-md-6">
                <?= $form->field($model, 'fecha_ingreso_planta')->widget(DatePicker::class, [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}',
                    'options' => [
                        'class' => 'form-control input-md',
                        'id'=>'fecha_ingreso',
                        'placeholder' => 'DD / MM / YYYY'
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ]
                ]);?>
            </div>
            <div class='col-md-6'>
                <?php
                echo $form->field($model, 'temp_excel_import', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                ->widget(FileInput::class, [
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
                ])->label('Excel a Importar');
                ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="row">
        <div class='col-md-12'>
            <div id="resultado" style="height:300px;overflow: auto;"></div>
        </div>
    </div>
</div>

<?php
$script = <<<  JS
    var selectedFile = null;
    var resultado = new Array();
    var fecha_ingreso = $("#fecha_ingreso").val();

    $('#excel_file').on('change', function(event) {
        selectedFile = event.target.files[0];
    });

    $("#fecha_ingreso").on('change', function(event){
        fecha_ingreso = $("#fecha_ingreso").val();
    })

    $(document).ready(function() {
        $("#btn_importar").click(function(){
            if((selectedFile != null) && (fecha_ingreso!='')) {
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
                            procesarFila(sheetObject,fila);
                            index_fila++;
                        });                    
                    });                         
                    if (resultado.length>0){
                        $("#resultado").html(resultado.length+" filas detectadas.");
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
        var legajo = fila[headers[alphaToNum('D')]];
        var categoria= fila[headers[alphaToNum('E')]];

        var ingreso = {
            'legajo' : legajo,
            'categoria' : categoria
        }
        resultado.push(ingreso);
    }

    function guardarResultado(){
        var parametros = {
            "registros": JSON.stringify(resultado),
            "fecha_ingreso": fecha_ingreso
        };
        $.ajax({
            data: parametros, //datos que se envian a traves de ajax
            url: "index.php?r=mds_org_contacto/pase_planta", //php que recibe la peticion
            type: 'post',
            success: function(response){
                console.log(response);
                $("#resultado").html($("#resultado").html().replace("<i class=\"fas fa-spinner fa-pulse\"></i>",""));
                if (response.guardados>0){
                    $("#resultado").html($("#resultado").html()+"<br><div class='text-success'><b>¡"+response.guardados+" registros guardados de manera correcta!.</b></div>");
                }
                if(response.errores.length>0){
                    $("#resultado").html($("#resultado").html()+"<br><div class='text-danger'><b>¡"+response.errores.length+" registros fallaron!.</b><br>Imprimiendo errores...</div>");
                    response.errores.forEach(function(error){
                        /*
                        if (Array.isArray(error)){
                            error.forEach(function(suberror){
                                if (Array.isArray(suberror)){
                                    suberror.forEach(function(subsuberror) {
                                        $("#resultado").html($("#resultado").html()+"<div class=\"text-danger\">3- "+subsuberror+"</div>");
                                    });
                                }else{
                                    $("#resultado").html($("#resultado").html()+"<div class=\"text-danger\">2- "+suberror+"</div>");
                                }
                            });
                        }else{
                            console.log(error);
                            $("#resultado").html($("#resultado").html()+"<div class=\"text-danger\">"+error+" [Error_code 1]</div>");
                        }
                        console.log(error);*/
                        $("#resultado").html($("#resultado").html()+"<div class=\"text-danger\">"+error);
                    });
                    //window.scrollTo(0,300);
                }
                /*
                if(response.errores.length>0 && !response.bulkDelete){
                    $("#resultado").html($("#resultado").html()+"<br><div class='text-danger'><b>¡Falla al procesar los datos!.</b><br>Imprimiendo errores...</div>");
                    $("#resultado").html($("#resultado").html()+"<div class=\"text-danger\">* "+response.errores+"</div>");
                }
                */
            }
        });
    }
        
JS;
$this->registerJs($script);