<?php

use app\models\Sds_800_atencion;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use app\models\Sds_com_provincia;

use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Llamada 0800 - Atencion Situación de Calle';
$this->params['breadcrumbs'][] = $this->title;
?>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="sds-800-atencion-form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <h5><b>Fecha de Atención: </b>
                                <?php echo date_format(
                                    date_create($model->fecha_hora),
                                    'd/m/Y H:i'
                                ); ?></h5>
                        </div>
                        <?php if (!$model->isNewRecord) : ?>
                            <div class="col-md-6 text-right">
                                <h5><b>Atendió: </b>
                                    <?php echo $model->idusuario0->nombre .
                                        ' ' .
                                        $model->idusuario0->apellido; ?></h5>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_atencion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_atencion" href="#atencion">
                                        Persona en Situación de Calle
                                    </a>
                                </h4>
                            </div>
                            <div id="atencion" class="accordion-body collapse in">
                                <div class="panel-body" id="atencion_content">
                                    <?php if (
                                        $model->isNewRecord ||
                                        (!$model->isNewRecord &&
                                            $model->dni !== null)
                                    ) : ?>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row"></div>
                                                <?= $form
                                                    ->field($model, 'dni')
                                                    ->textInput([
                                                        'id' => 'txtDNI',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-3" style="padding-top:25px;">
                                                <?php echo Html::a(
                                                    '<i class="glyphicon glyphicon-search"></i>',
                                                    null,
                                                    [
                                                        'name' => 'btn_dni',
                                                        'id' => 'btn_dni',
                                                        'data-request-method' =>
                                                        'post',
                                                        'data-toggle' =>
                                                        'tooltip',
                                                        'class' =>
                                                        'btn btn-primary',
                                                        'title' => Yii::t(
                                                            'app',
                                                            'Consultar DNI Llamante'
                                                        ),
                                                    ]
                                                ) .
                                                    Html::a(
                                                        '<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">',
                                                        null,
                                                        [
                                                            'name' => 'btn_pui',
                                                            'id' => 'btn_pui',
                                                            'data-request-method' =>
                                                            'post',
                                                            'data-toggle' =>
                                                            'tooltip',
                                                            'style' =>
                                                            'padding:0px;padding-left:2px;',
                                                            'class' => 'btn',
                                                            'title' => Yii::t(
                                                                'app',
                                                                'Consulta a Portal Unificado'
                                                            ),
                                                        ]
                                                    ); ?>
                                            </div>
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field($model, 'nombre')
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form
                                                    ->field($model, 'apellido')
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>
                                            <div class="col-md-2">
                                                <?php
                                                if (
                                                    $model->fecha_nacimiento !=
                                                    null
                                                ) {
                                                    $model->fecha_nacimiento = date(
                                                        'd/m/Y',
                                                        strtotime(
                                                            str_replace(
                                                                '/',
                                                                '-',
                                                                $model->fecha_nacimiento
                                                            )
                                                        )
                                                    );
                                                }
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'fecha_nacimiento'
                                                    )
                                                    ->widget(
                                                        DatePicker::class,
                                                        [
                                                            'name' =>
                                                            'check_issue_date',
                                                            'language' => 'es',
                                                            'readonly' => false,
                                                            'layout' =>
                                                            '{picker}{input}{remove}',
                                                            'options' => [
                                                                'id' =>
                                                                'fecha_nacimiento',
                                                                'class' =>
                                                                'form-control input-md',
                                                                'disabled' => true,
                                                            ],
                                                            'pluginOptions' => [
                                                                'value' => null,
                                                                'format' =>
                                                                'dd/mm/yyyy',
                                                                'endDate' => date(
                                                                    'd/m/Y'
                                                                ),
                                                                'todayHighlight' => true,
                                                                'autoclose' => true,
                                                            ],
                                                        ]
                                                    )
                                                    ->label(
                                                        'Fecha de Nacimiento'
                                                    );
                                                    
                                                ?>
                                                <small id='edad'><?php echo $model->edad ? ($model->edad == 1 ? "Edad {$model->edad} año " : "Edad {$model->edad} años ") : "" ?></small>
                                            </div>
                                            <div class="col-md-2">
                                                <?= $form
                                                    ->field($model, 'edad')
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) 
                                                    ->label(
                                                        'Edad que dice tener'
                                                    ) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <?= $form
                                                    ->field(
                                                        $model,
                                                        'nacionalidad'
                                                    )
                                                    ->dropdownList(
                                                        ArrayHelper::map(
                                                            Sds_com_configuracion::getConfiguracionesActivas(
                                                                Sds_com_configuracion_tipo::TIPO_NACIONALIDAD,
                                                                false
                                                            ),
                                                            'idconfiguracion',
                                                            'descripcion'
                                                        ),
                                                        [
                                                            'prompt' =>
                                                            'Seleccionar Nacionalidad ...',
                                                            'disabled' =>
                                                            'true',
                                                        ]
                                                    ) ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?= $form
                                                    ->field($model, 'sexo')
                                                    ->dropdownList(
                                                        ArrayHelper::map(
                                                            Sds_com_configuracion::getConfiguracionesActivas(
                                                                Sds_com_configuracion_tipo::TIPO_GENERO,
                                                                false
                                                            ),
                                                            'idconfiguracion',
                                                            'descripcion'
                                                        ),
                                                        [
                                                            'prompt' =>
                                                            'Seleccionar Género ...',
                                                            'disabled' =>
                                                            'true',
                                                        ]
                                                    ) ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?= $form
                                                ->field($model, 'generoautopercibido')
                                                ->dropdownList(
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion ::find()                        
                                                            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: TIPO_GENERO_AUTOPERCIBIDO])
                                                            ->orderBy(['descripcion' => SORT_ASC])
                                                            ->all(),
                                                            'idconfiguracion',
                                                            'descripcion'
                                                    ),
                                                    [
                                                        'prompt' =>
                                                        'Sel. Género Autopercibido ...',
                                                        'id' => 'generoautopercibido',
                                                        'disabled' => 'true',
                                                    ]
                                                ) ?>
                                            </div>
                                            

                                            <div class="col-md-3"> 
                                                <?= $form
                                                    ->field($model, 'telefono')
                                                    ->textInput([
                                                        'disabled' => 'true',
                                                    ]) ?>
                                            </div>
                                        
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">

                                                <?php
                                                   
                                                    if ($model->localidad ==''){}
                                                    else
                                                    {                                                        
                                                        $una_prov=Sds_com_localidad::find() 
                                                        ->select(['idprovincia'])                                   
                                                        ->where(['idlocalidad' => $model->localidad])
                                                        ->one();  
                                                        $model->provincia=$una_prov->idprovincia;
                                                    }

                                               
                                                
                                                ?>
                                                       <?= $form
                                                    ->field(
                                                        $model,
                                                        'provincia'
                                                    )
                                                    ->dropdownList(
                                                        ArrayHelper::map(
                                                            Sds_com_provincia::find()->where(["activo" => 1])->orderBy(['descripcion' => SORT_ASC])->all(),
                                                            'idprovincia',
                                                            'descripcion'
                                                        ),
                                                        [
                                                            'prompt' =>
                                                            'Seleccionar Provincia ...',
                                                            'id' => 'cmb_provincia',
                                                            'onchange' => 'cargarLocalidades();',
                                                            'disabled' => true
                                                        ]
                                                    ) ->label('Provincia');?>
                                                       
                                                
                                            </div>
                                            <?php

                                                if ($model->localidad =='')
                                                {
                                                    echo' <div class="col-md-3">';                                                                                                       

                                                    echo $form
                                                    ->field(
                                                        $model,'localidad')
                                                    ->dropdownList(   
                                                        []  ,                                                   
                                                        [
                                                            'prompt' =>
                                                            'Seleccionar Localidad ...',                                                            
                                                            'id' => 'localidad',                                                            
                                                            'disabled' => true
                                                        ]
                                                    ) ->label('Localidad');

                                                    
                                                echo '</div>';

                                                }
                                                else
                                                {
                                                    echo' <div class="col-md-3">';
                                                    echo $form
                                                    ->field(
                                                        $model,'localidad')
                                                    ->dropdownList(   
                                                        ArrayHelper::map(
                                                            Sds_com_localidad::find()->where(['idprovincia' => $una_prov->idprovincia])->orderBy(['descripcion' => SORT_ASC])->all(),
                                                            'idlocalidad',
                                                            'descripcion'
                                                        ),                                                                                                             
                                                        [
                                                            'prompt' =>
                                                            'Seleccionar Localidad ...',
                                                            'disabled' =>
                                                            'true',
                                                            'id' => 'localidad',                                                            
                                                            'disabled' => true
                                                        ]
                                                    ) ->label('Localidad');


                                                    
                                                    echo' </div>';

                                                }
                                            ?>                                                                                
                                            <div class="col-md-3">
                                            <?php
                                                   
                                                   if ($model->idlocalidadoriundo ==''){}
                                                   else
                                                   {                                                        
                                                       $una_prov=Sds_com_localidad::find() 
                                                       ->select(['idprovincia'])                                   
                                                       ->where(['idlocalidad' => $model->idlocalidadoriundo])
                                                       ->one();  
                                                       $model->provinciaoriundo=$una_prov->idprovincia;
                                                   }
                                                          
                                                   

                                                   echo $form
                                                    ->field(
                                                        $model,'provinciaoriundo')
                                                    ->dropdownList(   
                                                        ArrayHelper::map(
                                                            Sds_com_provincia::find()->where(["activo" => 1])->orderBy(['descripcion' => SORT_ASC])->all(),
                                                            'idprovincia',
                                                            'descripcion'
                                                        ),                                                                                                           
                                                        [
                                                            'prompt' =>
                                                            'Seleccionar Provincia ...',                                                            
                                                            'id' => 'cmb_provinciaoriundo',  
                                                            'onchange' => 'cargarLocalidades2();',                                                          
                                                            'disabled' => true
                                                        ]
                                                    ) ->label('Provincia Oriundo');
                                               ?>
                                                          
                                                          
                                               
                                            </div>
                                            <div class="col-md-3">

                                            <?php
                                                    if ($model->idlocalidadoriundo =='')
                                                    {
                                                        echo $form
                                                        ->field(
                                                        $model,'idlocalidadoriundo')
                                                        ->dropdownList(   
                                                            []  ,     
                                                                                                                                                              
                                                        [
                                                            'prompt' =>'Seleccionar Localidad ...',
                                                            'id' => 'localidadoriundo',                                                                                                                      
                                                            'disabled' => true
                                                        ]
                                                    ) ->label('Localidad Oriundo');

                                                        
                                                    }
                                                    else
                                                    {
                                                        echo $form
                                                        ->field(
                                                        $model,'idlocalidadoriundo')
                                                        ->dropdownList(  
                                                            ArrayHelper::map(
                                                                Sds_com_localidad::find()->where(['idprovincia' => $una_prov->idprovincia])->orderBy(['descripcion' => SORT_ASC])->all(),
                                                                'idlocalidad',
                                                                'descripcion'
                                                            ), 
                                                                                                                                                              
                                                        [
                                                            'prompt' =>'Seleccionar Localidad ...',
                                                            'id' => 'localidadoriundo',                                                                                                                      
                                                            'disabled' => true
                                                        ]
                                                    ) ->label('Provincia Oriundo');

                                                       

                                                    }


                                            ?>
                                            
                                                
                                            </div>
                                           
                                        </div>

                                        

                                    <?php else : ?>
                                        <p><b>Atención:</b> Sin Documento</p>
                                        <?= $form
                                            ->field($model, 'persona_datos')
                                            ->textarea(['rows' => 2]) ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>













                    <?php echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_aspsocial">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_aspsocial" href="#aspsocial">
                                        Aspectos Sociales
                                    </a>
                                </h4>
                            </div>
                            <div id="aspsocial" class="accordion-body collapse in">
                                <div class="panel-body" id="atencion_content">                                                                            
                                    <div class="row">                                    
                                        <div class="col-md-12" id="causa_situacion_texto_container">
                                            <?= $form->field($model, 'causa_situacion')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'causa_situacion_texto',
                                                ],
                                            ])->label("Motivo de situación de calle") ?>
                                        </div>   
                                    </div>                                                                                                                 
                                    <div class="row">  
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'familia')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::FAMILIA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::FAMILIA_TIENE_VINCULO =>
                                                        'Si tiene y con vínculos adecuados',
                                                        Sds_800_atencion::FAMILIA_TIENE_SIN_VINCULO =>
                                                        'Si tiene y sin vínculos adecuados',
                                                        Sds_800_atencion::FAMILIA_NO_TIENE =>
                                                        'No tiene',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                   
                                                ) 
                                                ->label("Red social y familiar ")
                                                ?>
                                        </div>                                   
                                        <div class="col-md-4">
                                                <?php

                                                    echo $form
                                                    ->field(
                                                        $model,'tipo_ayuda')
                                                    ->dropdownList(   
                                                        ArrayHelper::map(
                                                            Sds_com_configuracion ::find()
                                                            
                                                            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: TIPO_AYUDA])
                                                            ->orderBy(['descripcion' => SORT_ASC])
                                                            ->all(),
                                                            'idconfiguracion',
                                                            'descripcion'
                                                        ),                                                                                                       
                                                        [
                                                            'prompt' =>
                                                            'Seleccionar tipo ayuda ...',
                                                            'disabled' =>'true',
                                                            'id' => 'tipo_ayuda',                                                                                                                
                                                            'disabled' => false
                                                        ]
                                                    );

                                                ?>
                                                
                                                            
                                        </div>
                                        <div class="col-md-4">
                                                <?= 
                                                
                                                
                                                 $form
                                                    ->field(
                                                        $model,'expectativa_corto_plazo')
                                                    ->dropdownList(   
                                                        ArrayHelper::map(
                                                            Sds_com_configuracion ::find()
                                                            
                                                            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: EXPECTATIVA_CORTO_PLAZO])
                                                            ->orderBy(['descripcion' => SORT_ASC])
                                                            ->all(),
                                                            'idconfiguracion',
                                                            'descripcion'
                                                        ),                                                                                              
                                                        [
                                                            'prompt' =>
                                                            'Seleccionar opción ...',
                                                            'disabled' =>'true',
                                                            'id' => 'expectativa_corto_plazo',                                                                                                                
                                                            'disabled' => false
                                                        ]
                                                    );
                                                
                                                                                                
                                                 ?>
                                        </div>                                                                                                                                                          
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'evaluacion_funcional'
                                                )
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::FUNCIONAL_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::FUNCIONAL_DEPENDIENTE =>
                                                        'Totalmente Dependiente',
                                                        Sds_800_atencion::FUNCIONAL_CASI_DEPENDIENTE =>
                                                        'Dependiente en Algunas o Varias Actividades',
                                                        Sds_800_atencion::FUNCIONAL_INDEPENDIENTE =>
                                                        'Independiente',
                                                        Sds_800_atencion::FUNCIONAL_OTRO =>
                                                        'Otro',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-8">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'evaluacion_funcional_detalle'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->evaluacion_funcional ==
                                                        0,
                                                ]) ?>
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>
                        </div>
                    </div>


                    



                    <?php echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_habitacional">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_habitacional" href="#habitacional">
                                    Aspectos Habitacionales
                                    </a>
                                </h4>
                            </div>
                            <div id="habitacional" class="accordion-body collapse in">
                                <div class="panel-body" id="atencion_content">                                    
                                        
                                <div class="row">
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'antiguedad')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::ANTIGUEDAD_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::ANTIGUEDAD_MENOS_1 =>
                                                        'menos de 1 años',
                                                        Sds_800_atencion::ANTIGUEDAD_1_5 =>
                                                        'entre 1 y 5 años',
                                                        Sds_800_atencion::ANTIGUEDAD_MAS_5 =>
                                                        'mas de 5 años',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) 
                                                ->label("Hace cuanto se encuentra en situación de calle? ")
                                                ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'ubicacion_anterior'
                                                )
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::UBICACION_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::UBICACION_FAMILIAR =>
                                                        'En la casa de un familiar',
                                                        Sds_800_atencion::UBICACION_CUENTA_PROPIA =>
                                                        'Alquilaba por cuenta propia',
                                                        Sds_800_atencion::UBICACION_ESTADO =>
                                                        'Le alquilaba algún efector del estado',
                                                        Sds_800_atencion::UBICACION_OTRO =>
                                                        'Otro',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>

                                        <div class="col-md-6">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'ubicacion_anterior_detalle'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->ubicacion_anterior ==
                                                        0,
                                                ]) ?>
                                        </div>



                                        
                                    </div>
                                   
                                    

                                    <div class="row">
                                        <div class="col-md-3">
                                                <?= 
                                                
                                                $form
                                                ->field(
                                                    $model,'motivo_abandono')
                                                ->dropdownList(   
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion ::find()
                                                        
                                                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: MOTIVO_ABANDONO])
                                                        ->orderBy(['descripcion' => SORT_ASC])
                                                        ->all(),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),                                                                                     
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar opción ...',
                                                        'disabled' =>'true',
                                                        'id' => 'motivo_abandono',                                                                                                                
                                                        'disabled' => false
                                                    ]
                                                );

                                                        
                                                ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'atencion_anterior'
                                                )
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'atencion_anterior_institucion'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                    'disabled' =>
                                                    $model->atencion_anterior !=
                                                        1,
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'atencion_anterior_profesional'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                    'disabled' =>
                                                    $model->atencion_anterior !=
                                                        1,
                                                ]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'asistencia_estado'
                                                )
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) 
                                                ->label("Recorrido Institucional")
                                                ?>
                                        </div>
                                        <div class="col-md-8">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'asistencia_estado_detalle'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->asistencia_estado !=
                                                        1,
                                                ])
                                                ->label('¿Cuál?') ?>
                                        </div>
                                    </div>

                                </div>                                
                            </div>
                        </div>
                    </div>





                    <?php echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_salud">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_salud" href="#aspsalud">
                                    Aspectos de Salud
                                    </a>
                                </h4>
                            </div>
                            <div id="aspsalud" class="accordion-body collapse in">
                                <div class="panel-body" id="atencion_content">                                    
                                        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'sentimiento')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::SENTIMIENTO_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::SENTIMIENTO_BIEN =>
                                                        'Bien',
                                                        Sds_800_atencion::SENTIMIENTO_MAL =>
                                                        'Mal',
                                                        Sds_800_atencion::SENTIMIENTO_ELECCION =>
                                                        'Es una eleccion de vida',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-3">
                                                <?=
                                                $form
                                                ->field(
                                                    $model,'situacion_salud')
                                                ->dropdownList(   
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion ::find()
                                                        
                                                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: SITUACION_SALUD])
                                                        ->orderBy(['descripcion' => SORT_ASC])
                                                        ->all(),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),                                                                              
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar opción ...',
                                                        'id' => 'situacion_salud',                                                                                                                
                                                        'disabled' => false
                                                    ]
                                                );
                                                                                                                                                                                                             
                                                ?>
                                        </div>
                                        <div class="col-md-3">
                                                <?= 
                                                
                                                $form
                                                ->field(
                                                    $model,'consumo_problematico')
                                                ->dropdownList(   
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion ::find()
                                                        
                                                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: CONSUMO_PROBLEMATICO])
                                                        ->orderBy(['descripcion' => SORT_ASC])
                                                        ->all(),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),                                                                       
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar opción ...',
                                                        'id' => 'consumo_problematico',                                                                                                                
                                                        'disabled' => false
                                                    ]
                                                );
                                                                                               
                                                ?>
                                        </div>
                                        <div class="col-md-3">
                                                <?= 
                                                $form
                                                ->field(
                                                    $model,'capacidad_limitada')
                                                ->dropdownList(   
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion ::find()
                                                        
                                                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: CAPACIDAD_LIMITADA])
                                                        ->orderBy(['descripcion' => SORT_ASC])
                                                        ->all(),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),                                                              
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar opción ...',
                                                        'id' => 'capacidad_limitada',                                                                                                                
                                                        'disabled' => false
                                                    ]
                                                );

                                                ?>
                                        </div>
                                        
                                        
                                    
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'orientado')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) 
                                                ->label("¿Se encuentra orientado?")
                                                ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'intoxicado')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'violentado')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>

                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'expresar')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>
                                        
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'beneficio')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        'Seleccione una opción',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-2">
                                            <?= $form
                                                ->field($model, 'tratamiento')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        'Seleccione una opción',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'tratamiento_institucion'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                    'disabled' =>
                                                    $model->tratamiento !=
                                                        1,
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'tratamiento_profesional'
                                                )
                                                ->textInput([
                                                    'maxlength' => true,
                                                    'disabled' =>
                                                    $model->tratamiento !=
                                                        1,
                                                ]) ?>
                                        </div>
                                    </div>
                                    <div class="row">  
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'alucinaciones')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                    ]
                                                ) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_aspeconom">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_aspeconom" href="#aspeconom">
                                    Aspectos Económicos
                                    </a>
                                </h4>
                            </div>
                            <div id="aspeconom" class="accordion-body collapse in">
                                <div class="panel-body" id="atencion_content">                                    
                                        
                                <div class="row">                               
                                        <div class="col-md-2">
                                            <?= $form
                                                ->field($model, 'nivel_estudio')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::ESTUDIO_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::ESTUDIO_PRIMARIO_INCOMPLETO =>
                                                        'Primario Incompleto',
                                                        Sds_800_atencion::ESTUDIO_PRIMARIO_COMPLETO =>
                                                        'Primario Completo',
                                                        Sds_800_atencion::ESTUDIO_SECUNDARIO_INCOMPLETO =>
                                                        'Secundario Incompleto',
                                                        Sds_800_atencion::ESTUDIO_SECUNDARIO_COMPLETO =>
                                                        'Secundario Completo',
                                                        Sds_800_atencion::ESTUDIO_TERCIARIO_OTRO_INCOMPLETO =>
                                                        'Terciario/Otro Incompleto',
                                                        Sds_800_atencion::ESTUDIO_TERCIARIO_OTRO_COMPLETO =>
                                                        'Terciario/Otro Completo',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        'Seleccione una opción',
                                                    ]
                                                ) ?>
                                        </div>                                    
                                        <div class="col-md-2">
                                            <?= $form
                                                ->field($model, 'sabe_leer')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::RESPUESTA_SIN_DATOS =>
                                                        'Sin Datos',
                                                        Sds_800_atencion::RESPUESTA_SI =>
                                                        'Si',
                                                        Sds_800_atencion::RESPUESTA_NO =>
                                                        'No',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        'Seleccione una opción',
                                                    ]
                                                ) ?>
                                        </div>
                                                                  
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'trabajo')
                                                ->dropDownList(
                                                    [
                                                        Sds_800_atencion::TRABAJO_NO =>
                                                        'No',
                                                        Sds_800_atencion::TRABAJO_FORMAL =>
                                                        'Formal',
                                                        Sds_800_atencion::TRABAJO_INFORMAL =>
                                                        'Informal',
                                                    ],
                                                    [
                                                        'prompt' =>
                                                        '-- Seleccione una opción --',
                                                        'id' => 'cmb_trabajo',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'trabajo_detalle'
                                                )
                                                ->textarea([
                                                    'rows' => 2,
                                                    'disabled' =>
                                                    $model->trabajo == 0,
                                                ]) ?>
                                        </div>
                                    
                                </div>
                                <div class="row">
                                        <div class="col-md-3">
                                                <?= 
                                                
                                                $form
                                                ->field(
                                                    $model,'r_situacion_laboral')
                                                ->dropdownList(   
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion ::find()
                                                        
                                                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: R_SITUACION_LABORAL])
                                                        ->orderBy(['descripcion' => SORT_ASC])
                                                        ->all(),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),                                                          
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar opción ...',
                                                        'id' => 'r_situacion_laboral',                                                                                                                
                                                        'disabled' => false
                                                    ]
                                                );                                                          
                                                ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'oficio')
                                                ->textInput()
                                                ->label(
                                                    'Oficio'
                                                ) ?>
                                        </div>
                                        <div class="col-md-3">
                                                <?= 
                                                $form
                                                ->field(
                                                    $model,'aportes_economicos')
                                                ->dropdownList(   
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion ::find()
                                                        
                                                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: APORTES_ECONOMICOS])
                                                        ->orderBy(['descripcion' => SORT_ASC])
                                                        ->all(),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),                                             
                                                    [
                                                        'prompt' =>
                                                        'Seleccionar opción ...',
                                                        'id' => 'aportes_economicos',                                                                                                                
                                                        'disabled' => false
                                                    ]
                                                );             
                                                                                               
                                                ?>
                                        </div>
                                    </div>



                                    
                                    

                            </div>
                        </div>
                    </div>


                    <?php echo Collapse::widget([]); ?>                 
                    <div class="panel-group" id="accordion_detalle">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle" href="#detalle">
                                        Informacion Complementaria
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle" class="accordion-body collapse in">
                                <div class="panel-body" id="detalle_content">
                                   

                                    <div class="row">
                                        <div class="col-md-12" id="observaciones_texto_container">
                                            <?= $form->field($model, 'observaciones')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'observaciones_texto',
                                                ],
                                            ])->label("") ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?php if (
                                                $model->archivo_salud == null
                                            ) {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'temp_archivo_salud',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(
                                                        FileInput::class,
                                                        [
                                                            'options' => [
                                                                'accept' =>
                                                                'image/*,.pdf',
                                                            ],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => [
                                                                    'jpg',
                                                                    'jpeg',
                                                                    'gif',
                                                                    'png',
                                                                    'bmp',
                                                                    'pdf',
                                                                ],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' =>
                                                                'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/mds_com_intervencion/update',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 52428800, // 50MB
                                                                'previewFileType' =>
                                                                'file',
                                                                'initialCaption' =>
                                                                $model->archivo_salud,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => true,
                                                                    'showUpload' => false,
                                                                ],
                                                            ],
                                                        ]
                                                    );
                                            } else {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'temp_archivo_salud',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(
                                                        FileInput::class,
                                                        [
                                                            'options' => [
                                                                'accept' =>
                                                                'image/*,.pdf',
                                                            ],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => [
                                                                    'jpg',
                                                                    'jpeg',
                                                                    'gif',
                                                                    'png',
                                                                    'bmp',
                                                                    'pdf',
                                                                ],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' =>
                                                                'input-group-sm',
                                                                'uploadUrl' => Url::to(
                                                                    [
                                                                        '/sds_800_atencion/update',
                                                                    ]
                                                                ),
                                                                'maxFileSize' => 52428800, // 50MB
                                                                'previewFileType' =>
                                                                'file',
                                                                'initialPreview' => [
                                                                    Html::img(
                                                                        $model->archivo_salud,
                                                                        [
                                                                            'class' =>
                                                                            'file-preview-image',
                                                                            'style' =>
                                                                            'width:100%; text-align: center',
                                                                        ]
                                                                    ),
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' =>
                                                                $model->archivo_salud,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => true,
                                                                    'showUpload' => false,
                                                                ],
                                                            ],
                                                            'pluginEvents' => [
                                                                'fileclear' =>
                                                                "function() { console.log('fileclear'); $('#borrar').val(true);}",
                                                                'filereset' =>
                                                                'function() {  }',
                                                            ],
                                                        ]
                                                    );
                                            } ?>
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'borrar_adjunto'
                                                )
                                                ->hiddenInput([
                                                    'id' => 'borrar',
                                                ])
                                                ->label(false) ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <?= $form
                        ->field($model, 'idusuario')
                        ->hiddenInput()
                        ->label(false) ?>

                    <?= $form
                        ->field($model, 'idllamada')
                        ->hiddenInput()
                        ->label(false) ?>


                    <div class="row justify-content-between">
                        <div class="col-md-6">
                            <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php if (!Yii::$app->request->isAjax) { ?>
                                <div class="form-group">
                                    <?= Html::submitButton(
                                        $model->isNewRecord
                                            ? 'Crear'
                                            : 'Modificar',
                                        [
                                            'class' => $model->isNewRecord
                                                ? 'btn btn-success'
                                                : 'btn btn-primary',
                                            'id' => 'btnGuardar',
                                        ]
                                    ) ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <?= $form
                        ->field($model, 'idpersona')
                        ->hiddenInput()
                        ->label(false) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php $this->registerJs(
    "$(document).ready(function() {        
        datos_persona(true);
    });

    $('#txtDNI').change(function(){        
        datos_persona(false);
    });

    $('#cmb_trabajo').change(function(){        
        $('#sds_800_atencion-trabajo_detalle').prop('disabled', $('#cmb_trabajo option:selected').val()<=0);
    });

    $('#sds_800_atencion-ubicacion_anterior').change(function(){        
        $('#sds_800_atencion-ubicacion_anterior_detalle').prop('disabled', $('#sds_800_atencion-ubicacion_anterior option:selected').val()<=0);
    });
    
    $('#sds_800_atencion-atencion_anterior').change(function(){        
        $('#sds_800_atencion-atencion_anterior_institucion').prop('disabled', $('#sds_800_atencion-atencion_anterior option:selected').val()!=1);
        $('#sds_800_atencion-atencion_anterior_profesional').prop('disabled', $('#sds_800_atencion-atencion_anterior option:selected').val()!=1);
    });
    
    $('#sds_800_atencion-asistencia_estado').change(function(){        
        $('#sds_800_atencion-asistencia_estado_detalle').prop('disabled', $('#sds_800_atencion-asistencia_estado option:selected').val()!=1);
    });
    
    $('#sds_800_atencion-evaluacion_funcional').change(function(){        
        $('#sds_800_atencion-evaluacion_funcional_detalle').prop('disabled', $('#sds_800_atencion-evaluacion_funcional option:selected').val()<=0);
    });
    
    $('#sds_800_atencion-tratamiento').change(function(){        
        $('#sds_800_atencion-tratamiento_institucion').prop('disabled', $('#sds_800_atencion-tratamiento option:selected').val()!=1);
        $('#sds_800_atencion-tratamiento_profesional').prop('disabled', $('#sds_800_atencion-tratamiento option:selected').val()!=1);
    });
    
    $('#sds_800_atencion-sin_dni').change(function(){        
        $('#txtDNI').prop('disabled', $('#sds_800_atencion-sin_dni').prop('checked'));
        if ($('#sds_800_atencion-sin_dni').prop('checked')){
            $('#btn_dni').hide();
            habilitar_controles();
        }
        else {
            $('#btn_dni').show();
        }
    });

    $('#btn_dni').click(function(){        
        datos_persona(false);
    });
    
    $('#btn_pui').click(function(){        
        var dni_campo = $('#txtDNI').val();        
        window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
    });

    $('#btnGuardar').click(function(e){
        const causa_situacion_texto =  $('#causa_situacion_texto').val();
        const parser = new DOMParser();
        const { textContent } = parser.parseFromString(causa_situacion_texto, 'text/html').documentElement;
        causaSituacionAccionTextoSinHTML = textContent.trim();
    
        if (!causa_situacion_texto || causa_situacion_texto.length < 1 || !causaSituacionAccionTextoSinHTML){
            alert('\"Motivo de situación de calle\" no puede estar vacío.');
            e.preventDefault();
        }
    });
    "
); ?>

