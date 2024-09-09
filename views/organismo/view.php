<?php

use app\models\Organismo;
use yii\widgets\DetailView;

function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}
if($model->padre)
{$padre=Organismo::get_organismo($model->padre)->descripcion;}
else {$padre='raiz';}
/* @var $this yii\web\View */
/* @var $model app\models\Organismo */
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
        padding: 4px 8px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>
<div class="organismo-view">
    <div class="row">
        <div class=" col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <?= campo('idorganismo', "$model->idorganismo") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('descripcion', "$model->descripcion") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('padre', "$padre") ?>
                </div>                
            </div>
        </div>

        <div class=" col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <?= campo('nivel', "$model->nivel") ?>
                </div> 
                <div class="col-md-4">
                    <?= campo('abreviatura', "$model->abreviatura") ?>
                </div> 
                <div class="col-md-4">
                    
                    <?= campo('activo', "$model->activo") ?>
                </div>                
            </div>
        </div>
    </div>
</div>

