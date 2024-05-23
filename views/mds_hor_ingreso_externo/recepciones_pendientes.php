<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use johnitvn\ajaxcrud\CrudAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

CrudAsset::register($this);

$this->title = 'Recepciones pendientes';
?>
<header class="page-header ">
    <style>
        .sombra {
            box-shadow: 1px 2px 4px black;
            transform: scale(1.05);
            padding-left: 25px;
            padding-right: 25px;


        }

        .fila {
            background-color: #0c5284;
        }

        .turnero {
            /* background-color: #49708a; */
            background-color: #0a6c9d;
            color: white;
            border: 1px solid black;
            font-size: 20px;
        }

        .fondo {
            background-color: #d0e0eb;

        }

        .persona {

            border-bottom: 1px solid black;
            margin-top: 5px;
            margin-bottom: 5px;
            padding: 18px;
        }

        .letras {
            color: white;
            font-style: italic;
        }

        .panel {
            padding: 10px;
        }

        .content-body {
            background-color: rgba(90, 150, 200, 0.7);
            /* background-color: #d0e0eb; */
            height: 100vh;
        }
    </style>
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">

        <ol class="breadcrumbs  ">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li>
                <a href="index.php?r=mds_hor_ingreso_externo">
                    Ingreso de Externos
                </a>
            </li>
            <li><span>Recepciones pendientes</span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row ">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel ">
            <div class="panel-header">
                <a href="" id="btn-refresh"></a>
            </div>
            <div class="panel-body sombra fila">
                <div id="ajaxCrudDatatable">
                    <div class="Container ">
                        <div class="row">
                            <div class="col-md-2 letras text-center"><b> LLEGADA </b></div>
                            <div class="col-md-4 letras text-center"><b> PERSONA </b></div>
                            <div class="col-md-6 letras text-center"><b> MOTIVO</b></div>
                        </div>
                        <div class="row rounded turnero shadow-lg">
                            <?php
                            foreach ($dataProvider->getModels() as $model) : ?>
                                <div class="col-md-2 persona text-center">
                                    <b><?= $model->fecha_hora = date('d/m H:i', strtotime(str_replace('-', '/', $model->fecha_hora))); ?></b>
                                </div>
                                <div class="col-md-4 text-center persona">
                                    <?php
                                    $persona = Sds_com_persona::findOne($model->idpersona);
                                    if ($persona != null) {
                                        echo  "$persona->apellido, $persona->nombre";
                                    }
                                    ?>
                                </div>
                                <div class="col-md-6 text-center persona">
                                    <?php $model = Sds_com_configuracion::findOne($model->motivo);
                                    if (!($model == null)) {
                                        echo $model->descripcion;
                                    } else {
                                        echo "No encontrado";
                                    } ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
        $('#btn_refresh').click();  
    });

    window.setTimeout( function() {
        window.location.reload();
      }, 10000);
    
    setInterval(function(){if (!$(\"#ajaxCrudModal\").hasClass('fade modal in')) $('#full-screen').click();}, 3000);"
);

Modal::begin([
    "id" => "ajaxCrudModal",
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]);
Modal::end(); ?>