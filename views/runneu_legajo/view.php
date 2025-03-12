<?php



use yii\widgets\DetailView;
function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}

/* @var $this yii\web\View */
/* @var $model app\models\RunneuLegajo */
?>
<div class="runneu-legajo-view">
<div class="organismo-view">
    <div class="row">
        <div class=" col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <?= campo('num_legajo', $model->num_legajo) ?>
                </div>
                <div class="col-md-4">
                    <?= campo('dni', $model->dni) ?>
                </div>
                <div class="col-md-4">
                    <?= campo('archivo_adjunto', $model->archivo_adjunto) ?>
                </div>                
            </div>
        </div>
 
   <!--  <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'num_legajo',
            'dni',
            'archivo_adjunto',
        ],
    ]) ?> -->

</div>
