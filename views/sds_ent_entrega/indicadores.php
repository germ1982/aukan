<div class="row" id="filter_anio">
    <div class="col-md-2 col-md-offset-9" style="text-align:right;">
        <h5>Año Aplicado: </h5>
    </div>
    <div class="col-md-1" style="padding-bottom: 1%;">
        <select class="form-control" data-placeholder="Año..." id="cmbAnio" name="cmbAnio" style="padding-left: 2px;">

        </select>
    </div>
</div>
<div class="row" id="ind_general">

</div>
<div class="row" id="ind_entregas">

</div>
<div class="row" id="ind_tipos">

</div>
<?php
$this->registerJsFile('@web/js/indicadores_entregas.js', ['depends' => 'yii\web\JqueryAsset']);
?>