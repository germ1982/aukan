<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use kartik\file\FileInput;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$persona_nombre = '';
// Suponiendo que el modelo tiene un atributo 'foto' que guarda el nombre del archivo de la imagen
$initialPreview = [];
$initialPreviewConfig = [];





if (isset($model->foto)) {
    $imagePath = Url::to('img/empleados-fotos/' . $model->foto);

    // Agrega la imagen a la vista previa inicial
    $initialPreview = [
        Html::img($imagePath, ['class' => 'file-preview-image', 'alt' => 'Foto', 'title' => $model->foto, 'width' => '100%', 'height' => 'auto']),
    ];
}

?>
<style>
    /* Estilo para las pestañas (tabs) */
    .nav-tabs {
        border-bottom: 2px solid #ddd;
        /* Línea de borde debajo de las pestañas */
    }

    .nav-tabs>li>a {
        background-color: #2B3E4C;
        /* Fondo gris claro para las pestañas */
        color: #F4DFB9;
        /* Color de texto gris */
        border-radius: 8px 8px 0 0;
        /* Bordes redondeados en la parte superior */
        padding: 10px 20px;
        /* font-weight: bold; */
        text-transform: uppercase;
        /* Texto en mayúsculas */
        transition: background-color 0.3s ease, color 0.3s ease;
        /* Transición suave */
    }

    .nav-tabs>li>a:hover {
        background-color: #87B867;
        /* Color de fondo en hover */
        color: white;
        /* Color de texto en hover */
    }

    .nav-tabs>li.active>a {
        background-color: #87B867;
        /* Color de fondo de la pestaña activa */
        color: white;
        /* Color de texto en la pestaña activa */
        box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
        /* Sombra para darle un efecto destacado */
    }

    .nav-tabs>li.active>a:hover {
        background-color: #87B867;
        /* Sombra más oscura en hover de la pestaña activa */
    }

    /* Estilo para el contenido de la pestaña */
    .tab-content {
        background-color: #fff;
        /* Fondo blanco para el contenido */
        padding: 20px;
        border-radius: 0 0 8px 8px;
        /* Bordes redondeados en la parte inferior */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        /* Sombra suave para el contenido */
    }
</style>
<div class="empleado-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-8">
            <?=$this->render('_tab1', ['form' => $form, 'model' => $model, 'persona_nombre' => $persona_nombre]);?>
        </div>
        <div class="col-md-4">
            <?= $this->render('_tab3', ['form' => $form, 'model' => $model, 'initialPreview' => $initialPreview, 'initialPreviewConfig' => $initialPreviewConfig]); ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $this->render('_tab2', ['form' => $form, 'model' => $model]); ?>
        </div>
    </div>


    <?php

    /* Tabs::widget([
        'items' => [
            [
                'label' => 'Información Básica',
                'content' => $this->render('_tab1', ['form' => $form, 'model' => $model, 'persona_nombre' => $persona_nombre]),
                'active' => true, // Define la pestaña activa

            ],
            [
                'label' => 'Detalles Adicionales',
                'content' => $this->render('_tab2', ['form' => $form, 'model' => $model]),
            ],

            [
                'label' => 'Archivos',
                'content' => $this->render('_tab3', ['form' => $form, 'model' => $model, 'initialPreview' => $initialPreview, 'initialPreviewConfig' => $initialPreviewConfig]),
            ],
            // Agrega más pestañas según necesites
        ],
    ]); */
    ?>



    <?php ActiveForm::end(); ?>

</div>