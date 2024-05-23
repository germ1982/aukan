<?php

use app\controllers\SiteController;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use yii\widgets\ActiveForm;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_stk_entrega;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$model = new Sds_com_configuracion();
$model_entrega = new Sds_stk_entrega();
$model_entrega->fecha_hora = date('d/m/Y');
$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$idcontacto = Yii::$app->user->identity->idcontacto;
$permiso_consultar = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::STK_CONSULTAR_MINIMO . ")")->one();
$permiso_consultar = $permiso_consultar != null ? 1 : 0;
$id_organismo = 0;
if ($usuario->organismo_stock) {
    $id_organismo = $usuario->organismo_stock;
    $model->idconfiguracion = Mds_org_organismo::findOne($id_organismo)->idrubro;
}

?>


<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-5">
        <?= $form->field($model, 'idconfiguracion')->dropdownList(
            ArrayHelper::map(Sds_com_configuracion::findBySql(
                "select idconfiguracion, descripcion from sds_com_configuracion 
                                where idconfiguracion = 1 or idconfiguraciontipo = " . Sds_com_configuracion_tipo::TIPO_RUBRO . " 
                                and activo = 1 
                                and idconfiguracion in (select a.rubro from sds_stk_articulo a where a.organismo = $id_organismo)
                                order by descripcion"
            )->all(), 'idconfiguracion', 'descripcion'),
            [
                'prompt' => 'Todos',
                'id' => 'cmb_rubro',
                'onchange' => 'refrescar_grilla();',

            ]
        )->label('Filtrar por Rubro');
        ?>
    </div>
    <div class="col-md-3">
        <!-- FECHA -->
        <?= SiteController::actionGet_input_fecha($form, $model_entrega, 'fecha_hora', 'fecha_hasta', 'Fecha Hasta') ?>
    </div>
    <div class="col-md-2" style="text-align:right">
        <?= $permiso_consultar == 1 ? Html::a('Ver Stock Mín. <span class= "fas fa-eye"></span>', "#", [
            'title' => "Consultar Stock Mínimo ",
            'id' => "btn_consultar",
            'value' => 1,
            'role' => 'post', 'data-pjax' => 0,
            'data-toggle' => 'tooltip',
            'onclick' => '$("#loading").show();refrescar_grilla(1);'
        ]) : "";
        ?>
    </div>
    <div class="col-md-2" style="text-align:right" id="div_boton">

    </div>
</div>
<div class="row">
    <div class="col-md-12" id="div_grilla">

    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
    refrescar_grilla();

    function refrescar_grilla() {
        var idrubro = $('#cmb_rubro').val();
        var ver_minimo = $('#btn_consultar').val();
        var fecha_hasta = $('#fecha_hasta').val();
        var aux = "index.php?r=sds_stk_articulo/get_grilla_stock_general&ver_minimo=" + ver_minimo;
        if (idrubro) {
            aux = aux + "&rubro=" + idrubro;
        }
        if (fecha_hasta) {
            aux = aux + "&fecha_hasta=" + fecha_hasta;
        }
        $.post(aux, function(data) {
            $("#div_grilla").html(data);
            $("#loading").hide();
        });
        set_boton_imprimir();
        $('#btn_consultar').val(ver_minimo == 1 ? 0 : 1);
    }

    function set_boton_imprimir() {
        var idrubro = $('#cmb_rubro').val();
        var ver_minimo = $('#btn_consultar').val();
        var fecha_hasta = $('#fecha_hasta').val();
        var aux = "index.php?r=sds_stk_articulo/get_boton_imprimir&ver_minimo=" + ver_minimo;
        if (idrubro) {
            aux = aux + "&rubro=" + idrubro;
        }
        if (fecha_hasta) {
            aux = aux + "&fecha_hasta=" + fecha_hasta;
        }
        $.post(aux, function(data) {
            $("#div_boton").html(data);
        });
    }
</script>
<?php $this->registerJs('$("#fecha_hasta").change(function() {
        $("#loading").show();
        refrescar_grilla();
    });') ?>