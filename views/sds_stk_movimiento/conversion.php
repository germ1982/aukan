<?php

use app\models\Mds_seg_usuario;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_movimientoSearch;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$limite_items = 10;

$user = Yii::$app->user->identity;
$idusuario = $user != null ? $user->idusuario : null;
$usuario = Mds_seg_usuario::findOne($idusuario);
$organismo = $usuario->organismo_stock;
$iddeposito = $usuario->iddeposito > 0 ? $usuario->iddeposito : 0;
echo Html::input('hidden', 'input_hidden_iddeposito', $iddeposito, $options = ['id' => 'input_hidden_iddeposito']);

function GetFechaActual()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $mydate = getdate(date("U"));
    $dia = $mydate['mday'];
    if ($dia <= 9) {
        $dia = '0' . $dia;
    }
    $mes = $mydate['mon'];
    if ($mes <= 9) {
        $mes = '0' . $mes;
    }
    $hora = $mydate['hours'];
    if ($hora <= 9) {
        $hora = '0' . $hora;
    }
    $minuto = $mydate['minutes'];
    if ($minuto <= 9) {
        $minuto = '0' . $minuto;
    }
    $Fecha = "$dia/$mes/$mydate[year]";
    //echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
    return $Fecha;
}
//$model->isNewRecord;



if (Yii::$app->session->hasFlash('success')) : ?>
    <div class="alert alert-success">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif;
if (Yii::$app->session->hasFlash('fail')) : ?>
    <div class="alert alert-danger">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-times"></i> ¡Ups!</h4>
        <?= Yii::$app->session->getFlash('fail') ?>
    </div>
<?php endif; ?>



