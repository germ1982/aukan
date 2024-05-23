<?php

use app\controllers\SiteController;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\View_stock_detalle_oc;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-md-8">
    </div>
    <div class="col-md-2" style="text-align:right" div fxLayout="row" fxLayoutAlign="end end">
        <!-- stock minimo -->
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
    <div class="col-md-7">
        <div class="row">
            <div class="col-md-8">
                <!-- filtro rubro -->
                <?= $form->field($model, 'idconfiguracion')->dropdownList(
                    ArrayHelper::map(Sds_com_configuracion::findBySql("SELECT idconfiguracion, descripcion from sds_com_configuracion 
                                                                        where idconfiguracion = 1 or idconfiguraciontipo = " . Sds_com_configuracion_tipo::TIPO_RUBRO . " 
                                                                        and activo = 1 
                                                                        and idconfiguracion in (select a.rubro from sds_stk_articulo a where a.organismo = $id_organismo)
                                                                        order by descripcion")->all(), 'idconfiguracion', 'descripcion'),
                    ['prompt' => 'Todos', 'id' => 'cmb_rubro', 'onchange' => 'refrescar_grilla();',]
                )->label('Filtrar por Rubro');
                ?>
            </div>
            <div class="col-md-4">
                <!-- FECHA -->
                <?= SiteController::actionGet_input_fecha($form, $model_entrega, 'fecha_hora', 'fecha_hasta', 'Fecha Hasta', true) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="div_grilla" style="overflow-x: auto;overflow-y: auto;"></div>
        </div>
    </div>
    <div class="col-md-5" id="div_grilla_entregado" style="margin-top: 25px;overflow-y: auto;">

    </div>
</div>

<script>
    function refrescar() {
        setTimeout(function() {
            refrescar_grilla();
            refrescar();
            tiempo = 30000;
        }, tiempo);
    }

    function refrescar_grilla(desde_boton = 0) {
        var idrubro = $('#cmb_rubro').val();
        var ver_minimo = $('#btn_consultar').val() ? $('#btn_consultar').val() : 0;
        var fecha_hasta = $('#fecha_hasta').val();
        var aux = "index.php?r=sds_stk_articulo/get_grilla_stock_general&ver_minimo=" + ver_minimo;
        if (idrubro) {
            aux = aux + "&rubro=" + idrubro;
        }
        if (fecha_hasta) {
            aux = aux + "&fecha_hasta=" + fecha_hasta;
        }
        $.post(aux, function(data) {
            $(".field-fecha_hasta").removeClass("has-success");
            $("#div_grilla").html(data);
            const vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0) * 0.60;
            document.getElementById('div_grilla').style.height = vh + 'px';
            $("#loading").hide();
            refrescar_grilla_ultimas_entregas($('#beneficiario').val());
        });
        set_boton_imprimir();
        if (desde_boton == 1) {
            $('#btn_consultar').val(ver_minimo == 1 ? 0 : 1);
        }
    }

    function refrescar_grilla_ultimas_entregas(beneficiario=null) {
        var aux = "index.php?r=sds_stk_articulo/get_grilla_ultimas_entregas"+(beneficiario!=null?'&beneficiario='+beneficiario:'');
        $.post(aux, function(data) {
            $("#div_grilla_entregado").html(data);
            const vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0) * 0.66;
            document.getElementById('div_grilla_entregado').style.height = vh + 'px';
            $("#loading").hide();
        });
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
    
    function buscar_entregas(){
        refrescar_grilla_ultimas_entregas($('#beneficiario').val());
    }

    function cargarComboAnio(primerAnio) {
        var anioActual = (new Date).getFullYear();
        var html = '';
        for (var i = anioActual; i >= primerAnio; i--) {
            html = html + '<option value="' + i + '">' + i + '</option>';
        }
        $("#cmb_anio_inv").html(html);
        $("#cmb_anio_inv").val(anioActual);
        $("#cmb_anio_inv_os").html(html);
        $("#cmb_anio_inv_os").val(anioActual);
        $("#cmb_anio").html(html);
        $("#cmb_anio").val(anioActual);
    }
</script>
<?php $this->registerJs('
    $(document).keyup(function(event) {
        if (event.which === 13) {
            $("#w0").submit(function(evt){
                evt.preventDefault();
                buscar_entregas();
                $(".field-fecha_hasta").removeClass("has-success");
            });
        }
    });

    $(document).ready(function(){
        cargarComboAnio(" ' . View_stock_detalle_oc::getPrimerAnio() . ' ");
        $("#fecha_hasta").change(function() {
            $("#loading").show();
            refrescar_grilla();
        });
        refrescar_grilla();
        refrescar();
    });') ?>