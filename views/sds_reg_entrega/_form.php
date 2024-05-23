<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_stk_articulo;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$idregistro = $_GET['idregistro'];
?>

<div class="sds-reg-entrega-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-12">
                <?php
                    $aux = "Entrega vinculada al registro numero $idregistro";
                    echo Html::label($aux, 'label_registro', ['id' => 'label_registro']);
                    $model->idregistro = $idregistro; 
                    echo $form->field($model, 'idregistro')->hiddenInput()->label('');
                ?>
            </div>

        </div>

        <div class="row">
            <div class="col-md-8">


                <?= $form->field($model, 'idarticulo')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(
                                    Sds_stk_articulo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                    'idarticulo','descripcion'
                                ),
                                'options' => ['placeholder' => 'Seleccionar Articulo ...','onchange'=>'setear_disponible();', 'id'=>'combo_articulo'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],

                            ])->label('Articulo');
                ?>


            </div>
            <div class="col-md-2">
                <?= Html::label('Disponible', 'label_disponible', ['id' => 'label_disponible'])?>  
                <?= Html::textInput('input_disponible',0, ['id'=>'input_disponible','disabled'=>true,'class' => 'form-control input-md','onchange'=>'chequear_cantidad()'])?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'cantidad')->textInput(['id'=>'input_cantidad','onchange'=>'chequear_cantidad()']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="color:#FF0000">
                <?= Html::label('', 'label_estado', ['id' => 'label_estado']);?>
            </div>
        </div>

    
        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>


<script>
    function setear_disponible()
        {
            var aux = $("#combo_articulo").val();
            aux = "index.php?r=sds_stk_articulo/get_stock_disponible&idarticulo=" + aux;
            $.post(aux, function(data) {
                $("#input_disponible").val(data);
                if(data==0)
                    {
                        $('#label_estado').html('El articulo no dispone de stock para hacer entregas');
                        $('#btnGuardar').hide();
                    }
                else
                    {
                        $('#label_estado').html('');
                        $('#btnGuardar').show();
                    }

            }); 
        }
    function chequear_cantidad()
        {
            var disponible =  $("#input_disponible").val();
            var cantidad = $("#input_cantidad").val();
            //alert('disponible: ' + disponible + 'cantidad: ' + cantidad)
            if (cantidad<=disponible)
                {
                    $('#label_estado').html('');
                    $('#btnGuardar').show();
                }
            else
                {
                    $('#label_estado').html('La cantidad a entregar no debe superar el disponible');
                    $('#btnGuardar').hide();
                }

        }
</script>