<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Carga de francos por periodo';
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    .select2-search{
        z-index:0;
    }
    .content-body{
        padding-top: 13px;
    }
    .jconfirm-buttons{
        float: none !important;
    }
    .jconfirm-box{
        width: 120%;
    }

</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li><a href="index.html"><i class="fa fa-home"></i></a></li>
            <li><a href="index.php?r=mds_hor_franco">Francos</a></li>
            <li><span><u><?= $this->title ?></span></u></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div id="confirm" style="display: none; width:450px;"></div>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?php
                if(!empty($save['not_fail'])):?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
                        Se cargaron de manera correcta los francos de:
                        <ul>
                            <?php foreach($save['not_fail'] as $id_contacto):
                                $contacto=Mds_org_contacto::findBySql(
                                    "SELECT CONCAT(p.apellido, ', ',p.nombre) nombre FROM mds_org_contacto c
                                    JOIN sds_com_persona p ON p.idpersona=c.idpersona WHERE c.idcontacto=".$id_contacto)->one();
                                ?>
                                <li><?= $contacto->nombre?></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                <?php
                endif;
                if(!empty($save['with_fail'])):?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fas fa-times"></i> ¡Ups!</h4>
                        Falló la carga de los francos de:
                        <ol>
                            <?php 
                            foreach($save['with_fail'] as $id_contacto):
                                $contacto=Mds_org_contacto::findBySql(
                                    "SELECT CONCAT(p.apellido, ', ',p.nombre) nombre FROM mds_org_contacto c
                                    JOIN sds_com_persona p ON p.idpersona=c.idpersona WHERE c.idcontacto=".$id_contacto)->one();
                                ?>
                                <li><?= $contacto->nombre?></li>
                            <?php
                            endforeach;?>
                        </ol>
                    </div>
                <?php
                endif;
                ?>
                <div class="mds-hor-franco-form" >
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-7 col-md-offset-3">
                        <?= $form->field($model, 'contactos')->widget(Select2::class, [
                                'data' => ArrayHelper::map(
                                    Mds_org_contacto::findBySql('SELECT c.*, CONCAT(c.legajo, " - ",trim(p.apellido), ", ",trim(p.nombre)) nombre FROM mds_org_contacto c
                                    JOIN sds_com_persona p ON p.idpersona=c.idpersona
                                    WHERE c.legajo IS NOT NULL AND c.activo AND c.rotativo ORDER BY p.apellido,p.nombre')->all(),
                                    'idcontacto',
                                    'nombre'
                                ),
                                'options' => [
                                    'placeholder' => '- Seleccionar Contactos -'
                                ],
                                'pluginOptions' => [
                                    'multiple' => true,
                                    'allowClear' => false
                                ]
                            ]);
                        ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-md-offset-3">
                            <?= $form->field($model, 'desde')->widget(DatePicker::class, [
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'desde',
                                    'class' => 'form-control input-md',
                                    'disabled' => false
                                ],
                                'pluginOptions' => [
                                    'value' => null,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ]
                            ]);?>
                        </div>
                        <div class="col-md-3 col-md-offset-1">
                            <?= $form->field($model, 'hasta')->widget(DatePicker::class, [
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'hasta',
                                    'class' => 'form-control input-md',
                                    'disabled' => false
                                ],
                                'pluginOptions' => [
                                    'value' => null,
                                    'format' => 'dd-mm-yyyy',
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ]
                            ]);?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="row" style="font-size:15px;">
                            <span class="col-md-2" style="width:9.4%; margin-top:27px; padding:0; margin-left:330px;">Días laborales:</span>
                            <div class="col-md-1" style="width:13.7%; padding-left: 0;">
                                <?= $form->field($model, 'dias_laborales')->textInput(['maxlength' => true, 'type'=>'number', 'min'=>1])->label('') ?>
                            </div>
                            <span class="col-md-2" style="width:10%; margin-top:27px; padding:0; margin-left:114px;">x Días de Franco:</span>
                            <div class="col-md-1" style="width:13.3%; padding-left: 0;">
                                <?= $form->field($model, 'dias_franco')->textInput(['maxlength' => true, 'type'=>'number', 'min'=>1])->label('') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 col-md-offset-3">
                            <?= $form->field($model, 'descripcion')->textInput()->label('') ?>
                        </div>
                    </div>
                                
                    <div class="form-group">
                        <div class="row" style="padding-top: 2%">
                            <div class="col-md-6 col-md-offset-3">
                                <?= Html::button('Generar Francos', [
                                    'class' => 'btn btn-success col-md-10 col-md-offset-2',
                                    'id'=>'send_form'
                                ]);?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php

//$ajax = Yii::$app->request->isAjax;
$script = <<<  JS
    /*
    $('form#{$model->formName()}').on('beforeSubmit',function(e){        
        var \$form = $(this);
        $.post(
            \$form.attr("action"),
            \$form.serialize()
        )
        .done(function(result){            
            if(result >= 1){
                $(\$form).trigger("reset");    
                $('#modal_abm').modal('hide'); 
                e.preventDefault();                
            }else{
                $("#message").html(result);
            }
        }).fail(function(){
            console.log("server error");
        });
       
        return false;
    });
    */

    $('#send_form').click(function(){
        $.confirm({
            title:'',
            content: '<div style="text-align:center;">'+
                '<h3><span class="text-warning">La operacion que está por realizar:</h3>'+
                '<h4 class="text-info">Actualizará los francos existente de los contactos seleccionados.</h4>'+
                '<h4 class="text-danger">Esta operación es irreversible.</h4>'+
                '</div><div style="text-align:center; margin-top: 20px;"><h4><span class="text-warning">¿Está seguro de continuar?</span></h4></div>',
            buttons: {
                cancel: {
                    text: 'Cancelar',
                    btnClass: 'btn-danger ',
                    action: function(){}
                },
                confirm: {
                    text: 'Continuar',
                    btnClass: 'btn-success pull-right',
                    action: function(){
                        console.log($('#w0').attr('action'));
                        $('#w0').submit();
                        console.log($('#w0').attr('action'));
                    }
                }
            }
        });
    });
     
JS;
$this->registerJs($script);
?>