<div class="customer-form">
    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
        'action' => Url::to(['/sds_stk_movimiento/conversion'])
    ]); ?>
    <?= $form->field($model, 'idmovimiento')->hiddenInput(['id' => 'hidden_idmovimiento'])->label(false) ?>
    <div class="row">
        <div class="col-md-6">
            <?php //fijarse de cambier el id del organismo
            /* $organismo = Yii::$app->user->identity->organismo_stock;
            if ($organismo == null) {
                $organismo = 0;
            } */

            $where_deposito = $usuario->iddeposito > 0 ? " and deposito = $usuario->iddeposito" : '';
            /* $consulta_cantidad_tipo1 = "SELECT IFNULL(SUM(Mo.cantidad),0)
                                            FROM sds_stk_movimiento Mo
                                            WHERE Mo.tipo = 1 AND Mo.idarticulo = A.idarticulo";
            $consulta_cantidad_tipo3 = "SELECT IFNULL (SUM(Mo.cantidad),0)
                                            FROM sds_stk_movimiento Mo
                                            WHERE Mo.tipo = 3 AND Mo.idarticulo = A.idarticulo";
            $consulta_disponible = "($consulta_cantidad_tipo1)-($consulta_cantidad_tipo3)";
            $consulta = "SELECT 
                            A.idarticulo AS idarticulo, 
                            CONCAT(A.descripcion,' (en ',C.descripcion,')') AS descripcion,
                            $consulta_disponible AS disponible
                            FROM sds_stk_movimiento M
                            INNER JOIN sds_stk_recepcion_item RI ON RI.idrecepcionitem = M.item_recepcion
                            INNER JOIN sds_stk_recepcion R ON R.idrecepcion = RI.idrecepcion
                            INNER JOIN sds_stk_articulo A ON A.idarticulo = RI.idarticulo
                            INNER JOIN sds_com_configuracion C ON A.unidad_medida = C.idconfiguracion
                            WHERE R.organismo = $organismo AND $consulta_disponible > 0
                            GROUP BY A.idarticulo, A.descripcion"; */
            $consulta_disponible = "(SELECT ifnull(sum(cantidad),0) as disponible
                FROM view_stock_detalle d 
                WHERE d.idarticulo = A.idarticulo $where_deposito
                group by d.idarticulo)";
            $consulta = "SELECT A.idarticulo as idarticulo, 
                                A.descripcion as descripcion,
                                $consulta_disponible as disponible
                            FROM sds_stk_movimiento M
                            INNER JOIN sds_stk_recepcion_item RI on RI.idrecepcionitem = M.item_recepcion
                            INNER JOIN sds_stk_recepcion R on R.idrecepcion = RI.idrecepcion
                            INNER JOIN sds_stk_articulo A on A.idarticulo = RI.idarticulo
                            INNER JOIN sds_com_configuracion C on A.unidad_medida = C.idconfiguracion
                            WHERE R.organismo = $organismo and $consulta_disponible > 0
                            group by A.idarticulo, A.descripcion";
            $articulos = Sds_stk_articulo::findBySql($consulta)->all();

            $consulta = "SELECT 
                            A.idarticulo AS idarticulo, 
                            CONCAT(A.descripcion,' (en ',C.descripcion,')') AS descripcion
                            FROM sds_stk_movimiento M
                            INNER JOIN sds_stk_recepcion_item RI ON RI.idrecepcionitem = M.item_recepcion
                            INNER JOIN sds_stk_recepcion R ON R.idrecepcion = RI.idrecepcion
                            INNER JOIN sds_stk_articulo A ON A.idarticulo = RI.idarticulo
                            INNER JOIN sds_com_configuracion C ON A.unidad_medida = C.idconfiguracion
                            WHERE R.organismo = $organismo
                            GROUP BY A.idarticulo, A.descripcion";

            $articulos_todos = Sds_stk_articulo::findBySql($consulta)->all();
            echo $form->field($model, 'idarticulo')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    $articulos,
                    'idarticulo',
                    'descripcion'
                ),
                'options' => [
                    'id' => 'cmb_articulo',
                    'placeholder' => 'Seleccionar Articulo ...',
                    'disabled' => $model->isNewRecord ? false : true,
                    'onchange' => 'setear_articulos();'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Articulo');
            ?>
        </div>
        <div class="col-md-6" id="div_depositos">
            <?= $form->field($model, 'deposito_egreso')->widget(Select2::class, [
                'data' => "",
                'options' => [
                    'prompt' => '',
                    'placeholder' => 'Seleccionar deposito ...',
                    'id' => 'cmb_deposito_origen',
                    'disabled' => $model->isNewRecord ? false : true
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Deposito de Origen');
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4" id="div_expedientes">
            <?= $form->field($model, 'item_recepcion')->widget(Select2::class, [
                'data' => "",
                'options' => [
                    'prompt' => '',
                    'placeholder' => 'Seleccionar expediente ...',
                    'id' => 'cmb_item_recepcion',
                    'disabled' => $model->isNewRecord ? false : true
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Expediente');
            ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'disponible')->textInput(['id' => 'input_disponible', 'type' => 'number', 'readOnly' => true]);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'cantidad')->textInput(['id' => 'input_cantidad', 'type' => 'number', 'min' => 1, 'readonly' => $model->isNewRecord ? false : true])->label('Cantidad a Convertir'); ?>
        </div>
    </div>

    <div class="row">
        <?php
        $searchModel = new Sds_stk_movimientoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        ?>
    </div>

    <div class="row">
        <div class="col-md-12" style="padding-top:30px;color:red;" id="txt_mensaje"></div>
    </div>
    <div class="panel">
        <div class="panel-heading" style="padding:10px;margin-bottom:5px;background:#efe">
            <div class="row" style="margin-bottom:5px;font-size:15px;">
                <div class="col-md-8 text-center">
                    <b style="padding-right:70px;">Articulo</b>
                </div>
                <div class="col-md-3 text-center">
                    <b>Factor</b>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => $limite_items, // the maximum times, an element can be cloned (default 999)
                'min' => $model->isNewRecord ? 0 : 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $m_articulos[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'idarticulo',
                    'factor'
                ],
            ]); ?>
            <div id="contenedor_items" class="container-items">
                <!-- widgetContainer -->

                <?php foreach ($m_articulos as $i => $m_articulo) : ?>
                    <div class="item panel panel-default">
                        <!-- widgetBody -->

                        <div class="row">
                            <div class="col-md-8 text-center">
                                <?php

                                echo $form->field($m_articulo, "[{$i}]idarticulo")->widget(Select2::class, [
                                    'data' => ArrayHelper::map($articulos_todos, 'idarticulo', 'descripcion'),
                                    'options' => [

                                        'class' => "articulo",
                                        'placeholder' => 'Seleccionar articulo...',
                                        'disabled' => $model->isNewRecord ? false : true
                                    ],
                                    'pluginOptions' => [
                                        'pluginLoading' => false,
                                        'allowClear' => true
                                    ],
                                ])->label(false);

                                ?>
                            </div>
                            <div class="col-md-<?= $model->isNewRecord ? 2 : 4 ?> text-center">
                                <?= $form->field($m_articulo, "[{$i}]factor")->textInput(['type' => 'number', 'min' => '0', 'readonly' => $model->isNewRecord ? false : true])->label(false) ?>
                            </div>
                            <?php if ($model->isNewRecord) : ?>
                                <div class="col-md-2">
                                    <button type="button" class="remove-item btn btn-danger">
                                        <i class="glyphicon glyphicon-minus-sign"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                <?php endforeach; ?>
            </div>
            <?php if ($model->isNewRecord) : ?>
                <button type="button" class="add-item btn btn-success btn-create pull-right">
                    <i class="glyphicon glyphicon-plus-sign"></i> Añadir Articulo
                </button>
            <?php endif; ?>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJsFile('@web/js/stock.js'); ?>
<script>
    $(document).ready(function() {
        /* $('#cmb_articulo').change(function() {
            setear_depositos();
        }); */

        $('#cmb_articulo').change(function() {
            let iddeposito = $("#input_hidden_iddeposito").val();
            if (iddeposito > 0)

            {
                alert();
                setear_deposito_solo(iddeposito);
            } else {
                setear_depositos();
            }
        });

        $('#cmb_deposito_origen').change(function() {
            setear_expedientes();
        });

        $('#cmb_item_recepcion').change(function() {
            setear_disponible();
        });

        if ($('#cmb_deposito_origen').val() != '') {
            setear_depositos();
        }

        if ($('#cmb_item_recepcion').val() != '') {
            setear_expedientes();
        }

        function setear_deposito_solo(iddeposito) {
            aux = "index.php?r=sds_view_stock_detalle/get_deposito_solo&iddeposito=" + iddeposito;
            $.post(aux, function(data) {
                data = data.sort(SortDeposito);
                var option = '';
                data.forEach(function(deposito) {
                    option += '<option value="' + deposito.iddeposito + '">' + deposito.descripcion + '</option>'
                });
                $('#cmb_deposito_origen').html(option).trigger('change');
            });
        }

    });
</script>
<?php
$script = <<<  JS

function setear_articulos()
{
    let limite = $("#contenedor_items").children().length;
    for(i=0;i<limite;i++)
        {
            let aux = "sds_stk_movimiento_articulo-"+i+"-idarticulo";
            cargar_articulos_a_convertir(aux);
            
        }
}

function cargar_articulos_a_convertir(cmb_articulo) {
        console.log(cmb_articulo);
        let ruta = 'index.php?r=sds_stk_movimiento/cmb_articulo&idarticulo=' + $('#cmb_articulo').val();
        console.log(ruta);
        $.post(ruta, function(data) {
            console.log(data);
            let aux_combo = 'select#'+cmb_articulo;
            console.log(aux_combo);
            $(aux_combo).html(data);
        });
    }

$(".dynamicform_wrapper").on("beforeInsert", function(e, item) {

});

$(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    setear_articulos();
});

$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("¿Seguro que desea quitar este articulo?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("afterDelete", function(e) {
    console.log("Deleted item!");
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limit reached");
});
JS;
$this->registerJs($script);
?>