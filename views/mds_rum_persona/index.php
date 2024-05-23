<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracion;
use app\models\Mds_rum_filtro;
use app\models\Mds_rum_persona;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_provincia;
use app\models\Sds_com_localidad;
use app\models\Mds_rum_licconducir;

use app\view\mds_rum_persona\_search;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_rum_personaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model_filtro2 app\models\Mds_rum_filtro */

$this->title = 'Curriculums Vitae';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);


/* traemos los generos*/
$los_generos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_GENERO])->all();
/* traemos los estados civiles*/
$los_estados = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_SIT_CONYUGAL])->all();
$disp_horaria = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::RUM_DURACION_TRABAJO])->all();
$losniveles_inst = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::NIVEL_INSTITUCION])->all();

$las_provincias=Sds_com_provincia::find()->orderBy(['descripcion' => SORT_ASC])->all();
$model_filtro = new Mds_rum_filtro();
$filtro_definido=false;
if ( isset($model_filtro2)){$filtro_definido=true;$model_filtro=$model_filtro2;}else{$filtro_definido=false;}

?>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<?php //$form_search = ActiveForm::begin(['action'=>['filtrar_datos4'],'method'=>'get']); ?>
<?php $form_search = ActiveForm::begin(['id' => 'form_filtro','action'=>["filtrar_datos4"],'options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
        <div class="panel-group" id="accordion_detalle">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <?php
                                        if ($filtro_definido)
                                        {echo '<a class="accordion-toggle" data-toggle="collapse" aria-expanded="true" data-parent="#accordion_detalle" href="#detalle">'; }
                                        else
                                        {echo '<a class="accordion-toggle collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion_detalle" href="#detalle">'; }                                        
                                    ?>      
                                    <button type="button" class="btn btn-info">Abrir Filtro de Datos</button>                                                                 
                                        
                                    </a>
                                </h4>
                            </div>
                            <?php
                                if ($filtro_definido)
                                {
                                    echo '<div id="detalle" class="accordion-body collapse in" aria-expanded="true">';
                                }
                                else
                                {
                                    echo '<div id="detalle" class="accordion-body collapse" aria-expanded="false">';
                                }

                            ?>
                            <div id="detalle" class="accordion-body" aria-expanded="true">
                                <div class="panel-body" id="detalle_content">
                                    <div>   
                                        <form> 
                                            INFORMACIÓN PERSONAL
                                            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                                <div class="row">  
                                                    <div class="col-md-2">
                                                        <div class="form-group">  
                                                            <?= $form_search->field($model_filtro, 'dni')->textInput(['placeholder' => "Ingrese DNI",'id' => 'dni'])->hint('Sin espacios ni puntos.') ?>                                                                                                                                                                                    
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">                                                            
                                                            <?= $form_search->field($model_filtro, 'nombre')->textInput(['placeholder' => "Ingrese Nombre",'id' => 'nombre'])->hint('Solo letras y espacios.') ?>                               
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                        <?= $form_search->field($model_filtro, 'apellido')->textInput(['placeholder' => "Ingrese Apellido",'id' => 'apellido'])->hint('Solo letras y espacios.') ?>
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">                                                                
                                                        <?= $form_search->field($model_filtro, 'genero')->widget(Select2::classname(), [
                                                            'data' => ArrayHelper::map(
                                                                Sds_com_configuracion::find()
                                                                ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_GENERO])                                                                    
                                                                ->all(),
                                                                'idconfiguracion',
                                                                'descripcion' 
                                                                ),                                                            
                                                            'options' => ['placeholder' => 'Seleccionar ...', 
                                                            'id' => 'cmb_generos',
                                                            ],

                                                            'pluginOptions' => [
                                                                'allowClear' => true
                                                            ],
                                                        ])
                                                        ->label("Género");
                                                        ?>  
                                                            
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                        <?= $form_search->field($model_filtro, 'estado_civil')->widget(Select2::classname(), [
                                                            'data' => ArrayHelper::map(
                                                                Sds_com_configuracion::find()
                                                                ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_SIT_CONYUGAL])                                                                    
                                                                ->all(),
                                                                'idconfiguracion',
                                                                'descripcion' 
                                                                ),                                                            
                                                            'options' => ['placeholder' => 'Seleccionar ...', 
                                                            'id' => 'cmb_estado_civil',
                                                            ],

                                                            'pluginOptions' => [
                                                                'allowClear' => true
                                                            ],
                                                        ])
                                                        ->label("Estado Civil");
                                                        ?> 
                                                        </div>     
                                                    </div>
                                                </div> 
                                            </div> <br>                                                                                        
                                            <div class="row">  
                                                <div class="col-md-6">DOMICILIO
                                                    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                                        <div class="row">  
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    
                                                                    <?= $form_search->field($model_filtro, 'la_provincia')
                                                                        ->widget(Select2::classname(), 
                                                                        [
                                                                        'data' => ArrayHelper::map(
                                                                            Sds_com_provincia::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                                                            'idprovincia',
                                                                            'descripcion'
                                                                        ),
                                                                        'options' => ['placeholder' => 'Seleccionar Provincia ...', 
                                                                        'id' => 'cmb_provincia',
                                                                        ],

                                                                        'pluginOptions' => [
                                                                            'allowClear' => true
                                                                        ],
                                                                        'pluginEvents' => [
                                                                            "change" => 'function(data) { 
                                                                                $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {             
                                                                                    $("select#cmb_localidad").html(data);
                                                                                });
                                                                            }',
                                                                        ]
                                                                    ])
                                                                    ->label('Provincia');
                                                                    ?>
                                                                </div> 
                                                            </div> 
                                                            <div class="col-md-6">
                                                                <div class="form-group">                                                                    
                                                                    <?php 
                                                                    
                                                                    if ($model_filtro->id_localidad =='')
                                                                    {
                                                                        echo
                                                                        $form_search->field($model_filtro, 'id_localidad')->widget(Select2::classname(), [                                                                            
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
                                                                        echo
                                                                                $form_search->field($model_filtro, 'id_localidad')->widget(Select2::classname(), [
                                                                                    'data' => ArrayHelper::map(
                                                                                        Sds_com_localidad::find()
                                                                                        ->where(['idprovincia' => $model_filtro->la_provincia])
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
                                                                                ->label("Localidad");
                                                                    }
                    
                                                                   
                                                                    ?>
                                                                </div> 
                                                            </div>
                                                        </div> 
                                                    </div>     
                                                </div>
                                                <div class="col-md-6">RANGO DE EDAD
                                                    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                                        <div class="row">  
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <?= $form_search->field($model_filtro, 'edad_desde')->textInput(['placeholder' => "Ingrese edad en años",'id' => 'edad_desde'])->hint('Solo números. Edad en años') ?>                                                                     
                                                                </div>    
                                                            </div> 
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <?= $form_search->field($model_filtro, 'edad_hasta')->textInput(['placeholder' => "Ingrese edad en años",'id' => 'edad_hasta'])->hint('Solo números. Edad en años') ?>                                                                                                                                         
                                                                </div>   
                                                            </div>
                                                        </div> 
                                                    </div>     
                                                </div>
                                            </div> <br>

                                            INFORMACIÓN COMPLEMENTARIA
                                            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                                <div class="row">  
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <?= $form_search->field($model_filtro, 'libreta_san')->widget(Select2::classname(), [
                                                                'data' => ArrayHelper::map(
                                                                    [['id'=>'1','opcion'=>'Tiene'],['id'=>'0','opcion'=>'No tiene']],
                                                                    'id',
                                                                    'opcion' 
                                                                    ),                                                            
                                                                'options' => ['placeholder' => 'Seleccionar ...', 
                                                                'id' => 'cmb_libreta_san',
                                                                ],

                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ])
                                                            ->label("Libreta Sanitaria");
                                                            ?> 
                                                            
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                        <?= $form_search->field($model_filtro, 'libreta_fondo')->widget(Select2::classname(), [
                                                                'data' => ArrayHelper::map(
                                                                    [['id'=>'1','opcion'=>'Tiene'],['id'=>'0','opcion'=>'No tiene']],
                                                                    'id',
                                                                    'opcion' 
                                                                    ),                                                            
                                                                'options' => ['placeholder' => 'Seleccionar ...', 
                                                                'id' => 'cmb_libreta_fondo',
                                                                ],

                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ])
                                                            ->label("Libreta de Fondo de Desempleo");
                                                            ?> 
                                                            
                                                        </div> 
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <?= $form_search->field($model_filtro, 'disp_viaje')->widget(Select2::classname(), [
                                                                'data' => ArrayHelper::map(
                                                                    [['id'=>'1','opcion'=>'Tiene'],['id'=>'0','opcion'=>'No tiene']],
                                                                    'id',
                                                                    'opcion' 
                                                                    ),                                                            
                                                                'options' => ['placeholder' => 'Seleccionar ...', 
                                                                'id' => 'cmb_disp_viaje',
                                                                ],

                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ])
                                                            ->label("Disponibilidad para viajar");
                                                            ?> 
                                                          
                                                        </div>  
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <?= $form_search->field($model_filtro, 'veh_prop')->widget(Select2::classname(), [
                                                                'data' => ArrayHelper::map(
                                                                    [['id'=>'1','opcion'=>'Tiene'],['id'=>'0','opcion'=>'No tiene']],
                                                                    'id',
                                                                    'opcion' 
                                                                    ),                                                            
                                                                'options' => ['placeholder' => 'Seleccionar ...', 
                                                                'id' => 'cmb_veh_prop',
                                                                ],

                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ])
                                                            ->label("Vehículo propio");
                                                            ?>                                                             
                                                        </div>  
                                                    </div>                                                    
                                                </div> 
                                                <div class="row">  
                                                    <div class="col-md-3">
                                                        <div class="form-group"> 
                                                        <?= $form_search->field($model_filtro, 'disp_hor')->widget(Select2::classname(), [
                                                            'data' => ArrayHelper::map(
                                                                Sds_com_configuracion::find()
                                                                ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::RUM_DURACION_TRABAJO])                                                                    
                                                                ->all(),
                                                                'idconfiguracion',
                                                                'descripcion' 
                                                                ),                                                            
                                                            'options' => ['placeholder' => 'Seleccionar ...', 
                                                            'id' => 'cmb_disp_hor',
                                                            ],

                                                            'pluginOptions' => [
                                                                'allowClear' => true
                                                            ],
                                                        ])
                                                        ->label("Disponibilidad Horaria");                                                        
                                                        ?>                                                             
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                        
                                                            <?= $form_search->field($model_filtro, 'tienelicconducir')->widget(Select2::classname(), [
                                                                'data' => ArrayHelper::map(
                                                                    [['id'=>'1','opcion'=>'Tiene'],['id'=>'0','opcion'=>'No tiene']],
                                                                    'id',
                                                                    'opcion' 
                                                                    ),                                                            
                                                                'options' => ['placeholder' => 'Seleccionar ...', 
                                                                'id' => 'cmb_tienelicconducir',
                                                                'onchange' => 'evaluarlicencia();'
                                                                ],

                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ])
                                                            ->label("Lic. de Conducir");
                                                            ?>                                                                
                                                        </div>     
                                                    </div>                            
                                                    <div class="col-md-4" style="display: none;" id="divlicencias" name="divlicencias">                                                                                             
                                                        <?=
                                                        $form_search->field($model_filtro, 'licencias')->widget(Select2::classname(), [
                                                            'data' => ArrayHelper::map(
                                                                Mds_rum_licconducir::find()->all(),
                                                                'id',
                                                                function ($model_filtro) {
                                                                    
                                                                    return $model_filtro->clase. $model_filtro->subclase;
                                                                }                                                                
                                                            ),
                                                            'options' => ['id' => 'licencias', 'placeholder' => '', 'multiple' => true],
                                                            'size' => Select2::MEDIUM,
                                                            'pluginOptions' => [
                                                                'tags' => true,
                                                                'tokenSeparators' => [',', ' '],
                                                                //'maximumInputLength' => 50,
                                                                'allowClear' => true
                                                            ],
                                                        ])->label('Licencias de conducir');
                                                        ?>

                                        </div>
                                                </div>
                                                <div class="row"> 
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <?= $form_search->field($model_filtro, 'habilidades')->textInput(['placeholder' => "Detalle de habilidades", 'id' =>'habilidades'])->hint('Ingrese detalle de habilidades.') ?>                                                             
                                                        </div>  
                                                    </div>
                                            </div> <br>    

                                            FORMACIÓN ACADEMICA
                                            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                                <div class="row">  
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                        
                                                        <?= $form_search->field($model_filtro, 'nivel_institucion')->widget(Select2::classname(), [
                                                            'data' => ArrayHelper::map(
                                                                Sds_com_configuracion::find()
                                                                ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::NIVEL_INSTITUCION])                                                                    
                                                                ->all(),
                                                                'idconfiguracion',
                                                                'descripcion' 
                                                                ),                                                            
                                                            'options' => ['placeholder' => 'Seleccionar ...', 
                                                            'id' => 'cmb_nivel_institucion',
                                                            ],

                                                            'pluginOptions' => [
                                                                'allowClear' => true
                                                            ],
                                                        ])
                                                        ->label("Nivel Institución");                                                        
                                                        ?>       
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">                                                        
                                                        <?= $form_search->field($model_filtro, 'culmino_formacion')->widget(Select2::classname(), [
                                                                'data' => ArrayHelper::map(
                                                                    [['id'=>'1','opcion'=>'Culminó'],['id'=>'0','opcion'=>'No culminó']],
                                                                    'id',
                                                                    'opcion' 
                                                                    ),                                                            
                                                                'options' => ['placeholder' => 'Seleccionar ...', 
                                                                'id' => 'cmb_culmino_formacion',
                                                                ],

                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ])
                                                            ->label("Culminó Formación");
                                                            ?>                                                            
                                                        </div>     
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <?= $form_search->field($model_filtro, 'filtro_formacion')->textInput(['placeholder' => "Ingrese texto a buscar",'id'=>'filtro_formacion'])->hint('Cualquier texto a buscar en formación académica.') ?>                                                            
                                                        </div>  
                                                    </div>                                                                                                 
                                                </div>                                                 
                                            </div> <br>                                                                                      
                                            CAPACITACIÓN
                                            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                                <div class="row">  
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <?= $form_search->field($model_filtro, 'filtro_capacitacion')->textInput(['placeholder' => "Ingrese texto a buscar",'id'=>'filtro_capacitacion'])->hint('Cualquier texto a buscar en capacitaciones.') ?>                                                                                                                        
                                                        </div>  
                                                    </div>                                                                                                 
                                                </div>                                                 
                                            </div> <br>    
                                            OFERTA LABORAL
                                            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                                <div class="row">  
                                                    <div class="col-md-6">
                                                        <div class="form-group">                                                        
                                                            <?= $form_search->field($model_filtro, 'filtro_laboral')->textInput(['placeholder' => "Ingrese texto a buscar",'id'=>'filtro_laboral'])->hint('Cualquier texto a buscar en ofertas laborales.') ?>                                                            
                                                        </div>  
                                                    </div>                                                                                                 
                                                </div>                                                 
                                            </div> <br>                   
                                        </form>
                                        <br>
                                        
                                        <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
                                        <?= Html::submitButton("Resetear", ["class" => "btn btn-success","id" => "clear_form"]) ?>
                                    </div>

                                </div>
                            </div>
                        </div>
        </div>

        <?php $form_search = ActiveForm::end(); ?>



        <?php $form = ActiveForm::begin(); ?>
                                    
            <div class="panel-body">
<div class="mds-rum-persona-index">
    <div id="ajaxCrudDatatable">
    <?php Pjax::begin(); ?>
        <?=GridView::widget([
            'id'=>'crud-datatable', 
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    /*Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Create new Mds Rum Personas','class'=>'btn btn-default']).*/
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'default', 
                'heading' => '',
                'before'=>'',
                'after'=> '<div class="clearfix"></div>',
            ]
        ])?>
        <?php Pjax::end(); ?>
    </div>
