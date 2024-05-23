<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use app\models\Sds_com_localidad;
use app\models\Sds_com_provincia;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use  yii\bootstrap\Button;
use app\models\Mds_rum_domicilio;
use yii\helpers\Url;
use app\models\Mds_seg_usuario;
use yii\bootstrap\Modal; 
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario_rol;
$el_usuario = Yii::$app->user->identity;
// analizando el rol
$un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
->where(['idusuario' => $el_usuario->idusuario])
->andWhere(["idrol"=> 38] )
->one(); 
/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_empleador */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-empleador-form"> 

    <?php $form = ActiveForm::begin(); ?> 
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">  
                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true])->label('Nombre o Razon Social') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">  
                <?= $form->field($model, 'slogan')->textInput() ?>
            </div>
        </div>
    </div>
  
<br>
    

    <div class="row">
            <div class="col-md-6" >                
                CONTACTO 
                <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">    
                        <div class="row">
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'nombre_contacto')->textInput(['maxlength' => true]) ?>                                
                            </div>
                        </div>   
                        <div class="row">
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'cargo_contacto')->textInput(['maxlength' => true]) ?>                                
                            </div>
                        </div>                                                                                                                                                                                                                             
                        <div class="row">
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>                                
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                            <?= $form->field($model, 'telefono1')->textInput(['maxlength' => true]) ?>
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
                                        //'uploadUrl' => Url::to(['/mds_rum_empleador/update']),
                                        'uploadUrl' => Url::to(['/mds_rum_empleador']),
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
                                        'uploadUrl' => Url::to(['/mds_rum_empleador']),
                                        'maxFileSize' => 1000,
                                        'previewFileType' => 'image',
                                        'initialPreview' => [
                                            //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                            Html::img(Url::base()."/uploads/empleador/".$model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                           //CHtml::image(Yii::app()->baseUrl."/uploads/empleador/".$model->imagen);
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
      <!--  public $calle;
    public $numero;
    public $barrio;
    public $descripcion;
    public $adicional;
    public $idlocalidad;
    public $manzana;
    public $duplex;
    public $monoblock;
    public $piso;
    public $dpto;
    public $lote; -->
    DOMICILIO
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
    <?php
        if ($model->id==null)
        { //echo 'es nuevo';
        
        }
        else
        { //echo 'Ya tiene contenido';
            $un_domicilio = Mds_rum_domicilio::findOne($model->iddomicilio);                    
        }
            
           
    ?>
        <div class="row">
            <div class="col-md-5"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'calle')->textInput(['maxlength' => true])->label('Calle'); }
                    else
                    {   $model->calle=$un_domicilio->calle;
                        echo  $form->field($model, 'calle')->textInput(['maxlength' => true])->label('Calle'); 
                    } 
                ?>
                
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'numero')->textInput(['maxlength' => true])->label('Numero'); }
                    else
                    {   $model->numero=$un_domicilio->numero;
                        echo  $form->field($model, 'numero')->textInput(['maxlength' => true])->label('Numero'); } 
                ?>
                
            </div>
            <div class="col-md-3"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'manzana')->textInput(['maxlength' => true])->label('Manzana'); }
                    else
                    {   $model->manzana=$un_domicilio->manzana;
                        echo  $form->field($model, 'manzana')->textInput(['maxlength' => true])->label('Manzana'); } 
                ?>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'duplex')->textInput(['maxlength' => true])->label('Duplex'); }
                    else
                    {   $model->duplex=$un_domicilio->duplex;
                        echo  $form->field($model, 'duplex')->textInput(['maxlength' => true])->label('Duplex'); 
                    } 
                ?>                              
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'monoblock')->textInput(['maxlength' => true])->label('Monoblock'); }
                    else
                    {   $model->monoblock=$un_domicilio->monoblock;
                        echo  $form->field($model, 'monoblock')->textInput(['maxlength' => true])->label('Monoblock'); } 
                ?> 
                
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'piso')->textInput(['maxlength' => true])->label('Piso'); }
                    else
                    {   $model->piso=$un_domicilio->piso;
                        echo  $form->field($model, 'piso')->textInput(['maxlength' => true])->label('Piso'); } 
                ?>                 
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'dpto')->textInput(['maxlength' => true])->label('Dpto'); }
                    else
                    {   $model->dpto=$un_domicilio->dpto;
                        echo  $form->field($model, 'dpto')->textInput(['maxlength' => true])->label('Dpto'); } 
                ?>   
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'lote')->textInput(['maxlength' => true])->label('Lote'); }
                    else
                    {   $model->lote=$un_domicilio->lote;
                        echo  $form->field($model, 'lote')->textInput(['maxlength' => true])->label('Lote'); } 
                ?>                 
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'barrio')->textInput(['maxlength' => true])->label('Barrio'); }
                    else
                    {   $model->barrio=$un_domicilio->barrio;
                        echo  $form->field($model, 'barrio')->textInput(['maxlength' => true])->label('Barrio'); } 
                ?>    
                
            </div>

        </div>
            <div class="row">            
                <?php
                    if ($model->id==null)
                    {

                        echo '<div class="col-md-4"> ';
                                echo $form->field($model, 'idprovincia')
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
                                ->label('Provincia');
                                
                        echo '</div>';
                        echo '<div class="col-md-4"> ';
                                echo
                                $form->field($model, 'idlocalidad')->widget(Select2::classname(), [                            
                                    'options' => ['placeholder' => 'Seleccionar ...', 
                                    'id' => 'cmb_localidad',
                                    ],
            
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])
                                ->label('Localidad');
                        echo '</div>';

                    }
                    else // para editar
                    {

                        if ($un_domicilio->idlocalidad ==null)
                        {

                            echo '<div class="col-md-4"> ';
                                echo $form->field($model, 'idprovincia')
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
                                ->label('Provincia');
                                
                            echo '</div>';
                            echo '<div class="col-md-4"> ';
                                    echo
                                    $form->field($model, 'idlocalidad')->widget(Select2::classname(), [                            
                                        'options' => ['placeholder' => 'Seleccionar ...', 
                                        'id' => 'cmb_localidad',
                                        ],
                
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])
                                    ->label('Localidad');
                            echo '</div>';

                        }
                        else // para editar:: existe la localidad
                        {   $model->idlocalidad=$un_domicilio->idlocalidad;
                            //buscamos el id de la provincia correspondiente a la localidad
                            $una_prov=Sds_com_localidad::find() 
                            ->select(['idprovincia'])                                   
                            ->where(['idlocalidad' => $un_domicilio->idlocalidad])
                            ->one();  
                            $model->idprovincia=$una_prov->idprovincia;

                            echo '<div class="col-md-4"> ';
                            echo  $form->field($model, 'idprovincia')
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
                                ->label('Provincia');
                            echo '</div>';
                            echo '<div class="col-md-4"> ';
                                echo
                                $form->field($model, 'idlocalidad')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        Sds_com_localidad::find()
                                        ->where(['idprovincia' => $model->idprovincia])
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
                                ]);
                                echo '</div>';
                        }
                    
                    }

                ?>
                
            </div> 
                    
           
        </div>
 
     
    <br>  
    OTROS
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                            $una_conf=Sds_com_configuracion_tipo::find() 
                            ->select(['idconfiguraciontipo'])                                   
                            ->where(['descripcion' => 'Rum_Categoria_Oferta_Lab'])
                            ->one();                           
                            //echo 'el id_categoria es: '.$model->id_categoria.' fin';
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
                            'id' => 'cmb_para_categoria',
                            
                            ],

                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?> 
                    </div>
                </div>
                <?php
                    if ($un_rol_usuario == null) // es administrador
                    {   echo "
                        <div class='row'>
                            <div class='col-md-12'>";
                                echo $form->field($model, 'estado')->        
                                dropDownList(['1' => 'Espera Validación', '2' => 'Pendiente de Aprobación', '3' => 'Aceptada', '4' => 'Rechazada']);
                            echo "    
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-4'>";    
                                echo $form->field($model, 'activo')->checkBox(['selected' => $model->activo]);
                            echo "
                            </div>
                        </div>";
                    }
                        
                ?>


                
            </div>   
            <?php
                    if ($un_rol_usuario == null) // es administrador
                    {
                        echo "
                        <div class='col-md-3'>              
                        CUIT
                            <div style='border: ridge 1px; padding: 8px; border-color:#D8D8D8;'>                        
                                    <div class='row'>                    
                                        <div class='col-md-12'>"; 
                                            echo $form->field($model, 'cuit')->textInput()->label(false);
                                            echo "
                                        </div>
                                    </div>
                                    <div class='row'>                    
                                        <div class='col-md-12'>";                                         
                                             
                                                $url =  Url::to("https://seti.afip.gob.ar/padron-puc-constancia-internet/ConsultaConstanciaAction.do",true);
                                                echo Html::a('<i class="far fa-check-circle"></i> Validar Cuit', $url, [
                                                                                'role' => 'post', 'data-pjax' => 0, 'target' => '_blank','class' => 'btn btn-outline-primary',]);
                                        echo "    
                                        </div>
                                    </div>
                            </div>
                        </div>";                        
                    }
                    else
                    {
                        echo "
                        <div class='col-md-3'>              
                        CUIT
                            <div style='border: ridge 1px; padding: 8px; border-color:#D8D8D8;'>                        
                                    <div class='row'>                    
                                        <div class='col-md-12'>";                                             
                                            echo $form->field($model, 'cuit')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label(false);
                                            echo "
                                        </div>
                                    </div>                                    
                            </div>
                        </div>";     
                    }
                    
            ?>
            
            <?php
                
                if ($un_rol_usuario == null) // es administrador
                {
                    if ($model->id == null) 
                    { }
                    else
                    { echo '
                        <div class="col-md-5">  
                            USUARIO ASOCIADO
                            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                        
                                <div class="row">                    
                                    <div class="col-md-12">  ';
                                        $label='<div class=\'button\' style="float:left;background-color: transparent">'. 
                                            'Nombre de Usuario <a href="index.php?r=mds_seg_usuario/create2&id_empresa='.$model->id.' & nombre_empresa='.$model->nombre. 
                                            '" title aria-label="Crear Usuario Asociado" data-pjax="0" role="modal-remote" data-toggle="tooltip" data-original-title="Editar" >
                                            <span class= "glyphicon glyphicon-plus" aria-hidden="true"></span></a></div>';
                                            
                                            echo $form->field($model, 'idpersona')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map(
                                                    Mds_seg_usuario::find()
                                                    ->where(['activo'=>'1'])
                                                    ->orderBy(['user' => SORT_ASC])
                                                    ->all(),
                                                    'idusuario',
                                                    'user'
                                                ),
                                                'options' => ['placeholder' => 'Seleccionar ...', 
                                                'id' =>'cmb_para_usuarios',                                
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label($label);
                                        echo '</div>                    
                                    </div>
                                    
                                </div>
                                
                            </div> '; 
                        
                    }
                }
                else
                {// es un usuario de alguna empresa 
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
<?php
$script = <<<  JS

function cargarLocalidades() {
    $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {             
            $("select#cmb_localidad").html(data);
    });
}

$('.btn-ajax-modal').click(function (){
       var elm = $(this),
           target = elm.attr('data-target'),
           ajax_body = elm.attr('value');

       $(target).modal('show')
           .find('.modal-content')
           .load(ajax_body);
});

JS;

$cadena_enviar='"index.php?r=mds_rum_empleador/enviar_datos&id='.$model->id.'"';
$js = <<< JS
    $("#boton_not").on("click", 
        function() 
        {        
            var cmb_users=$("#cmb_para_usuarios option:selected").text();
            if (cmb_users=="Seleccionar ...")
            {
                krajeeDialog.alert("No se pueden enviar los datos de la cuenta \\n  Aun no asoció un usuario a la Empresa");
            }
            else
            {
                krajeeDialog.confirm("¿Seguro desea enviar un email, con los datos de la cuenta, al usuario asociado?", 
                function (result) 
                {
                        if (result) 
                        {
                                // Aqui debe ir el codigo que llama a la accion del controlador                                
                                $.post($cadena_enviar, 
                                function(data) 
                                {                                                                          
                                    krajeeDialog.alert("Se ha enviado el email con exito.");
                                });
                        } 
                });
            }                                            
            return false;
        });
JS;
$this->registerJs($script);
$this->registerJs($js);
?>