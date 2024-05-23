<?php

use yii\widgets\DetailView;
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;
use app\models\Sds_com_configuracion;
use yii\helpers\Html;

if (!Yii::$app->request->isAjax) {echo ("ENTREGA NUMERO $model->identrega");}
?>


<input type="hidden" id="hidden_input_id_entrega" name="hidden_input_id_entrega" value="<?=$model->identrega?>">

<script>
    refrescar_grilla();
    function refrescar_grilla()
        {
            identrega = $('#hidden_input_id_entrega').val();
            if(identrega)
                {
                    aux = "index.php?r=sds_stk_entrega_item/grilla_items_view&identrega=" + identrega;
                    $.post(aux, function(data) {$("#div_grilla").html(data);});
                }

        }
</script>
<div class="sds-stk-entrega-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'identrega',
                'label' => 'ID Entrega',
            ],
            [
                'attribute' => 'fecha_hora',
                'label' => 'Fecha',
                'value' => function ($model) {
                    if ($model->fecha_hora != null) {
                        $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora)));
                        return "$fecha";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'hora',
                'label' => 'Hora',
                'value' => function ($model) {
                    if ($model->fecha_hora != null) {
                        $hora = date('H:i', strtotime(str_replace('/', '-', $model->fecha_hora)));
                        return "$hora";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'idcontacto',
                'label' => 'Responsable',
                'value' => function ($model) {
                    if ($model->idcontacto != null) {
                        $contacto = Mds_org_contacto::findOne($model->idcontacto);
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        return "$persona->apellido, $persona->nombre";
                    }
                    return "";
                },
            ],

            [
                'attribute' => 'idpersona',
                'label' => 'Destinatario',
                'value' => function ($model) {
                    if ($model->idpersona != null) {
                        $persona = Sds_com_persona::findOne($model->idpersona);
                        return "$persona->apellido, $persona->nombre";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'organizacion_social',
                'value' => function ($model) {return $model->organizacion_social ? Sds_com_configuracion::findOne($model->organizacion_social)->descripcion:'';},
            ],
            [
                'attribute' => 'acta_original',
                'label' => 'Acta Completa',
                'value' => function ($model) {
                    if ($model->acta_original) {
                        
                        return "Si";
                    }
                    return "No";
                },
            ],
            [
                'attribute' => 'observaciones',
                'label' => 'Observaciones',
                'value' => function ($model) {
                    if ($model->observaciones != null) {
                        return $model->observaciones;
                    }
                    else

                    return "";
                },
            ],

        ],
    ]) ?>
    <div class="row" style="border-radius: 5px; padding: 15px;">
        Items:
        <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div> 
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="row">
            <?php 
                echo Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary']);
                echo Html::a('Edit',['update','id'=>$model->identrega],['class'=>'btn btn-primary','role'=>'post'])
                
            //Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
</div>
