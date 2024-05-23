<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Sds_stk_deposito;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;

//Si existe fecha_hora, seteo los atributos fecha y hora para usar con los widgets
if ($model->fecha_hora != null) {
    $fecha = $model->fecha_hora;
    $model->fecha_hora = date('d/m/Y', strtotime(str_replace('/', '-', $fecha)));
    $model->hora = date('H:i', strtotime($fecha));
} else {
    $model->hora = date('H:i');
    $model->fecha_hora = date('d/m/Y');
}
$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;

$model->idusuario = $model->idusuario ? $model->idusuario : $idusuario;
$model->usuario_descripcion = Mds_org_contacto::getAyN($usuario->idcontacto);

if ($model->iddeposito) {
    $model->deposito_descripcion = Sds_stk_deposito::findOne($model->iddeposito)->descripcion;
} else {
    if ($usuario->iddeposito) {
        $model->iddeposito = $usuario->iddeposito;
        $model->deposito_descripcion = Sds_stk_deposito::findOne($model->iddeposito)->descripcion;
    } else {
        if ($usuario->organismo_stock) {
            $depositos = ArrayHelper::map(Sds_stk_deposito::find()->where(['activo' => 1, 'idorganismo' => $usuario->organismo_stock])->orderBy(['descripcion' => SORT_ASC])->all(), 'iddeposito', 'descripcion');
        } else {
            $depositos = ArrayHelper::map(Sds_stk_deposito::find()->all(), 'iddeposito', 'descripcion');
        }
    }
}

if ($usuario->organismo_stock) {
    $organismo = Mds_org_organismo::findOne($usuario->organismo_stock);
    if ($organismo->idrubro != null) {
        $model->idrubro = $organismo->idrubro;
        $model->rubro_descripcion = Sds_com_configuracion::findOne($organismo->idrubro)->descripcion;
    } else {
        $rubros = ArrayHelper::map(Sds_com_configuracion::find()
            ->where("idconfiguraciontipo = " . Sds_com_configuracion_tipo::TIPO_RUBRO . " and activo = 1 
                and idconfiguracion in (select a.rubro from sds_stk_articulo a where a.organismo = $organismo->idorganismo)")
            ->orderBy(['idconfiguracion' => SORT_ASC])
            ->all(), 'idconfiguracion', 'descripcion');
    }
}

?>

<div class="sds-stk-inventario-form" id="div_formulario_inventario">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'idinventario')->hiddenInput(["id" => "hidden_input_idinventario"])->label(false) ?>
    <?= $form->field($model, 'idorganismo')->hiddenInput(["id" => "hidden_input_idorganismo"])->label(false) ?>

    <?= $form->field($model, 'idusuario')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'iddeposito')->hiddenInput()->label(false); ?>

    <div class="row">
        <!-- LINEA 1 -->
        <div class="col-md-3 col-sm-6 col-xs-9">
            <!-- FECHA -->
            <?= //SiteController::actionGet_input_fecha($form, $model, 'fecha_hora', 'input_fecha_hora', 'Fecha',true) 
            $form->field($model, 'fecha_hora')->textInput(['readonly' => true])->label('Fecha'); ?>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-3">
            <!-- HORA -->
            <?php $model->fecha_hora = date('d/m/Y H:i'); ?>
            <?= //SiteController::actionGet_input_hora($form, $model, 'hora', 'horax', 'Hora') 
            $form->field($model, 'hora')->textInput(['readonly' => true])->label('Hora'); ?>
        </div>
        <div class="col-md-7 col-sm-7 col-xs-7">
            <!-- USUARIO -->
            <?= $form->field($model, 'usuario_descripcion')->textInput(['readonly' => true])->label('Usuario'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-5 col-xs-5">
            <?php

            if ($usuario->organismo_stock) {
                $depositos = ArrayHelper::map(Sds_stk_deposito::find()->where(['activo' => 1, 'idorganismo' => $usuario->organismo_stock])->orderBy(['descripcion' => SORT_ASC])->all(), 'iddeposito', 'descripcion');
            } else {
                $depositos = ArrayHelper::map(Sds_stk_deposito::find()->all(), 'iddeposito', 'descripcion');
            }

            if ($model->iddeposito) {
                if ($usuario->iddeposito == $model->iddeposito) {
                    echo $form->field($model, 'deposito_descripcion')->textInput(['readonly' => true])->label('Deposito');
                } else {
                    echo $form->field($model, 'iddeposito')->dropdownList($depositos, ['id' => 'combo_depositos'])->label('Deposito');
                }
            } else {
                echo $form->field($model, 'iddeposito')->dropdownList($depositos, ['id' => 'combo_depositos'])->label('Deposito');
            }
            ?>
        </div>
        <div class="col-md-6 col-sm-5 col-xs-5">
            <?php
            if ($model->idrubro != null) {
                $rubro_input = $form->field($model, 'rubro_descripcion')->textInput(['readonly' => true])->label('Rubro');
                echo $rubro_input;
            } else {
                $rubro_combo = $form->field($model, 'idrubro')->dropdownList($rubros, ['id' => 'combo_rubros', 'onchange' => 'actualizar_grilla();'])->label('Rubros');
                $model->idrubro = $model->idrubro ? $model->idrubro : Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_RUBRO])->orderBy(['idconfiguracion' => SORT_ASC])->limit(1)->one()->idconfiguracion;
                echo $rubro_combo;
            }
            ?>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading" style="padding:10px;margin-bottom:5px;background:#efe">
            <div class="row" style="margin-bottom:5px;font-size:15px;">
                <div class="col-md-9 col-sm-9 col-xs-9 text-center">
                    <b>Articulo</b>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3 text-center">
                    <b>Cantidad</b>
                </div>
            </div>
        </div>
        <div class="panel-body" id="div_grilla">
            <?= Yii::$app->controller->renderPartial('_form_grilla', ['model_inventario_items' => $model_inventario_items, 'form' => $form]) ?>
        </div>

        <?php if (Yii::$app->session->hasFlash('success')) : ?>
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


        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>

    </div>


    <script>
        function actualizar_grilla() {
            let idrubro = $("#combo_rubros").val();
            $.post("index.php?r=sds_stk_inventario/get_grilla&idrubro=" + idrubro, function(data) {
                $("#div_grilla").html(data);
            });

        }
    </script>