<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Progress;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_licencia */
/* @var $form yii\widgets\ActiveForm */
?>
<input type="hidden" id="input_idfactura" name="input_idfactura" value="<?= $idfactura?>">

<script>
gif = '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';
let selectedFile;
//console.log(window.XLSX);
document.getElementById('input').addEventListener("change", (event) => {
    selectedFile = event.target.files[0];
    document.getElementById("estado").innerHTML = "Estado: esperando orden para realizar la importación...";
})

let data=[{
    "name":"jayanth",
    "data":"scd",
    "abc":"sdef"
}]


document.getElementById('button').addEventListener("click", () => {//aca espera el clic al boton de importar
    document.getElementById("jsondata").innerHTML = "";
    document.getElementById("otro_progreso").style.width = '0%';
    //alert('click');
    document.getElementById("estado").innerHTML = "Estado: importando...";
    XLSX.utils.json_to_sheet(data, 'out.xlsx');
    if(selectedFile){
        document.getElementById("estado").innerHTML = "Estado: Importando datos de excel a JSON " + '<img src="data:image/gif;base64,'+gif+'" alt=""  height="10px" />';
        let fileReader = new FileReader();
        fileReader.readAsBinaryString(selectedFile);
        fileReader.onload = (event)=>{
         let data = event.target.result;
         let workbook = XLSX.read(data,{type:"binary", cellDates: true, dateNF: 'yyyy/mm/dd'});
         //console.log(workbook);
         hoja = 1;
         workbook.SheetNames.forEach(sheet => {
                let rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
                
                //console.log(rowObject);
                aux_varjson = JSON.stringify(rowObject,undefined,4);
                
                if(hoja==1)
                    {
                        document.getElementById("estado").innerHTML = "Estado: JSON Generado...";
                        varjson = aux_varjson;
                    }
                hoja++;    
            });
            
            document.getElementById("jsondata").innerHTML = varjson;
            document.getElementById("estado").innerHTML = "Estado: Procesando informacion...";
            
            procesar_factura(varjson);

            
            marcar_final();

        }
    }
});


function procesar_factura(datos)
    {
        //var url = "index.php?r=mds_hor_licencia/procesar_factura";
        var url = "index.php?r=sds_cel_factura/procesar_factura";
        var idfactura =  $('#input_idfactura').val();
        
        var aux
        var parametros = {
            "datos": datos,
            "idfactura": idfactura,          
        };
        $.ajax({
            url: url, //php que recibe la peticion
            data: parametros,
            type: 'post', 
            async: false,
            success: function(data) { //aca recibe el json del php que guarda o dice si ya existia
                console.log(data);
                //aux = data;
                document.getElementById("jsondata").innerHTML = data;
            }
        });
        //return aux;
    }



async function marcar_final()
    {
        const result = await resolveonesecond();
        document.getElementById("estado").innerHTML = "Estado: Proceso Finalizado...";
    }

function resolveonesecond() {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve('resolved');
    }, 500);
  });
}
</script>
<div class="sds-cel-factura-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-md-12' >

            <?=html::fileInput('input',
                                'lalalla', 
                                [ 'id' => 'input', 
                                    'class' => 'btn btn-default pull-left',
                                    'accept' => '.xls,.xlsx',
                                ]
                                                    
                                );?>
            <?=Html::button('Importar', ['id' => 'button', 'class' => 'btn btn-default pull-left']);?>
            <?=Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);?>  

        </div>
    </div>
    <br>
    <div class="row">
        <div class='col-md-12'>
            <pre id="estado">Estado: esperando seleccion de archivo</pre>    
        </div> 
    </div>
    <div class="row">
        <div class='col-md-12'>
            <?=Progress::widget([
                'percent' => 0,
                'label' => '',
                'barOptions' => ['class' => 'progress-bar-success', 'id' => 'otro_progreso'],
                'options' => ['class' => 'active progress-striped','id' => 'progreso']
            ]);?>
        </div> 
    </div>

    <div class="row">
        <div class='col-md-12'>
		    <pre id="jsondata"></pre>
        </div> 
    </div>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
