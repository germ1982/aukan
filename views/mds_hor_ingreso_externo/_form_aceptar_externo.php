<?php

use app\models\Mds_org_contacto;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */
?> 

<div class="mds-hor-ingreso-externo-aceptar-externo">
    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form
                        ->field($model, 'idcontacto')
                        ->label('Contacto responsable')
                        ->widget(Select2::className(), [
                            'id' => 'cmb_contacto',
                            'data' => ArrayHelper::map(
                                Mds_org_contacto::findBySql(
                                    "select * from mds_org_contacto c 
                                    join sds_com_persona p on p.idpersona=c.idpersona where idcontacto in 
                                    (SELECT idcontacto FROM mds_org_contacto where iddispositivo in 
                                    (Select iddispositivo from mds_org_organismo where idorganismo = 101)) 
                                    order by trim(p.nombre), trim(p.apellido)"
                                    )
                                    ->all(),
                                    'idcontacto',
                                    function ($model) {
                                        return $model->nombre .
                                        ' ' .
                                        $model->apellido;
                                },
                            ),
                            'options' => [
                                'placeholder' => 'Seleccione contacto...',
                                'id' => 'cmb_contacto',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            ]) ?>
               
    <?php ActiveForm::end(); ?>

</div>
