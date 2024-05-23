<?php

use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;


?>

<head>
	<!-- <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>	 -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
        crossorigin="anonymous"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
    <script type="text/javascript" src="AutoSolicitud/Lib/jquery-2.0.3.min.js"></script>   

</head>

<div class="mds-hor-registro-import-form" >
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-md-12' >

            <?=html::fileInput('input','lalalla', ['id' => 'input', 'class' => 'btn btn-default pull-left']);?>
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
		    <pre id="jsondata"></pre>
        </div> 
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script src="AutoSolicitud/Exell/mds_hor_registro_excel_importar.js"></script>