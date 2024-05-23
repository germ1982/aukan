<?php

use app\controllers\SiteController;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_stk_articulo;
use app\models\View_stock_detalle_oc;
use app\models\View_stock_inversion_os;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$url_print_inversiones =  Url::to(['/sds_stk_articulo/imprimir_entregado_pesos']);
//$url_print_inversiones_os =  Url::to(['/mds_acomp_asistencia/detalle_asistencia', 'id' => $model->idasistencia]);

?>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label" for="cmb_anio_inv">Año</label>
                        <select class="form-control" id="cmb_anio_inv" name="cmb_anio_inv" style="padding-left: 2px;padding-right: 2px;" onchange="refrescar_grilla_inv();set_boton_imprimir_entregado_pesos();">
                        </select>
                    </div>
                    <div class="col-md-9">
                        <?= $form->field($model, 'idarticulo')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Sds_stk_articulo::find()->where(['organismo' => $id_organismo])->orderBy(['orden' => SORT_ASC])->all(),
                                'idarticulo',
                                'descripcion'
                            ),
                            'options' => ['placeholder' => 'Seleccionar Artículo...', 'onchange' => 'refrescar_grilla_inv();set_boton_imprimir_entregado_pesos();', 'id' => 'cmb_articulo_inv'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],

                        ])->label('Articulo');
                        ?>
                    </div>
                    <div class="col-md-1" style="font-size: 20px;padding-top: 32px;text-align:left;" id="div_boton_inversiones">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label" for="cmb_anio_inv_os">Año</label>
                        <select class="form-control" id="cmb_anio_inv_os" name="cmb_anio_inv_os" style="padding-left: 2px;padding-right: 2px;" onchange="refrescar_grilla_inv(true);set_boton_imprimir_entregado_pesos_os();">
                        </select>
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($model, 'idarticulo')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Sds_stk_articulo::find()->where(['organismo' => $id_organismo])->orderBy(['orden' => SORT_ASC])->all(),
                                'idarticulo',
                                'descripcion'
                            ),
                            'options' => ['placeholder' => 'Seleccionar Artículo...', 'onchange' => 'refrescar_grilla_inv(true);set_boton_imprimir_entregado_pesos_os();', 'id' => 'cmb_articulo_inv_os'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Articulo');
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?= SiteController::actionGet_input_select2($form, new View_stock_inversion_os(), 'organizacionsocial', 'cmb_organizacion_social_inv', Sds_com_configuracion::find()->where("activo = 1 and idconfiguraciontipo = " . Sds_com_configuracion_tipo::ORGANIZACION_SOCIAL)->all(), 'idconfiguracion', 'descripcion', 'Organizacion Social', 'Organizacion Social ...', null, 'refrescar_grilla_inv(true);set_boton_imprimir_entregado_pesos_os();') ?>
                    </div>
                    <div class="col-md-1" style="font-size: 20px;padding-top: 32px;text-align:left;" id="div_boton_inversiones_os"></div>
                </div>
            </div>



        </div>
        <div class="row" style="padding-top:1%">
            <div class="col-md-6" id="grilla" style="margin-top: 25px;overflow-y: auto;"></div>
            <div class="col-md-6" id="grilla_os" style="margin-top: 25px;overflow-y: auto;"></div>
        </div>
    </div>

</div>
<script>
    function refrescar() {
        setTimeout(function() {
            refrescar_grilla_inv();
            refrescar_grilla_inv(true);
            tiempo = 30000;
        }, tiempo);
    }

    function refrescar_grilla_inv(os = false) {
        let idarticulo = $('#cmb_articulo_inv' + (os ? '_os' : '')).val();
        let anio = $('#cmb_anio_inv' + (os ? '_os' : '')).val();
        let idos = os ? $('#cmb_organizacion_social_inv').val() : 0;
        var aux = "index.php?r=view_stock_inversion/get_grilla_inversiones&idarticulo=" +
            idarticulo + "&anio=" + anio + "&idos=" + idos;
        $.post(aux, function(data) {
            $("#grilla" + (os ? '_os' : '')).html(data);
            const vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0) * 0.66;
            document.getElementById('grilla' + (os ? '_os' : '')).style.height = vh + 'px';
            $("#loading").hide();
        });
    }

    function set_boton_imprimir_entregado_pesos() {
        let idarticulo = $('#cmb_articulo_inv').val();
        let anio = $('#cmb_anio_inv').val();
        let idos = 0;

        let filtro = '';
        filtro = filtro + (idarticulo ? "&idarticulo=" + idarticulo : "");
        filtro = filtro + (anio ? "&anio=" + anio : "");
        filtro = filtro + "&idos=" + idos;

        var aux = "index.php?r=sds_stk_articulo/get_boton_imprimir_entregado_pesos" + filtro;

        console.log(aux);

        $.post(aux, function(data) {
            $("#div_boton_inversiones").html(data);
        });
    }

    function set_boton_imprimir_entregado_pesos_os() {
        let idarticulo = $('#cmb_articulo_inv_os').val();
        let anio = $('#cmb_anio_inv_os').val();
        let idos = $('#cmb_organizacion_social_inv').val();

        let filtro = '';
        filtro = filtro + (idarticulo ? "&idarticulo=" + idarticulo : "");
        filtro = filtro + (anio ? "&anio=" + anio : "");
        filtro = filtro + "&idos=" + idos;

        var aux = "index.php?r=sds_stk_articulo/get_boton_imprimir_entregado_pesos" + filtro;

        //console.log(aux);

        $.post(aux, function(data) {
            $("#div_boton_inversiones_os").html(data);
        });
    }
</script>
<?php $this->registerJs('
    $(document).ready(function(){        
        refrescar_grilla_inv();
        refrescar_grilla_inv(true);
        set_boton_imprimir_entregado_pesos();
        set_boton_imprimir_entregado_pesos_os();        
    });')
?>