<script>
    var dni = <?php echo isset($model->dni) ? $model->dni : 0; ?>;

    function datos_persona(primera_vez = false) { 
        var dni_campo = $('#txtDNI').val();
        if (dni != dni_campo || primera_vez) {
            if (dni_campo != '') {
                $('#txt_mensaje').html("Buscando datos de Persona...");
                dni = dni_campo;
                $.post("index.php?r=sds_800_atencion/validar_dni&dni=" + dni, function(data) {
                    data = $.parseJSON(data);
                    if (data.length === 0) {
                        datos_renaper(dni);
                    } else {
                        $("#sds_800_atencion-idpersona").val(data[0]['idpersona']);
                        $("#sds_800_atencion-nombre").val(data[0]['nombre']);
                        $("#sds_800_atencion-apellido").val(data[0]['apellido']);
                        $("#fecha_nacimiento").val(formatearFecha(data[0]['fecha_nacimiento']));                       
                        calcular_edad((data[0]['fecha_nacimiento']));
                        $("#sds_800_atencion-nacionalidad").val(data[0]['nacionalidad']);
                        $("#sds_800_atencion-sexo").val(data[0]['genero']);
                        if (data.length > 1) {
                            $("#sds_800_atencion-localidad").val(data[1]['idlocalidad']);
                        } else {
                            $("#sds_800_atencion-localidad").val("");
                        }
                        $('#txt_mensaje').html(""); 
                        habilitar_controles();
                    }
                });
            }
        }
    }

    function datos_renaper(dni) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
            if (data.status == "error") {
                $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                limpiarDatos();
            } else {
                var nombre = "";
                var apellido = "";
                var localidad = "";
                var foto = "";
                var fecha_nacimiento = null;
                $.each(data, function(ind, elem) {
                    console.log(ind);
                    if (ind == 'records') {
                        console.log(elem[0]);
                        nombre = elem[0].result.nombres;
                        apellido = elem[0].result.apellido;
                        localidad = elem[0].result.ciudad;
                        //foto = elem[0].result.foto;
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                    }
                });  
               
                if (fecha_nacimiento != null) {
                    $("#sds_800_atencion-idpersona").val('0');
                    $("#sds_800_atencion-nombre").val(corregir_palabra(nombre));
                    $("#sds_800_atencion-apellido").val(corregir_palabra(apellido));
                    $("#fecha_nacimiento").val(fecha_nacimiento);
                    fecha_nueva=formatearFecha2(fecha_nacimiento);                    
                    calcular_edad(fecha_nueva);
                    $("#sds_800_atencion-nacionalidad").val('');
                    $("#sds_800_atencion-sexo").val('');
                    //$("#sds_800_atencion-localidad").val(getIdLocalidad(corregir_palabra(localidad)));
                    $("#sds_800_atencion-localidad").val('');
                    /* $("#renaper_foto").attr("src", foto); */
                    $('#txt_mensaje').html("");
                    habilitar_controles();
                }
            }
        });
    }

    function limpiarDatos() {
        habilitar_controles();
        $("#sds_800_atencion-nombre").val('');
        $("#sds_800_atencion-apellido").val('');
        $("#fecha_nacimiento").val('');
        $("#sds_800_atencion-nacionalidad").val('');
        $("#sds_800_atencion-sexo").val('');
        $("#sds_800_atencion-telefono").val("");
        $("#sds_800_atencion-domicilio").val("");
        $("#sds_800_atencion-localidad").val("");
        $("#sds_800_atencion-idpersona").val('0');
        $("#sds_800_atencion-localidad").val("");
        $("#cmb_provincia").val("");
        $("#generoautopercibido").val(""); 
        $("#sds_800_atencion-edad").val(""); 
    }

    function getIdLocalidad(localidad) {
        $.post("index.php?r=sds_800_atencion/get_id_localidad&localidad=" + localidad, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                return "";
            } else {
                $("#sds_800_atencion-localidad").val(data['idlocalidad']);
            }
        });
    }

    function habilitar_controles() {
        
        $("#sds_800_atencion-nombre").prop("disabled", false);
        $("#sds_800_atencion-apellido").prop("disabled", false);
        $("#fecha_nacimiento").prop("disabled", false);
        $("#sds_800_atencion-nacionalidad").prop("disabled", false);
        $("#sds_800_atencion-sexo").prop("disabled", false);
        $("#sds_800_atencion-localidad").prop("disabled", false);
        $("#sds_800_atencion-telefono").prop("disabled", false);
        $("#cmb_provincia").prop("disabled", false); 
        $("#cmb_provinciaoriundo").prop("disabled", false); 
        $("#generoautopercibido").prop("disabled", false); 
        $("#localidad").prop("disabled", false); 
        $("#localidadoriundo").prop("disabled", false); 
        $("#sds_800_atencion-edad").prop("disabled", false);
        
        
    }

    function formatearFecha(fecha) { 
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }
    function formatearFecha2(fecha) {

        var year = fecha.substring(6, 10);
        var month = fecha.substring(3, 5);
        var day = fecha.substring(0, 2);


        var today = year + "-" + month + "-" + day;
        return today;
    }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }

    function cargarLocalidades() {
        if ($("#cmb_provincia").val()) {
            $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {
                $("select#localidad").html(data);
                $("#localidad").val($("#idLocalidadSelected").val()).trigger("change");
                $("#localidad").prop("disabled", false);
            });
        } else {
            $("#localidad").val(null).trigger("change");
            $("#localidad").prop("disabled", true);
        }
    }
    function cargarLocalidades2() {
        if ($("#cmb_provinciaoriundo").val()) {
            $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provinciaoriundo").val(), function(data) {
                $("select#localidadoriundo").html(data);
                $("#localidadoriundo").val($("#idLocalidadSelected").val()).trigger("change");
                $("#localidadoriundo").prop("disabled", false);
            });
        } else {
            $("#localidadoriundo").val(null).trigger("change");
            $("#localidadoriundo").prop("disabled", true);
        }
    }
    function calcular_edad(fecha_nacimiento) {
        var hoy = new Date();
        var cumpleanos = new Date(fecha_nacimiento);
        var edad = hoy.getFullYear() - cumpleanos.getFullYear();
        var m = hoy.getMonth() - cumpleanos.getMonth();

        if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
            edad--;
        }
        $("#edad").val(edad);
        if (edad != 1) {
            $('#edad').text('Edad actual ' + edad + ' años.');
        } else {
            $('#edad').text('Edad actual ' + edad + ' año.');
        }
    }

</script>