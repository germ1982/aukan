<?php

use app\controllers\SiteController;
use app\models\Mds_org_contacto;
use app\models\Sds_stk_articulo;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

//$url_print_inversiones =  Url::to(['/sds_stk_articulo/imprimir_entregado_pesos']);
//$url_print_inversiones_os =  Url::to(['/mds_acomp_asistencia/detalle_asistencia', 'id' => $model->idasistencia]);

?>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_hora', 'desde_resp', 'Fecha Desde', true, 'refrescar_grilla_resp();') ?>
                    </div>
                    <div class="col-md-2">
                        <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_hora', 'hasta_resp', 'Fecha Hasta', true, 'refrescar_grilla_resp();') ?>
                    </div>
                    <div class="col-md-4">

                        <?= $form->field($model, 'idarticulo')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Sds_stk_articulo::find()->where(['organismo' => $id_organismo])->orderBy(['orden' => SORT_ASC])->all(),
                                'idarticulo',
                                'descripcion'
                            ),
                            'options' => ['placeholder' => 'Seleccionar Artículo...', 'onchange' => 'refrescar_grilla_resp();set_boton_imprimir_responsables(); ', 'id' => 'cmb_articulo_resp'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple' => true
                            ],

                        ])->label('Articulo');
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
                            'data' => ArrayHelper::map(
                                Mds_org_contacto::findBySql(
                                    "select * from mds_org_contacto c 
                                    join sds_com_persona p on p.idpersona=c.idpersona 
                                    where idcontacto in
                                    (select idcontacto from view_stock_detalle_ent_resp where organismo=$id_organismo)
                                    order by apellido,nombre"
                                )->all(),
                                'idcontacto',
                                function ($model) {
                                    return $model->apellido . ", " . $model->nombre;
                                }
                            ),
                            'options' => [
                                'id' => 'cmb_contacto_resp',
                                'placeholder' => 'Seleccione Responsable...',
                                'onchange' => 'refrescar_grilla_resp();set_boton_imprimir_responsables();'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ])->label("Responsable"); ?>
                    </div>
                    <div class="col-md-1" style="font-size: 20px;padding-top: 32px;text-align:left;" id="div_boton_responsables"></div>
                    <!-- <div class="col-md-1" style="font-size: 20px;padding-top: 25px;" id="div_boton_responsables">
                    </div> -->
                </div>
            </div>
        </div>
        <div class="row" style="padding-top:1%">
            <div class="col-md-12" id="grilla_resp" style="margin-top: 25px;overflow-y: auto;"></div>
        </div>
    </div>
</div>
<script>
    function refrescar() {
        setTimeout(function() {
            refrescar_grilla_resp();
            tiempo = 30000;
        }, tiempo);
    }

    function refrescar_grilla_resp() {
        let desde = $('#desde_resp').val();
        let hasta = $('#hasta_resp').val();
        let idarticulo = $('#cmb_articulo_resp').val();
        let idcontacto = $('#cmb_contacto_resp').val();
        var aux = "index.php?r=sds_stk_entrega/get_grilla_responsables&desde=" + desde + "&hasta=" + hasta +
            "&idarticulo=" + idarticulo + "&idcontacto=" + idcontacto;
        $.post(aux, function(data) {
            $("#grilla_resp").html(data);
            const vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0) * 0.66;
            document.getElementById('grilla_resp').style.height = vh + 'px';
            $("#loading").hide();
        });
    }



    function set_boton_imprimir_responsables() {
    var url = "index.php?r=sds_stk_articulo/get_boton_imprimir_responsables";
    
    var datos = {
        desde: $('#desde_resp').val(),
        hasta: $('#hasta_resp').val(),
        articulos: $('#cmb_articulo_resp').val().join(),
        idcontacto: $('#cmb_contacto_resp').val(),
    };
    
    $.post(url, datos, function(data) {
        $("#div_boton_responsables").html(data);
    });
}

</script>
<?php $this->registerJs('
    $(document).ready(function(){     
  
        refrescar_grilla_resp();
        
        $("#desde_resp").change(function() {refrescar_grilla_resp();set_boton_imprimir_responsables();});
        $("#hasta_resp").change(function() { refrescar_grilla_resp();set_boton_imprimir_responsables();});

        set_boton_imprimir_responsables();
        
    });')

?>