<?php
use yii\helpers\Url;
use app\models\Sds_com_persona;
use app\models\Sds_ris_persona;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\Sds_com_localidad;
use app\models\Sds_com_provincia;
use yii\helpers\Html;
return [
    /*[
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],*/
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'persona',
        'label' => 'Nombres y Apellidos',
        'value' => function ($model) {                     
            $un_risneu_=Sds_ris_persona::find()->where('idrisneu="'.$model->id_risneu.'" ')->one();
            $una_com_persona=Sds_com_persona::find()->where(' idpersona="'.$un_risneu_->idpersona.'" ')->one();            
            $cad=$una_com_persona->nombre.' '.$una_com_persona->apellido;
            return $cad;
        }, 

        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_persona::find()->where("idpersona in (select sds_ris_persona.idpersona from sds_ris_persona,mds_atpcen_encuesta where sds_ris_persona.idrisneu= mds_atpcen_encuesta.id_risneu  )")->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(), 
            'idpersona', 
            function ($model) {
                return $model->nombre . " " . $model->apellido;
            }
        ),
        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Persona...'],
        'format' => 'raw',
        'width' => '25%',    
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'label' => 'DNI',
        'value' => function ($model) {                     
            $un_risneu_=Sds_ris_persona::find()->where('idrisneu="'.$model->id_risneu.'" ')->one();
            $una_com_persona=Sds_com_persona::find()->where(' idpersona="'.$un_risneu_->idpersona.'" ')->one();            
            $cad=$una_com_persona->documento;
            return $cad;
        }, 

        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_persona::find()->where("idpersona in (select sds_ris_persona.idpersona from sds_ris_persona,mds_atpcen_encuesta where sds_ris_persona.idrisneu= mds_atpcen_encuesta.id_risneu  )")->orderBy(['documento' => SORT_ASC])->all(), 
            'idpersona', 
            function ($model) {
                return $model->documento;
            }
        ),
        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'DNI...'],
        'format' => 'raw',
        'width' => '8%',    
    ],
    /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_atpcen',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_persona_carga',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_hora_alta',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_entrevistador',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_hora_entrevista',
    ],*/
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_localidad_entrevista',
    // ],
   /* <?php
                    if ($model->id_localidad_entrevista==null)
                    {$model->loc_prov_e='no registra'; }
                    else
                    {
                        $una_localidad_e = Sds_com_localidad::find()->where(['idlocalidad' => $model->id_localidad_entrevista])->one();
                        $una_provincia_e = Sds_com_provincia::find()->where(['idprovincia' => $una_localidad_e->idprovincia])->one(); 
                        $model->loc_prov_e=$una_localidad_e->descripcion." (".$una_provincia_e->descripcion.")";

                    }
                       
            ?>*/
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'localidad_entrev',
        'label' => 'Localidad de la entrevista',
        'width' => '12%',    
        'value' => function ($model) 
        {                     
            if ($model->id_localidad_entrevista==null)
            {
                $cad="no registra";
            }
            else
            {
                $una_localidad_e = Sds_com_localidad::find()->where(['idlocalidad' => $model->id_localidad_entrevista])->one();
                $una_provincia_e = Sds_com_provincia::find()->where(['idprovincia' => $una_localidad_e->idprovincia])->one(); 
                $cad=$una_localidad_e->descripcion." (".$una_provincia_e->descripcion.")";
                
            }
            return $cad;
            
        }, 
    ],
        /*$un_risneu_=Sds_ris_persona::find()->where('idrisneu="'.$model->id_risneu.'" ')->one();
                                                $una_com_persona=Sds_com_persona::find()->where(' idpersona="'.$un_risneu_->idpersona.'" ')->one();            
                                                $model->nombre=$una_com_persona->nombre;
                                                $model->apellido=$una_com_persona->apellido;
                                                $model->fecha_nacimiento=$una_com_persona->fecha_nacimiento;
                                                $model->nacionalidad=$una_com_persona->nacionalidad;
                                                $model->sexo=$una_com_persona->genero;*/
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'fecha_nacimiento',
            'width' => '12%',
            'label' => 'Fecha Nacimiento',
            'value' => function ($model) {

                $un_risneu_=Sds_ris_persona::find()->where('idrisneu="'.$model->id_risneu.'" ')->one();
                $una_com_persona=Sds_com_persona::find()->where(' idpersona="'.$un_risneu_->idpersona.'" ')->one();            
                

                $fc = date_create($una_com_persona->fecha_nacimiento);
                $fc = date_format($fc, 'd/m/Y');
                return $fc;
            },
           
         
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'dni_beneficiario',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_risneu',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tip_control',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'telefono_contacto1',
        'width' => '12%',   
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'telefono_contacto2',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'email',
        'width' => '12%',   
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tipo_documento_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'documento_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'cuil_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'apellido_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_nac_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'parentezco_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'frente_dni_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'dorso_dni_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_fuente_ingreso',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'sexo_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'nombre_tutor',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tiene_obra_social',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'obra_social',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tiene_biopsia',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_diagnostico',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'estudio_biopsia',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'concurre_a_control',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'frecuencia',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'integrante_celiaco',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'establecimiento_salud',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_establ_salud',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'organismo_asiste',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'cantidad_asistencia',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'periocidad_asistencia',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'capacitacion_solicitada',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'observacion',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template'=> $stringButtonsIndex,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons'=>[
           'imprimirRisneu'=>function($url,$model){
               $url =  Url::to(['/sds_ris_risneu/imprimir', 'id' => $model->id_risneu]);
               return Html::a('<span class= "fas fa-users"></span>', $url, [
                   'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                   'data-toggle' => 'tooltip',
                   'title' => 'Imprimir RISNeu'
               ]);

           }
        ]
    ],

];   