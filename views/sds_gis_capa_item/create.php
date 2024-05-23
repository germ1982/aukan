<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_gis_capa_item */

?>
<div class="sds-gis-capa-item-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
<div class="modal fade" id="modal_map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<?php

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg&libraries=places,drawing&callback');
$this->registerJsFile('@web/js/google_maps_zonas.js', ['depends' => 'yii\web\JqueryAsset']);
/*$this->registerJs(

    "$('#modal_map').modal('show');"
);*/
?>
