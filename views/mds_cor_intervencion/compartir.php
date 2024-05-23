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
        <div class="col-md-6">
            <p><b>Filtrar por Organismo y/o Dispositivo:</b></p>
            <div class="col-md-12">
                <?= $form->field($model, 'idorganismo')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
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
                            $.post("index.php?r=mds_cor_intervencion/cmb_usuarios_organismo&idorganismo=" + $("#cmb_organismo").val(), function(data) {
                                $("select#cmb_usuarios").html(data);
                                $("#cmb_usuarios").val("");
                                $("#cmb_usuarios").trigger("change");
                                $("#mds_cor_intervencion-temp_compartido_1").val(null);
                            });
                        '
                    ]
                ])->label('Seleccione un Organismo');
                ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'iddispositivo')->widget(Select2::classname(), [
                    'data' => [],
                    'options' => [
                        'prompt' => '',
                        'id' => 'cmb_dispositivo',
                        'onchange' => '
                            if($("#cmb_dispositivo").val() == "null") {
                                $.post("index.php?r=mds_org_dispositivo/cmb_dispositivo&idorganismo=" + $("#cmb_organismo").val(), function(data) {
                                    data = "<option value=null>-- Seleccione un Dispositivo --</option>" + data;
                                    $("select#cmb_dispositivo").html(data);
                                });
                                $.post("index.php?r=mds_cor_intervencion/cmb_usuarios_organismo&idorganismo=" + $("#cmb_organismo").val(), function(data) {
                                    $("select#cmb_usuarios").html(data);
                                    $("#cmb_usuarios").val("");
                                    $("#cmb_usuarios").trigger("change");
                                    $("#mds_cor_intervencion-temp_compartido_1").val(null);
                                });
                            } else {
                                $.post("index.php?r=mds_cor_intervencion/cmb_usuarios_dispositivo&iddispositivo=" + $("#cmb_dispositivo").val(), function(data) {
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
            <div class="col-md-12">
                <?= $form->field($model, 'temp_compartido_1')->widget(Select2::classname(), [
                    'data' => [],
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
            <div class="col-md-12">
                <?php
                echo Html::a('<span>Agregar</span>', null, [
                    'name' => 'btn_agregar_1',
                    'id' => 'btn_agregar_1',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-primary',
                    'onclick' => "
                            $.pjax.reload('#' + $.trim(#crud-datatable));
                        "
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <p><b>Filtrar por Nombre:</b></p>
            <div class="col-md-12">
                <?= $form->field($model, 'temp_compartido_2')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Mds_seg_usuario::findBySql("select *
                        from mds_org_contacto c 
                        join mds_seg_usuario u on u.idcontacto=c.idcontacto
                        join sds_com_persona p on p.idpersona=c.idpersona
                        order by p.nombre, p.apellido")->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
                        'idusuario',
                        function ($model) {
                            return $model->nombre . " " . $model->apellido;
                        }
                    ),
                    'options' => ['id' => 'temp_compartido_2', 'placeholder' => '', 'multiple' => true],
                    'size' => Select2::MEDIUM,
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        //'maximumInputLength' => 50,
                        'allowClear' => true
                    ],
                ])->label('Seleccione el/los Usuarios');
                ?>
            </div>
            <div class="col-md-12">
                <?php
                echo Html::a('<span>Agregar</span>', null, [
                    'name' => 'btn_agregar_2',
                    'id' => 'btn_agregar_2',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-primary',
                    'onclick' => "
                            $('#mds_cor_intervencion-compartido_con').val($model->temp_compartido_2);   
                            console.log($('#mds_cor_intervencion-compartido_con'));  
                        "
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <hr>
    <div class="col-md-12">
        <div class="col-md-12">
            <div style="display:<?= ($model->compartido_con)->getTotalCount() == 0 ? "block" : "none" ?>">
                <p>
                    <b>Hasta el momento no se seleccionó ningún usuario para compartir la intervención. Solo es visible para el usuario que la creó.</b>
                </p>
                <hr>
            </div>
            <div id="ajaxCrudDatatable">
                <?= GridView::widget([
                    'id' => 'crud-datatable',
                    'dataProvider' => $model->compartido_con,
                    'pjax' => true,
                    'columns' => [
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'nombre',
                            'value' => function ($data) {
                                return $data['nombre'] . ' ' . $data['apellido'];
                            }
                        ],
                        [
                            'class' => 'kartik\grid\DataColumn',
                            'attribute' => 'editar',
                            'label' => '¿Puede editar?',
                            'value' => function ($data) {
                                return $data['editar'] ? 'Si' : 'No';
                            }
                        ],
                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'dropdown' => false,
                            'template' => '{update} {eliminar}',
                            'vAlign' => 'middle',
                            'urlCreator' => function ($action, $model, $key, $index) {
                                return Url::to([$action, 'id' => $key]);
                            },
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    $url =  Url::to(['/mds_cor_intervencion/update_compartir', 'id' => $model['id']]);
                                    return  Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip']);
                                },
                                'eliminar' => function ($url, $model) {
                                    $url =  Url::to(['/mds_cor_intervencion/delete_compartir', 'id' => $model['id']]);
                                    return  Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                                        'role' => 'modal-remote',
                                        'title' => 'Eliminar',
                                        'data-toggle' => 'tooltip'
                                    ]);
                                },
                            ],
                        ],
                    ],
                    'toolbar' => false,
                    // [
                    //     ['content' => Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar', Url::to(['/mds_cor_intervencion/agregar', 'id' => $model->idintervencion]),
                    //         ['role' => 'modal-remote', 'class' => 'btn btn-default']
                    //     )],
                    // ],
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'panel' => [
                        'type' => 'default',
                        'heading' => '',
                        'after' => '<div class="clearfix"></div>',
                        'footer' => false,
                        'heading' => false
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>