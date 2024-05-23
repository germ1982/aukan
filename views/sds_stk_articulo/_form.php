<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_stk_articulo;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;



$form_principal = 'interv_form';

function botonAltaConfiguracion($model, $tipo, $titulo, $form_principal)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to([
            '//sds_com_configuracion/create_ext',
            'tipo' => $tipo,
        ]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . $tipo,
        'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        //"disabled" => !$model->isNewRecord,
        'onclick' =>
        '
                $("#abm_configuracion").show();
                $("#abm_configuracion_content").load($(this).attr("value"));
                $("#abm_configuracion_title").html("' .
            $titulo .
            '");
                $("#btnGuardar").hide();$("#btnCerrar").hide();
                $("#' .
            $form_principal .
            '").hide();',
    ]);
}
?>

<div class="sds-stk-articulo-form" id="interv_form">
    <?php $form = ActiveForm::begin([
        'action' => [
            'sds_stk_articulo/' .
                ($model->isNewRecord
                    ? 'create' . (isset($botones) ? '_ext' : '')
                    : 'update'),
            'id' => $model->idarticulo,
        ],
        'id' => $model->formName(),
    ]); ?>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'descripcion')->textInput([
                'id' => 'input_descripcion_articulo',
                'maxlength' => true,
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form
                ->field($model, 'abreviatura')
                ->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form
                ->field($model, 'orden')
                ->textInput(['maxlength' => true, 'type' => 'number']) ?>
        </div>
        <div class="col-md-2" >
            <?= $form->field($model, 'ocultar')->checkbox() ?>
            <?= $form->field($model, 'devolucion')->checkbox() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <!-- rubro -->
            <div class="input-group">
                <?= $form
                    ->field($model, 'rubro')
                    ->dropdownList(
                        ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(
                                Sds_com_configuracion_tipo::TIPO_RUBRO,
                                true
                            ),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        [
                            'prompt' => '',
                            'id' =>
                            'config_' .
                                Sds_com_configuracion_tipo::TIPO_RUBRO,
                        ]
                    ) ?>
                <span class="input-group-btn">
                    <?php
                    $tipo = Sds_com_configuracion_tipo::TIPO_RUBRO;
                    $titulo = 'Nuevo Rubro';
                    echo botonAltaConfiguracion(
                        $model,
                        $tipo,
                        $titulo,
                        $form_principal
                    );
                    ?>
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <!-- unidad_medida -->
            <div class="input-group">
                <?= $form
                    ->field($model, 'unidad_medida')
                    ->dropdownList(
                        ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(
                                Sds_com_configuracion_tipo::TIPO_UNIDAD_MEDIDA,
                                true
                            ),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        [
                            'prompt' => '',
                            'id' =>
                            'config_' .
                                Sds_com_configuracion_tipo::TIPO_UNIDAD_MEDIDA,
                        ]
                    ) ?>
                <span class="input-group-btn">
                    <?php
                    $tipo = Sds_com_configuracion_tipo::TIPO_UNIDAD_MEDIDA;
                    $titulo = 'Nueva Unidad de Medida';
                    echo botonAltaConfiguracion(
                        $model,
                        $tipo,
                        $titulo,
                        $form_principal
                    );
                    ?>
                </span>
            </div>
        </div>
        <div class="col-md-2">
            <?php
            if ($model->isNewRecord) {
                $aux = 1;
            } else {
                $aux = $model->activo;
            }
            echo $form
                ->field($model, 'activo')
                ->dropDownList(
                    ['0' => 'no', '1' => 'si'],
                    ['value' => $aux]
                )
                ->label('Activo');
            ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'stock_minimo')->textInput(['type' => 'number','id' => 'input_stock_minimo','maxlength' => true,]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'imagen')->textInput(['id' => 'input_stock_minimo','maxlength' => true,])->label('URL Imagen') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'observaciones')->textArea(['maxlength' => true, 'rows' => 5]) ?>
        </div>
        <div class="col-md-4">
            <?= Html::img($model->imagen,['class' => 'file-preview-image', 'style' => 'height:200px'])?>
        </div>
    </div>

    
    
