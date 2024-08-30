<?php

use yii\helpers\Html;
use app\models\Articulo;
use app\models\Configuracion;
use Codeception\Specify\ConfigBuilder;

function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}
$articulo = Configuracion::findOne($model->idtipo)->descripcion . " " . Configuracion::findOne($model->idmarca)->descripcion . " " . $model->modelo ; 

?>
<style>
    #base64image {
        display: block;
        border: ridge 1px;
        padding: 8px;
        border-color: #E6E6E6;
        max-width: 100%;
    }

    .campo {
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>



<div class="articulo-view">

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                <?= campo('idarticulo', "$articulo") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?= campo('idarticulo', "$model->idarticulo") ?>
                </div>            
                                
                <div class="col-md-3">
                    <?= campo('idtipo', Configuracion::findOne($model->id_unidad_medida)->descripcion) ?>
                </div>
                <div class="col-md-3">
                    <?= campo('Rubro', Configuracion::findOne($model->idrubro)->descripcion) ?>
                </div>
                <div class="col-md-3">
                    <?= campo('activo', $model->activo ? "SI" : "NO") ?>
                </div>
                    
                
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= campo('descripcion', "$model->descripcion") ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= Html::img('img/articulos/' . $model->imagen, ['id' => 'base64image']); ?>
            </div>
        </div>
    </div>
</div>