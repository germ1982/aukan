<?php

use yii\helpers\Url;
use yii\helpers\Html;

function campo($contenido, $esArchivo = false)
{
    // Si el campo es un archivo, se trata de una previsualización
    if ($esArchivo) {
        // Verificamos si existe un archivo adjunto
        if ($contenido) {
            $ts = time();
            $imagePath = Yii::$app->request->hostInfo . '/uploads_datafam/registro_familia_legajos/' . $contenido . '?t=' . $ts;
            
                $contenido = Html::tag('object', '', ['data' => $imagePath, 'type' => 'application/pdf', 'width' => '100%', 'height' => '470px']);
            } 
        else {
            $contenido = 'No hay archivo adjunto.';
        }
    }

    echo "$contenido";
}


?>

<div class="runneu-legajo-view">
<div class="row">
<div class="col-md-12">
    <?=$model->observacion?>
</div>
</div>

    <div class="row">
        <div class="col-md-12">
            <?= campo($model->archivo_adjunto, true) ?> <!-- Aquí pasamos 'true' para indicar que es un archivo -->
        </div>
    </div>
</div>

