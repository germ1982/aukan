<?php

use app\models\Sds_stk_articulo_conversion;
use app\models\Sds_stk_articulo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_articulo_conversion */
/* @var $form yii\widgets\ActiveForm */


/*creamos los botones de exito/error que vamos a llamar desde el controller*/
if(Yii::$app->session->hasFlash('success')) : ?> 
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
        <b><?= Yii::$app->session->getFlash('success') ?></b>
    </div>
<?php endif; 
if(Yii::$app->session->hasFlash('faild')) : ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-times"></i> ¡UPS!</h4>
        <b><?= Yii::$app->session->getFlash('faild') ?></b>
    </div>
	<?php endif;?>


<div class="sds-stk-articulo-conversion-form" >
    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'articulo_base')->widget(Select2::class,[  //Inserccion botones de seleccion
	            	'data' => $filter['articulos'],
	            	'options' => [
	            		'id' => "descripcion",
	            		'placeholder' => 'Seleccionar articulo',
	            		'tabIndex' => '1',
	            	]
	            ]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'articulo_convertido')->widget(Select2::class, [//Inserccion botones de seleccion
                'data' => $filter['articulos'],
                        'options' => [
                            'id' => 'config_',
                            'placeholder' => 'Seleccione...',
                            'tabIndex' => '1'
                        ],

            ])?>
        </div>
    <?php ActiveForm::end(); ?>
</div>