<!-- --------------------------------------------------------------------------------------------------------------------------- -->
    

    <!-- --------------------------------------------------------------------------------------------------------------------------- -->
    <div class="row">
        <div class="col-md-12" style="padding-top:30px;color:red;" id="txt_mensaje_alta_articulo"></div>
    </div>
    <?php if (isset($botones)) { ?>
        <br>
        <div class="form-group">
            <?= Html::submitButton(
                $model->isNewRecord ? 'Guardar Articulo' : 'update',
                [
                    'class' => $model->isNewRecord
                        ? 'btn btn-success'
                        : 'btn btn-primary',
                    'onclick' => 'validar_datos_item_entrega();',
                ]
            ) ?>
            <?= Html::button('Cerrar Articulo', [
                'class' => 'btn btn-default',
                'onclick' => '$("#abm_articulos").hide();
                    $("#abm_items").show();
                    $("#btnGuardarInterno").show();//Ver que es
                    $("#btnCerrarInterno").show();//Ver que es

                    ',
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<!-- DIV NUEVA CONFIGURACION ##################################################################################################################################################### -->
<div class="row" id="abm_configuracion" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 id="abm_configuracion_title" class="panel-title">
                </h3>
            </header>
            <div class="panel-body" id="abm_configuracion_content">
            </div>
        </section>
    </div>
</div>
<?php
$script = <<<JS

$('form#{$model->formName()}').on('beforeSubmit',function(e){
    
    var \$form = $(this);
    $.post(

        \$form.attr("action"),
        \$form.serialize()

    )
    .done(function(result){    
        if(result > 0){
            $(\$form).trigger("reset");      
            $('#abm_articulos').hide(); 
            $('#abm_items').show(); 
            e.preventDefault();
            $.post("index.php?r=sds_stk_articulo/cmb_articulo", function(data) {
                $("select#cmb_articulo").html(data);
                $("select#cmb_articulo").val(result);
            });            
        }else{
            $("#message").html(result);
        }
    }).fail(function(){
        console.log("server error");
    });
   
    return false;
});
function validar_datos_item_entrega()
{
    var articulo = $("#input_descripcion_articulo").val();
    //alert (articulo);
    unidad_medida = $("#config_86").val();
    $.post("index.php?r=sds_stk_articulo/verificar_existencia&articulo=" + articulo, function(data) 
        {
            //alert(data);
            aux = '';
            if(data>0)
                {
                    aux = 'El articulo ya existe';
                }

            $('#txt_mensaje_alta_articulo').html(aux);
        });

}
/* function validar_datos_item_entrega()
            {
                alert ('entra');
                id_entrega = $("#input_identrega").val();
                id_articulo = $("#cmb_articulo").val();
                id_deposito = $("#cmb_deposito").val();
                id_recepcion_expediente = $("#cmb_expediente").val();
                disponible = $("#input_disponible").val();
                cantidad = $("#input_cantidad").val();

                //alert('id_entrega: ' + id_entrega + '\nid_articulo: ' + id_articulo + '\nid_deposito: ' + id_deposito + '\nid_recepcion_expediente: ' + id_recepcion_expediente + '\ndisponible: ' + disponible + '\ncantidad: ' + cantidad);
                ban=0;
                aux = 'Error:';

                if(!(id_entrega>0))
                    {
                        aux = aux + ' /// Falta el id de entrega';
                        ban=1;
                    }

                if(!(id_articulo>0))
                    {
                        aux = aux + ' /// Falta el articulo';
                        ban=1;
                    }

                if(!(id_deposito>0))
                    {
                        aux = aux + ' /// Falta el deposito';
                        ban=1;
                    }


                if(!(id_recepcion_expediente>0))
                    {
                        aux = aux + ' /// Falta el expediente';
                        ban=1;
                    }

                if(cantidad=='')
                    {
                        aux = aux + ' /// Falta la cantidad';
                        ban=1;
                    }

                if(parseInt(cantidad,10) > parseInt(disponible,10))
                    {
                        aux = aux + ' /// La cantidad no debe superar al disponible';
                        ban=1;
                    }

                if(ban==1)
                    {
                        $('#txt_mensaje').html(aux);
                    }
                else
                    {
                        $.post("index.php?r=sds_stk_entrega_item/validar_item_existente&id_entrega=" + id_entrega + "&id_articulo=" + id_articulo + "&id_recepcion_expediente=" + id_recepcion_expediente, function(data) 
                            {
                                
                                if(data>0)
                                    {
                                        aux = 'El articulo seleccionado ya existe en la entrega numero ' + id_entrega;
                                        $('#txt_mensaje').html(aux);
                                    }
                                else
                                    {
                                            guardar_item_entrega();
                                            limpiar_campos();
                                            actualizar_grilla();
                                    }
                            });

                    }
            } */

JS;

$this->registerJs($script);


?>