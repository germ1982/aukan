<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
//use app\models\Mds_rum_categoria;
//use app\models\Mds_rum_cualificacion;
use kartik\widgets\FileInput;
use yii\helpers\Url;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Mds_rum_postulacion;
use app\models\Mds_rum_persona;
use app\models\Sds_com_provincia;
use app\models\Sds_com_localidad;
use app\models\Mds_rum_empleador;
use kartik\date\DatePicker;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario_rol;
use kartik\time\TimePicker;
use dosamigos\ckeditor\CKEditor;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_oferta_laboral */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-oferta-laboral-form"> 
    <?php $form = ActiveForm::begin(); ?> 
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">    
                    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true])->label("Titulo*") ?>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12">    
                <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12">    
            <?= $form->field($model, 'competencia')->textarea(['rows' => 6]) ->label("Competencia*")?>
            </div>
        </div>        
        <!--<div class="row">
            <div class="col-md-12">    -->
            <?php //echo $form->field($model, 'imagen')->textInput(['maxlength' => true]); ?>
            <!--</div>
        </div>-->
    </div> 

    <br>

        <div class="row">
            <div class="col-md-6" >
                CONTACTO 
                <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">                                                                                                                                                                                                                                  
                        <div class="row">
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'email1')->textInput(['maxlength' => true])->label("Email 1*") ?>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'email2')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'telefono1')->textInput(['maxlength' => true])->label("Telefono 1*") ?>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'telefono2')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                </div>        
            </div>         
            
            <div class='col-md-6' align="center";>
                        <?php
                        if ($model->imagen == null) {
                            echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    //'name' => 'i1',
                                    'options' => ['accept' => 'image/*'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        //'showPreview' => false,
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],                                        
                                        'showCaption' => false,
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'showCancel'=> false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_rum_oferta_laboral/update']),
                                        'maxFileSize' => 1000,
                                        /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                                        'previewFileType' => 'image',
                                        'initialCaption' => $model->imagen,
                                        'fileActionSettings' => [
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showZoom' => false,
                                        'showCaption' => false,
                                        'showCancel'=> false
                                        ]
                                        //'minFileCount' => 1,
                                        // 'validateInitialCount' => true,
                                    ],
                                ])->label('IMAGEN PRINCIPAL');
                        } else {
                            echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                        'showCaption' => false,
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'showCancel'=> false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_rum_oferta_laboral']),
                                        'maxFileSize' => 1000,
                                        'previewFileType' => 'image',
                                        'initialPreview' => [
                                            //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                            Html::img(Url::base()."/uploads/ofertas/".$model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                           //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->imagen);
                                        ],
                                        'overwriteInitial' => true,
                                        'autoReplace' => true,
                                        'initialCaption' => $model->imagen,
                                        'fileActionSettings' => [
                                            'showRemove' => false,
                                        'showUpload' => false,
                                        'showZoom' => false,
                                        'showCaption' => false,
                                        'showCancel'=> false
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                        "filereset" => "function() {  }",
                                    ]
                                ])->label('IMAGEN PRINCIPAL');
                        }
                        ?>
            </div>
           
        </div><br>
 
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
                <div class="col-md-3">
                    <?php
                           $una_conf=Sds_com_configuracion_tipo::find() 
                           ->select(['idconfiguraciontipo'])                                   
                           ->where(['descripcion' => 'Rum_Nivel_Ocupacion'])
                           ->one();                           
                        
                    ?>
                
                    <?= $form->field($model, 'id_nivel_ocupacion')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => $una_conf->idconfiguraciontipo, 'activo'=>'1'])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_nivel_ocup',
                         ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Nivel Ocupación*");
                    ?>
                </div> 
                <div class="col-md-3">

                    <?php
                           $una_conf=Sds_com_configuracion_tipo::find() 
                           ->select(['idconfiguraciontipo'])                                   
                           ->where(['descripcion' => 'Rum_Nivel_Experiencia'])
                           ->one();                           
                        
                    ?>

                    <?= $form->field($model, 'id_experiencia')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => $una_conf->idconfiguraciontipo, 'activo'=>'1'])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_nivel_exp',
                         ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Experiencia*");
                    ?>
                </div> 
                <div class="col-md-3">
                    <?php
                           $una_conf=Sds_com_configuracion_tipo::find() 
                           ->select(['idconfiguraciontipo'])                                   
                           ->where(['descripcion' => 'Rum_Categoria_Oferta_Lab'])
                           ->one();                           
                        
                    ?>
                    <?= $form->field($model, 'id_categoria')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => $una_conf->idconfiguraciontipo, 'activo'=>'1'])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_categoria',
                         ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Categoría*");
                    ?>
                </div> 
                <div class="col-md-3">
                    <?php
                           $una_conf=Sds_com_configuracion_tipo::find() 
                           ->select(['idconfiguraciontipo'])                                   
                           ->where(['descripcion' => 'Rum_Cualificacion'])
                           ->one();                           
                        
                    ?>
                    <?= $form->field($model, 'id_cualificacion')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => $una_conf->idconfiguraciontipo, 'activo'=>'1'])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_cualificacion',
                         ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Cualificación*");
                    ?>
                </div> 
        </div>  

        <div class="row">
                <div class="col-md-3">
                    <?php
                           $una_conf=Sds_com_configuracion_tipo::find() 
                           ->select(['idconfiguraciontipo'])                                   
                           ->where(['descripcion' => 'Rum_Tipo_Trabajo'])
                           ->one();                           
                        
                    ?>
                    <?= $form->field($model, 'id_tipo_trabajo')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => $una_conf->idconfiguraciontipo, 'activo'=>'1'])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_tipo_trabajo',
                         ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Tipo Trabajo*");
                    ?>
                </div> 
                <div class="col-md-3">
                    <?php
                           $una_conf=Sds_com_configuracion_tipo::find() 
                           ->select(['idconfiguraciontipo'])                                   
                           ->where(['descripcion' => 'Rum_Duracion_Trabajo'])
                           ->one();                           
                        
                    ?>
                    <?= $form->field($model, 'id_dur_trabajo')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => $una_conf->idconfiguraciontipo, 'activo'=>'1'])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion' 
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_dur_trabajo',
                         ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Dur. Trabajo*");
                    ?>  
                </div>
                <div class="col-md-3">  
                 <?php 
                 $el_usuario = Yii::$app->user->identity;
                 // analizando el rol
                 $un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
                           ->where(['idusuario' => $el_usuario->idusuario])
                           ->andWhere(["idrol"=> 38] )
                           ->one();  
                if ($un_rol_usuario == null) // es administrador
                { 
                    
                    echo $form->field($model, 'id_empleador')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Mds_rum_empleador::find()
                            ->where(['activo'=>'1'])                                
                            ->orderBy(['nombre' => SORT_ASC])
                            ->all(),
                            'id',
                            'nombre' 
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_empleador',
                         ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Empresa*');    
                
                }
                else
                {
                    if ($un_rol_usuario->idrol==38) 
                    {
                        echo $form->field($model, 'id_empleador')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Mds_rum_empleador::find()
                                ->where(['idpersona' => $el_usuario->idusuario]) 
                                ->andWhere(['activo'=>'1'] )
                                ->orderBy(['nombre' => SORT_ASC])
                                ->all(),
                                'id',
                                'nombre' 
                            ),
                            'options' => ['placeholder' => 'Seleccionar ...', 
                            'id' => 'cmb_empleador',
                             ],
    
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Empresa');                
                    }
                    else
                    {
                        echo $form->field($model, 'id_empleador')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Mds_rum_empleador::find()
                                ->where(['activo'=>'1'])                                
                                ->orderBy(['nombre' => SORT_ASC])
                                ->all(),
                                'id',
                                'nombre' 
                            ),
                            'options' => ['placeholder' => 'Seleccionar ...', 
                            'id' => 'cmb_empleador',
                             ],
    
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Empresa');    

                    }
                }    
                                                   
                 ?>
                    
                </div>
        </div> <!-- del row -->

    </div><br> 
    
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">

        <div class="row">

            <div class="col-md-12"> 
                        <?= $form->field($model, 'ubicacion')->textInput(['maxlength' => true]) ->label('Domicilio*');?>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6"> 
               
                <?php
                    if ($model->id_localidad ==''){}
                    else
                    {
                        //echo 'el id localidad es: '.$model->id_localidad;
                        $una_prov=Sds_com_localidad::find() 
                           ->select(['idprovincia'])                                   
                           ->where(['idlocalidad' => $model->id_localidad])
                           ->one();  
                           $model->la_provincia=$una_prov->idprovincia;
                    }

                ?>
                <?= $form->field($model, 'la_provincia')
                    ->widget(Select2::classname(), 
                    [
                    'data' => ArrayHelper::map(
                        Sds_com_provincia::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                        'idprovincia',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccionar Provincia ...', 
                    'id' => 'cmb_provincia',
                    'onchange' =>   'cargarLocalidades();',],

                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ->label('Provincia*');
                ?>

            </div>            
            <div class="col-md-6"> 
               
                <?php
                    if ($model->id_localidad =='')
                    {   
                        echo
                        $form->field($model, 'id_localidad')->widget(Select2::classname(), [
                            /*'data' => ArrayHelper::map(
                                Mds_rum_localidad::find()->orderBy(['nombre' => SORT_ASC])->all(),
                                'id',
                                'nombre'
                            ),*/
                            'options' => ['placeholder' => 'Seleccionar ...', 
                            'id' => 'cmb_localidad',
                             ],
    
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])
                        ->label("Localidad*");
                    }
                    else
                    {
                        //echo 'el id localidad es: '.$model->id_localidad;
                        echo
                        $form->field($model, 'id_localidad')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Sds_com_localidad::find()
                                ->where(['idprovincia' => $una_prov->idprovincia])
                                ->orderBy(['descripcion' => SORT_ASC])
                                ->all(),
                                'idlocalidad',
                                'descripcion'
                            ),
                            'options' => ['placeholder' => 'Seleccionar ...', 
                            'id' => 'cmb_localidad',
                             ],
    
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])
                        ->label("Localidad*");
                    }

                ?>
                    
            </div>
        </div>
    </div>
    <br>
    DURACION DE LA PUBLICACION
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-3">
                    <?php
                    if ($model->fecha_publicacion != null) {
                        $model->fecha_publicacion = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_publicacion)));
                    }
                    else
                    {
                        $model->fecha_publicacion =date('Y-m-d H:i:s');
                        $model->fecha_publicacion = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_publicacion)));
                    }
                    echo $form->field($model, 'fecha_publicacion')->widget(DatePicker::ClassName(), [
                        'name' => 'check_issue_date_desde',
                        'language' => 'es',
                        'readonly' => false,
                        // 'layout' => '{picker}{input}{remove}',
                        'layout' => !$model->isNewRecord ? '{picker}{input}' : '{picker}{input}',

                        
                        'pluginOptions' => [
                            'value' => null,
                            'format' => 'dd/mm/yyyy',
                            //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                            'todayHighlight' => true,
                            'autoclose' => true,
                        ]
                    ]);
                    ?>
            </div>
            
            <div class="col-md-3"> 
                <?php // $form->field($model, 'hora_publicacion')->textInput() ?>
                <?php
                   
                    // usage without model
                    echo 
                    $form->field($model, 'hora_publicacion')->widget(TimePicker::classname(), [
                        //'options' => ['value' =>'00:00'],
                        'options' => [
                            'id' => 'hora_publicacion',
                            'disabled' => false,
                            'class' => 'form-control input-sm',
                        ],
                        'pluginOptions' => [
                            'showSeconds' => true,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                            'secondStep' => 1,
                        ]
                    ]);
                    ?>
            </div>
            <div class="col-md-3">
                    <?php
                    if ($model->fecha_publicacionfin != null) {
                        $model->fecha_publicacionfin = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_publicacionfin)));
                    }
                    else
                    {
                        $model->fecha_publicacionfin =date('Y-m-d H:i:s');
                        $model->fecha_publicacionfin = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_publicacionfin)));
                    }
                    echo $form->field($model, 'fecha_publicacionfin')->widget(DatePicker::ClassName(), [
                        'name' => 'check_issue_date_desde',
                        'language' => 'es',
                        'readonly' => false,
                        // 'layout' => '{picker}{input}{remove}',
                        'layout' => !$model->isNewRecord ? '{picker}{input}' : '{picker}{input}',

                        
                        'pluginOptions' => [
                            'value' => null,
                            'format' => 'dd/mm/yyyy',
                            //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                            'todayHighlight' => true,
                            'autoclose' => true,
                        ]
                    ]);
                    ?>
            </div>            
            <div class="col-md-3">                 
                <?php                   
                    // usage without model
                    echo 
                    $form->field($model, 'hora_publicacionfin')->widget(TimePicker::classname(), [
                        //'options' => ['value' =>'00:00'],
                        'options' => [
                            'id' => 'hora_publicacionfin',
                            'disabled' => false,
                            'class' => 'form-control input-sm',
                        ],
                        'pluginOptions' => [
                            'showSeconds' => true,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                            'secondStep' => 1,
                        ]
                    ]);
                    ?>
            </div>
        </div>

    </div> <br>                    
    
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-4">    
                <?= $form->field($model, 'salario')->textInput() ?> 
            </div>
            <div class="col-md-4">    
                <?php echo $form->field($model, 'genero')->        
                    dropDownList(['F' => 'Femenino', 'M' => 'Masculino', 'I' => 'Otros'])
                    ->label("Genero*");                                         
                    ?>  
            </div>        
        </div>
        <div class="row">
            <div class="col-md-4">    
                <?= $form->field($model, 'activo')->checkBox(['selected' => $model->activo])?> 
            </div>
        </div>
    </div>  


   <br>
   <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">        
        <div class="row" >
            <div class="col-md-12">    
                <?= $form->field($model, 'ver_info_empresa')->checkBox(['selected' => $model->ver_info_empresa, 'id'=>'check_empresa'])?> 
            </div>                            
        </div>
        <div class="row" style="display: <?php  if ($model->ver_info_empresa==1){echo 'block';}else {echo 'none';} ?>;" id="div_info_empresa">
            <div class="col-md-12">    
            <?= $form->field($model, 'info_empresa')->textarea(['rows' => 6]) ?>            
            </div>                            
        </div>
   <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Saved!</h4>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>   

</div>



           
    <?php  //echo $form->field($model, 'fechaalta')->textInput(); ?>

    <?php //echo $form->field($model, 'horaalta')->textInput(); ?>    

   

    

    <?php //echo $form->field($model, 'num_visto')->textInput() ?>


    
   

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php
$script = <<<  JS

$('#check_empresa').on('click', function() {  
            if ($('#check_empresa').prop('checked'))
            {
                $('#div_info_empresa').show(); 
            }
            else
            {
                $('#div_info_empresa').hide();              
            }
});

function cargarLocalidades() {
    $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {             
            $("select#cmb_localidad").html(data);
    });
}

JS;

$this->registerJs($script);

?>