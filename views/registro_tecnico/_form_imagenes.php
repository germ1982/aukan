<?php
use app\models\Imagenes;
use app\models\RegistroTecnico;
use app\models\ConstantesGlobales;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\RegistroTecnico $model */

// Publicar y registrar el archivo JS que está dentro de views/registro_tecnico/
$jsFilePath = Yii::getAlias('@app/views/registro_tecnico/_form_imagenes.js');
$publishedJs = Yii::$app->assetManager->publish($jsFilePath);

$urlDeleteFoto = Url::to(['registro_tecnico/delete-foto']);
$this->registerJs("var urlDeleteFoto = '{$urlDeleteFoto}';", \yii\web\View::POS_HEAD);
$this->registerJsFile($publishedJs[1], ['depends' => [\yii\web\JqueryAsset::class]]);

// views/registro_tecnico/_form_imagenes.php

$imagenesExistentes = [];
if (!$model->isNewRecord) {
    // Consulta limpia apuntando a idreferencia
    $imagenesExistentes = Imagenes::find()
        ->where([
            'idmodulo'     => ConstantesGlobales::REGISTRO_TECNICO_INFORMATICA,
            'idregistro' => $model->idregistro, // <-- Filtrado por idregistro
        ])
        ->all();
}
?>

<style>
    .panel-heading {

    padding: 5px;

}

.panel-title {
    color: #8b8b8b;
    font-size: 12px;

}
</style>

<div class="panel panel-default">
    <div class="panel-body">
        
        <!-- Botones de Acción -->
        <div class="row" style="margin-bottom: 15px; display: flex; justify-content: center; align-items: center;">
            <div class="col-md-6" style="text-align: end;">
                <h3 class="panel-title"><i class="fa fa-camera"></i> Adjuntar Fotos</h3>
            </div>
            <div class="col-md-6" style="text-align: left;">
                <!-- <button type="button" class="btn btn-info" id="btn-abrir-camara">
                    <i class="fa fa-video-camera"></i> Usar Cámara
                </button> -->
                <label class="btn btn-primary" style="margin-bottom: 0;">
                    <i class="fa fa-upload"></i> Seleccionar
                    <input type="file" id="input-archivo-fotos" accept="image/*" multiple style="display: none;">
                </label>
            </div>
        </div>

        <!-- Contenedor Stream Cámara -->
        <div id="contenedor-camara" style="display: none; margin-bottom: 15px;" class="well">
            <div class="row">
                <div class="col-md-8">
                    <video id="video-stream" width="100%" height="auto" autoplay playsinline style="border: 1px solid #ccc; background: #000;"></video>
                </div>
                <div class="col-md-4 text-center" style="padding-top: 20px;">
                    <button type="button" class="btn btn-success btn-lg btn-block" id="btn-capturar-foto">
                        <i class="fa fa-circle"></i> Capturar Foto
                    </button>
                    <button type="button" class="btn btn-danger btn-block" id="btn-cerrar-camara" style="margin-top: 10px;">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                </div>
            </div>
        </div>

        <canvas id="canvas-procesamiento" style="display: none;"></canvas>

        <!-- Fotos Existentes -->
        <?php if (!empty($imagenesExistentes)): ?>
            <h4>Fotos Guardadas</h4>
            <div class="row" id="galeria-existentes" style="margin-bottom: 15px;">
                <?php foreach ($imagenesExistentes as $img): ?>
                    <div class="col-xs-6 col-sm-3 col-md-2 text-center img-contenedor-item" id="foto-item-<?= $img->idimagen ?>" style="margin-bottom: 10px;">
                        <div class="thumbnail" style="padding: 4px; position: relative;">
                            <img src="<?= $img->getUrl() ?>" alt="Foto" class="img-responsive" style="height: 100px; object-fit: cover; width: 100%;">
                            <button type="button" 
                                    class="btn btn-danger btn-xs btn-eliminar-foto-existente" 
                                    data-id="<?= $img->idimagen ?>"
                                    style="position: absolute; top: 6px; right: 6px;" 
                                    title="Eliminar foto">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Previsualización Nuevas Fotos -->
        <h5>Nuevas Fotos a Adjuntar</h5>
        <div class="row" id="contenedor-previsualizacion-nuevas"></div>
        <div id="contenedor-inputs-base64"></div>

    </div>
</div>