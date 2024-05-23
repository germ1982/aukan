<?php

use app\controllers\Telefonia_vista_integradoraController;
use app\models\Sds_cel_factura_item;
use app\models\Telefonia_vista_integradora;
use Codeception\Event\PrintResultEvent;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;


?>

<div class="sds-cel-factura-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6"> <!-- cuenta -->
            <?php 
                $mysql = 'SELECT * from vista_integradora where cuenta >= 1 group by cuenta';
                echo $form->field($model, 'cuenta')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Telefonia_vista_integradora::findBySql($mysql)->all(), 'lineanro', 'cuenta'),
                    'options' => ['placeholder' => 'Seleccionar cuenta ...',
                                    //'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_ORGANISMO_LINEA,
                                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Cuenta');
            ?>

        </div>
        <div class="col-md-3"><!-- fecha carga -->
            <?php 
                if($model->fecha_carga==null)
                    {$model->fecha_carga = date('d/m/Y');}
                else
                    {$model->fecha_carga = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_carga)));}
                
                echo $form->field($model, 'fecha_carga')->widget(DatePicker::ClassName(), [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'id' => 'carga',
                        'class' => 'form-control input-md',
                        'disabled' => false

                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        //'startDate' => $fecha_desde,
                        'endDate' => date('d-m-Y'),
                        'todayHighlight' => true,
                        'autoclose' => true,
                        ]
                    ]); 

            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"><!-- periodo mes -->
            <?php
                $meses = array('',1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio', 8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
                echo $form->field($model, 'periodo_mes')->dropDownList($meses);
            ?>
        </div>
        <div class="col-md-2"> <!-- periodo año -->
            <?php
                $anios = array();
                $anios[]='';
                $anio_actual = date('Y');


                for ($i = ($anio_actual-1); $i <= ($anio_actual+1); $i++) {
                    $anios[$i]=$i;
                }
                echo $form->field($model, 'periodo_anio')->dropDownList($anios);
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
        </div>
    </div>
        <!-- LINEA GRILLA DE ITEMS ##################################################################################################################################################### -->
        <div class="row" style="border-radius: 5px; padding: 15px;">
            Items:
            <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;">
                <?php

                    if ($model->idfactura)
                        {
                            $idfactura = $model->idfactura;
                            $dataProvider = new ActiveDataProvider([
                                'query' => Sds_cel_factura_item::findBySql('Select * from sds_cel_factura_item where idfactura = '.$model->idfactura),
            
                            ]);
                                    echo GridView::widget([
                                        'id' => 'grilla_items',
                                        'dataProvider' => $dataProvider,
                                        'summary' => '',
                                        'columns' => [

                                            'linea',
                                            'idconcepto',
                                            'concepto',
                                            'cantidad',
                                            'neto',
                                            'impuestos',
                                            'total',
                                        ],
                                    ]);
                        }
                    else
                        {
                            echo "Sin Items...";
                        }
                    
                ?>
            </div>
        </div>

    


    

    

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