</div>


</div>
</section>
</div>
</div>
<div id="simple-div"></div>
<?php $form = ActiveForm::end(); ?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'size' => Modal::SIZE_LARGE,
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
<script type="text/javascript">
function evaluarlicencia() {
        let tienelicencia=$("#cmb_tienelicconducir").val();        
        if (tienelicencia==1){
            $("#divlicencias").show();
        }
        else
        {
            $("#divlicencias").hide();
        }
          
    }
</script>
<?php
$script = <<<  JS

$("#form_provincia").change(function() {  
  $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#form_provincia").val(), function(data) {                     
            $("#form_localidad").html(data);
    });
});
//
$("#clear_form").on("click", 
        function() 
        {     
            $("#dni").val("");
            $("#nombre").val("");
            $("#apellido").val("");            
            $('#cmb_generos').val('').trigger('change.select2');
            $('#cmb_estado_civil').val('').trigger('change.select2');
            $('#cmb_provincia').val('').trigger('change.select2');
            $('#cmb_localidad').val('').trigger('change.select2');
            $("#edad_desde").val("");
            $("#edad_hasta").val("");
            $('#cmb_libreta_san').val('').trigger('change.select2');
            $('#cmb_libreta_fondo').val('').trigger('change.select2');
            $('#cmb_disp_viaje').val('').trigger('change.select2');
            $('#cmb_veh_prop').val('').trigger('change.select2');
            $('#cmb_disp_hor').val('').trigger('change.select2');
            $('#cmb_tienelicconducir').val('').trigger('change.select2');
            $("#habilidades").val("");

            $('#cmb_nivel_institucion').val('').trigger('change.select2');
            $('#cmb_culmino_formacion').val('').trigger('change.select2');
            $("#filtro_formacion").val("");
            $("#filtro_capacitacion").val("");
            $("#filtro_laboral").val("");


            return false;
            
        }
);

JS;

$this->registerJs($script);

?>
<!--
    $cadena_enviar='"index.php?r=mds_rum_empleador/enviar_datos&id='.$model->id.'"';
    $.post($cadena_enviar, 
                                function(data) 
                                {                                                                          
                                    krajeeDialog.alert("Se ha enviado el email con exito.");
                                });

                                -->