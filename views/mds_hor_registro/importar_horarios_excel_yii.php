<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Progress;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_licencia */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
    gif = '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';
    let selectedFile;
    console.log(window.XLSX);
    document.getElementById('input').addEventListener("change", (event) => {
        selectedFile = event.target.files[0];
        //document.getElementById("estado").innerHTML = " Estado: esperando orden para realizar la importación...";
        document.getElementById("progress-text").innerHTML = "Esperando orden para realizar la importación...";
    })

    let data = [{
        "name": "jayanth",
        "data": "scd",
        "abc": "sdef"
    }]


    document.getElementById('button').addEventListener("click", () => { //aca espera el clic al boton de importar
        document.getElementById("jsondata").innerHTML = "";
        //document.getElementById("otro_progreso").style.width = '0%';
        //alert('click');
        //document.getElementById("estado").innerHTML = "Estado: importando...";
        document.getElementById("progress-bar").setAttribute('aria-valuenow', 20);
        document.getElementById("progress-bar").setAttribute('style', 'width: 20%');
        document.getElementById("progress-text").innerHTML = "Importando...";

        //document.getElementById("estado").innerHTML = "Estado: importando...";
        XLSX.utils.json_to_sheet(data, 'out.xlsx');
        if (selectedFile) {
            //document.getElementById("estado").innerHTML = "Estado: Importando datos de excel a JSON " + '<img src="data:image/gif;base64,' + gif + '" alt=""  height="10px" />';
            document.getElementById("progress-bar").setAttribute('aria-valuenow', 40);
            document.getElementById("progress-bar").setAttribute('style', 'width: 40%');
            document.getElementById("progress-text").innerHTML = "Importando datos de excel a JSON " + '<img src="data:image/gif;base64,' + gif + '" alt=""  height="10px" />';
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(selectedFile);
            fileReader.onload = (event) => {
                let data = event.target.result;
                let workbook = XLSX.read(data, {
                    type: "binary",
                    cellDates: true,
                    dateNF: 'yyyy/mm/dd'
                });
                console.log(workbook);
                hoja = 1;
                workbook.SheetNames.forEach(sheet => {
                    let rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);

                    console.log(rowObject);
                    aux_varjson = JSON.stringify(rowObject, undefined, 4);

                    if (hoja == 3) {
                        //document.getElementById("estado").innerHTML = "Estado: JSON Generado...";
                        document.getElementById("progress-bar").setAttribute('aria-valuenow', 60);
                        document.getElementById("progress-bar").setAttribute('style', 'width: 60%');
                        document.getElementById("progress-text").innerHTML = "JSON Generado...";
                        varjson = aux_varjson;
                    }
                    hoja++;
                });

                document.getElementById("jsondata").innerHTML = varjson;
                //document.getElementById("estado").innerHTML = "Estado: Procesando informacion...";
                document.getElementById("progress-bar").setAttribute('aria-valuenow', 80);
                document.getElementById("progress-bar").setAttribute('style', 'width: 80%');
                document.getElementById("progress-text").innerHTML = "Procesando informacion...";
                //var aux = procesar_info(varjson);
                console.log('json rescatado del excel');
                console.log(varjson);
                aux = procesar_horarios_excel(varjson, selectedFile.name);

                document.getElementById("jsondata").innerHTML = aux;
                marcar_final();

            }
        }
    });


    function procesar_horarios_excel(datos, file_name) {
        var aux
        var parametros = {
            "datos": datos,
            "file_name": file_name
        };
        $.ajax({
            url: "index.php?r=mds_hor_registro/procesar_horarios_excel", //php que recibe la peticion
            data: parametros,
            type: 'post',
            async: false,
            success: function(data) { //aca recibe el json del php que guarda o dice si ya existia
                console.log('log_horarios: ' + data);
                aux = data;
            }
        });
        return aux;
    }

    async function marcar_final() {
        const result = await resolveonesecond();
        //document.getElementById("estado").innerHTML = "Estado: Proceso Finalizado...";
        document.getElementById("progress-bar").setAttribute('aria-valuenow', 100);
        document.getElementById("progress-bar").setAttribute('style', 'width: 100%');
        document.getElementById("progress-text").innerHTML = "Proceso Finalizado.";
        document.getElementById("progress-bar").classList.add('progress-bar-success');

    }

    function resolveonesecond() {
        return new Promise(resolve => {
            setTimeout(() => {
                resolve('resolved');
            }, 800);
        });
    }
</script>

<div class="mds-hor-registro-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-md-12'>

            <?= html::fileInput(
                'input',
                'lalalla',
                [
                    'id' => 'input',
                    'class' => 'btn btn-default pull-left col-md-12',
                    'accept' => '.xls,.xlsx',
                ]

            ); ?>
        </div>
    </div>
    <br>
    <div class="row">
        <!-- <div class="col-md-12" style="display: none;">
            <div class='progress'> 
                <div  class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 0%;'> 
                    <span style="padding-left:5px;">Esperando...</span>
                </div>
            </div>
        </div> -->

        <div class="col-md-12">
            <div class='progress text-center'> 
                <div id='progress-bar' class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 0%;'> 
                </div>
            </div>
            <span id="progress-text" class="col-md-7 text-center" style="position: absolute; top: -1px; left: 20%; color: #fff;">Esperando seleccion de archivo Excel...</span>
        </div>

        <!-- <div class='col-md-12'>
            <pre id="estado">Estado: esperando seleccion de archivo</pre>
        </div> -->
    </div>


    <div class="row">
        <div class='col-md-12'>
            <pre id="jsondata"></pre>
        </div>
    </div>



    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <br>
    <div class="row">
        <div class="col-md-6">
            <?= Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-danger pull-left', 'data-dismiss' => "modal"]); ?>    
        </div>
        <div class="col-md-6">
            <?= Html::button('Importar', ['id' => 'button', 'class' => 'btn btn-primary pull-right']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>