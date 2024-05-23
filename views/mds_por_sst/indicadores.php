<?php

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;

$idusuario = Yii::$app->user->identity->idusuario;
$permisos_subsidios = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and iditem in (" . Mds_seg_item::MODULO_SST_INDICADORES . "," . Mds_seg_item::MODULO_POR_DESEMPLEO . "," .
    Mds_seg_item::MODULO_POR_FAMILIA . "," . Mds_seg_item::MODULO_POR_SST . ")")->all();

$permiso_indicadores = false;
$permiso_fam = 0;
$permiso_des = 0;
$permiso_sst = 0;
foreach ($permisos_subsidios as $permiso) {
    if ($permiso->iditem == Mds_seg_item::MODULO_SST_INDICADORES) {
        $permiso_indicadores = true;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_POR_DESEMPLEO) {
        $permiso_des = 1;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_POR_FAMILIA) {
        $permiso_fam = 1;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_POR_SST) {
        $permiso_sst = 1;
    }
}
if (!$permiso_indicadores) {
    Yii::$app->session->setFlash('error_modulo', "Usted no posee permisos para ingresar al módulo. <br>Comuníquese con un administrador.");
    return Yii::$app->getResponse()->redirect([
        'site',
    ]);
}

?>

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
<div class="row" id="ind_tipos">

</div>
<input type="hidden" id="permiso_fam" value="<?= $permiso_fam ?>">
<input type="hidden" id="permiso_des" value="<?= $permiso_des ?>">
<input type="hidden" id="permiso_sst" value="<?= $permiso_sst ?>">
<?php
$this->registerJsFile('@web/js/indicadores_subsidios.js', ['depends' => 'yii\web\JqueryAsset']);
?>