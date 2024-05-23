<?php

use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use kartik\editable\Editable;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
        <p><b>Filtrar por Organismo y/o Dispositivo:</b></p>
        <div class="col-md-6">
            <?= $form->field($model, 'idorganismo')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(
                    $model->organismos,
                    'idorganismo',
                    'descripcion'
                ),
                'options' => [
                    'placeholder' => '',
                    'id' => 'cmb_organismo',
                    'onchange' => '
                            $.post("index.php?r=mds_org_dispositivo/cmb_dispositivo&idorganismo=" + $("#cmb_organismo").val(), function(data) {
                                data = "<option value=null>-- Seleccione un Dispositivo --</option>" + data;
                                $("select#cmb_dispositivo").html(data);
                            });
                            $.post("index.php?r=mds_cor_intervencion_usuario/cmb_usuarios_organismo&idorganismo=" + $("#cmb_organismo").val(), function(data) {
                                $("select#cmb_usuarios").html(data);
                                $("#cmb_usuarios").val("");
                                $("#cmb_usuarios").trigger("change");
                                $("#mds_cor_intervencion_usuario-agregar").val(null);
                            });
                        '
                ]
            ])->label('Seleccione un Organismo');
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'iddispositivo')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(
                    $model->dispositivos,
                    'iddispositivo',
                    function ($value) {
                        $org = Mds_org_organismo::findOne(['idorganismo' => $value->idorganismo]);
                        return "$value->descripcion - $org->descripcion";
                    }
                ),
                'options' => [
                    'prompt' => '',
                    'id' => 'cmb_dispositivo',
                    'onchange' => '
                            if($("#cmb_dispositivo").val() == "null") {
                                $.post("index.php?r=mds_org_dispositivo/cmb_dispositivo&idorganismo=" + $("#cmb_organismo").val(), function(data) {
                                    data = "<option value=null>-- Seleccione un Dispositivo --</option>" + data;
                                    $("select#cmb_dispositivo").html(data);
                                });
                                $.post("index.php?r=mds_cor_intervencion_usuario/cmb_usuarios_organismo&idorganismo=" + $("#cmb_organismo").val(), function(data) {
                                    $("select#cmb_usuarios").html(data);
                                    $("#cmb_usuarios").val("");
                                    $("#cmb_usuarios").trigger("change");
                                    $("#mds_cor_intervencion_usuario-agregar").val(null);
                                });
                            } else {
                                $.post("index.php?r=mds_cor_intervencion_usuario/cmb_usuarios_dispositivo&iddispositivo=" + $("#cmb_dispositivo").val(), function(data) {
                                    $("select#cmb_usuarios").html(data);
                                    $("#cmb_usuarios").val("");
                                    $("#cmb_usuarios").trigger("change");
                                    $("select#cmb_usuarios").html(data);
                                });
                            }
                        '
                ]
            ])->label('Seleccione un Dispositivo');
            ?>
        </div>
        <p><b>Filtrar por Nombre:</b></p>
        <div class="col-md-12">
            <?= $form->field($model, 'agregar')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($model->usuarios, 'idusuario', function($model) {
                    return $model->nombre . ' ' . $model->apellido;
                }),
                'options' => [
                    'prompt' => '',
                    'id' => 'cmb_usuarios',
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    //'maximumInputLength' => 50,
                    'allowClear' => true
                ],
            ])->label('Seleccione el/los Usuarios');
            ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>