<?php

use yii\helpers\Url;
use yii\helpers\Html;

function campo($titulo, $contenido, $esArchivo = false)
{
    // Si el campo es un archivo, se trata de una previsualización
    if ($esArchivo) {
        // Verificamos si existe un archivo adjunto
        if ($contenido) {
            $imagePath = Url::to('uploads/legajo_runneu/' . $contenido);

            // Determinamos el tipo de archivo (imagen o PDF)
            $fileExtension = pathinfo($contenido, PATHINFO_EXTENSION);

            // Si el archivo es una imagen, mostramos la imagen
            if (in_array($fileExtension, ['jpg', 'jpeg', 'gif', 'png'])) {
                $contenido = Html::img($imagePath, ['class' => 'file-preview-image', 'alt' => 'Imagen', 'title' => $contenido, 'width' => '100%', 'height' => 'auto']);
            } elseif ($fileExtension === 'pdf') {
                // Mostrar el archivo PDF en un objeto
                $contenido = Html::tag('object', '', ['data' => $imagePath, 'type' => 'application/pdf', 'width' => '100%', 'height' => '300px']);
            } else {
                // Si es otro tipo de archivo, mostrar un enlace para descargar
                $contenido = Html::a('Descargar archivo', $imagePath, ['class' => 'btn btn-primary', 'target' => '_blank']);
            }
        } else {
            $contenido = 'No hay archivo adjunto.';
        }
    }

    echo "<h5><b>$titulo: </b></h5>
          <p class='campo'>
              $contenido
          </p>";
}

/* @var $this yii\web\View */
/* @var $model app\models\RunneuLegajo */
?>

<div class="runneu-legajo-view">

    <div class="row">
        <div class="col-md-12">

            <div class="col-md-6">
                <?= campo('num_legajo', $model->num_legajo) ?>
            </div>

            <div class="col-md-6">
                <?= campo('dni', $model->dni) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= campo('archivo_adjunto', $model->archivo_adjunto, true) ?> 
        </div>
    </div>
</div>