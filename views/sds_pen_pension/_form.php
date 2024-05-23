<?php

use app\controllers\Sds_pen_pensionController;
use app\models\Mds_org_contacto;
use app\models\Sds_com_barrio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_persona;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_provincia;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->registerJs(
    "
        $(document).ready(function()
                            {
                                if ($('#inputDniPersona').val()!='')
                                    {
                                        datos_persona();
                                    }
                                
                            }
                        ); 
        $('#btn_dni').on('click',function(){datos_persona()});
        $('#inputDniPersona').keyup(function(e){ValidaringresoDni()});
            "
);
$form_principal = 'interv_form';

function botonAltaConfiguracion($model, $tipo, $titulo, $form_principal)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to([
            '//sds_com_configuracion/create_ext',
            'tipo' => $tipo,
        ]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . $tipo,
        'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        //"disabled" => !$model->isNewRecord,
        'onclick' =>
            '
                $("#abm_configuracion").show();
                $("#abm_configuracion_content").load($(this).attr("value"));
                $("#abm_configuracion_title").html("' .
            $titulo .
            '");
                $("#btnGuardar").hide();$("#btnCerrar").hide();
                $("#' .
            $form_principal .
            '").hide();',
    ]);
}
?>

<!-- DIV PRINCIPAL ##################################################################################################################################################### -->
<div class="sds-pen-pension-form" id="interv_form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- BLOQUE 1 BUSQUEDA ##################################################################################################################################################### -->
    <div class="row">
        <!-- Linea de busqueda -->
        <div class="col-md-3">
            <!-- si es edicion y viene con un id persona lo setea en el campo de dni -->
            <?php
            if ($model->idpersona) {
                $persona = Sds_com_persona::findOne($model->idpersona);
                $documento = $persona->documento;
                $model->documento = $documento;
            }

            echo $form
                ->field($model, 'documento')
                ->textInput(['id' => 'inputDniPersona'])
                ->label('Buscar persona por Dni');
            ?>
        </div>
        <div class="col-md-1" style="padding-top:25px;">
            <?php echo Html::a(
                '<i class="glyphicon glyphicon-search"></i>',
                null,
                [
                    'name' => 'btn_dni',
                    'id' => 'btn_dni',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-primary',
                    'title' => Yii::t('app', 'Consultar DNI Llamante'),
                ]
            ); ?>
        </div>

        <div class="col-md-7" style="padding-top:30px;" id="txt_mensaje">

        </div>

    </div>
    <!-- BLOQUE 2 DATOS DE PERSONA ##################################################################################################################################################### -->
    Datos de Persona
    <div style='border: 1px solid #ccc; border-radius: 4px;'>
        <div class="row" style='padding:10px; '>
            <!-- Datos de persona -->
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <!-- Apellido -->
                        <?= $form
                            ->field($model, 'apellido')
                            ->textInput(['id' => 'input_apellido'])
                            ->label('Apellido') ?>
                    </div>
                    <div class="col-md-7">
                        <!-- Nombres -->
                        <?= $form
                            ->field($model, 'nombre')
                            ->textInput(['id' => 'input_nombre'])
                            ->label('Nombres') ?>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-2">
                        <!-- id persona -->
                        <?= $form
                            ->field($model, 'idpersona')
                            ->textInput([
                                'id' => 'VarHiddenIdPersona',
                                'readonly' => true,
                            ])
                            ->label('Id Persona') ?>
                    </div>
                    <div class="col-md-4">
                        <!-- tipo documento -->
                        <?php
                        $idtipo = Sds_com_configuracion_tipo::TIPO_TIPO_DOC;
                        $consulta = "Select * From sds_com_configuracion Where idconfiguraciontipo = $idtipo and activo = 1";
                        $datos = ArrayHelper::map(
                            Sds_com_configuracion::findBySql($consulta)->all(),
                            'idconfiguracion',
                            'descripcion'
                        );
                        echo $form
                            ->field($model, 'documento_tipo')
                            ->dropdownList($datos, [
                                'id' => 'input_combo_tipo_documento',
                            ])
                            ->label('Tipo Documento');
                        ?>

                    </div>
                    <div class="col-md-3">
                        <!-- documento -->
                        <?= $form
                            ->field($model, 'documento')
                            ->textInput(['id' => 'input_numero_documento'])
                            ->label('Numero Documento') ?>
                    </div>
                    <div class="col-md-3">
                        <!-- legajo_rh este campo esta aca solo por que se incluye en pensiones, sino, no es parte de los datos de la persona -->
                        <?php echo $form
                            ->field($model, 'legajo_rh')
                            ->textInput(['id' => 'input_legajo_rh']); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <!-- fecha de nacimiento -->
                        <?= $form
                            ->field($model, 'fecha_nacimiento')
                            ->widget(DatePicker::ClassName(), [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}',

                                'options' => [
                                    'class' => 'form-control input-md',
                                    'id' => 'input_fecha_nacimiento',
                                    'placeholder' => 'DD / MM / YYYY',
                                ],
                                'pluginOptions' => [
                                    'value' => null,
                                    'format' => 'dd/mm/yyyy',
                                    'endDate' => date('d/m/Y'),
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ],
                            ]) ?>
                    </div>
                    <div class="col-md-4">
                        <!-- Nacionalidad -->
                        <?php
                        $idtipo = Sds_com_configuracion_tipo::TIPO_NACIONALIDAD;
                        $consulta = "Select * From sds_com_configuracion Where idconfiguraciontipo = $idtipo and activo = 1";
                        $datos = ArrayHelper::map(
                            Sds_com_configuracion::findBySql($consulta)->all(),
                            'idconfiguracion',
                            'descripcion'
                        );
                        echo $form
                            ->field($model, 'nacionalidad')
                            ->dropdownList($datos, [
                                'id' => 'input_combo_nacionalidad',
                            ]);
                        ?>
                    </div>
                    <div class="col-md-4">
                        <!-- Genero -->
                        <?php
                        $idtipo = Sds_com_configuracion_tipo::TIPO_GENERO;
                        $consulta = "Select * From sds_com_configuracion Where idconfiguraciontipo = $idtipo and activo = 1";
                        $datos = ArrayHelper::map(
                            Sds_com_configuracion::findBySql($consulta)->all(),
                            'idconfiguracion',
                            'descripcion'
                        );
                        echo $form
                            ->field($model, 'genero')
                            ->dropdownList($datos, [
                                'id' => 'input_combo_genero',
                            ]);
                        ?>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3">                
                <div class="col-md-3" style="text-align: center;">
                    <img id="renaper_foto" src="" alt="" height="200px" />
                </div>
            </div> -->
        </div>
        <div style='padding:10px; '>
            <!-- Datos de direccion de la persona (no estan en la tabla sds_com_personas, son exclusivos de penciones)-->
            <div class="row">
                <div class="col-md-5">
                    <!-- idlocalidad -->
                    <?php
                    $provincia = Sds_com_provincia::findBySql(
                        "Select * from sds_com_provincia Where descripcion = 'Neuquén'"
                    )->one();
                    echo $form
                        ->field($model, 'idlocalidad')
                        ->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Sds_com_localidad::find()
                                    ->where([
                                        'idprovincia' =>
                                            $provincia->idprovincia,
                                        'activo' => 1,
                                    ])
                                    ->orderBy(['descripcion' => SORT_ASC])
                                    ->all(),
                                'idlocalidad',
                                'descripcion'
                            ),
                            'options' => [
                                'placeholder' => 'Seleccionar ...',
                                'id' => 'cmb_localidad',
                            ],

                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])
                        ->label('Localidad');
                    ?>
                </div>
                <div class="col-md-7">
                    <!-- idbarrio -->
                    <div id="div_cmd_barrio">
                        <?php echo $form
                            ->field($model, 'idbarrio')
                            ->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(
                                    Sds_com_barrio::find()
                                        ->where([
                                            'idlocalidad' =>
                                                $model->idlocalidad,
                                            'activo' => 1,
                                        ])
                                        ->orderBy(['nombre' => SORT_ASC])
                                        ->all(),
                                    'idbarrio',
                                    'nombre'
                                ),
                                'options' => [
                                    'placeholder' => 'Seleccionar ...',
                                    'id' => 'cmb_barrio',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])
                            ->label('Barrio'); ?>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- calle -->
                    <?= $form
                        ->field($model, 'calle')
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <!-- numero -->
                    <?= $form
                        ->field($model, 'numero')
                        ->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <!-- manzana -->
                    <?= $form
                        ->field($model, 'manzana')
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <!-- casa -->
                    <?= $form
                        ->field($model, 'casa')
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <!-- lote -->
                    <?= $form
                        ->field($model, 'lote')
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <!-- departamento -->
                    <?= $form
                        ->field($model, 'departamento')
                        ->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>


    <!-- BLOQUE 3 DATOS DE PENSION ##################################################################################################################################################### -->
    <br>Datos de Pension
    <div style='border: 1px solid #ccc; border-radius: 4px;'>
        <div style='padding:10px; '>
            <div class="row">
                <div class="col-md-3">
                    <!-- programa -->
                    <div class="input-group">
                        <?= $form
                            ->field($model, 'programa')
                            ->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguraciones(
                                        Sds_com_configuracion_tipo::TIPO_PENSION_PROGRAMA,
                                        true
                                    ),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                    //"disabled" => !$model->isNewRecord,
                                    'id' =>
                                        'config_' .
                                        Sds_com_configuracion_tipo::TIPO_PENSION_PROGRAMA,
                                ]
                            ) ?>
                        <span class="input-group-btn">
                            <?php
                            $tipo =
                                Sds_com_configuracion_tipo::TIPO_PENSION_PROGRAMA;
                            $titulo = 'Nuevo Programa';
                            echo botonAltaConfiguracion(
                                $model,
                                $tipo,
                                $titulo,
                                $form_principal
                            );
                            ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <!-- legajo -->
                    <?= $form->field($model, 'legajo')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <!-- estado -->
                    <div class="input-group">
                        <?= $form
                            ->field($model, 'estado')
                            ->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguraciones(
                                        Sds_com_configuracion_tipo::TIPO_PENSION_ESTADO,
                                        true
                                    ),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    'prompt' => 'Estado...',
                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                    //"disabled" => !$model->isNewRecord,
                                    'id' =>
                                        'config_' .
                                        Sds_com_configuracion_tipo::TIPO_PENSION_ESTADO,
                                ]
                            ) ?>
                        <span class="input-group-btn">
                            <?php
                            $tipo =
                                Sds_com_configuracion_tipo::TIPO_PENSION_ESTADO;
                            $titulo = 'Nuevo Estado';
                            echo botonAltaConfiguracion(
                                $model,
                                $tipo,
                                $titulo,
                                $form_principal
                            );
                            ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <!-- fecha_carga -->

                    <?php
                    if ($model->fecha_carga) {
                        $model->fecha_carga = date(
                            'd/m/Y',
                            strtotime(
                                str_replace('/', '-', $model->fecha_carga)
                            )
                        );
                    } else {
                        $model->fecha_carga = date('d/m/Y');
                    }
                    echo $form
                        ->field($model, 'fecha_carga')
                        ->widget(DatePicker::ClassName(), [
                            'name' => 'check_issue_date',
                            'language' => 'es',
                            'readonly' => false,
                            'layout' => '{picker}{input}',

                            'options' => [
                                'class' => 'form-control input-md',
                                'id' => 'input_fecha_carga',
                                'placeholder' => 'DD / MM / YYYY',
                            ],
                            'pluginOptions' => [
                                'format' => 'dd/mm/yyyy',
                                'endDate' => date('d/m/Y'),
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ],
                        ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <!-- expediente -->
                    <?= $form
                        ->field($model, 'expediente')
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-2">
                    <!-- resolucion -->
                    <?= $form
                        ->field($model, 'resolucion')
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-2">
                    <!-- tramite_nacion -->
                    <?= $form
                        ->field($model, 'tramite_nacion')
                        ->dropDownList([0 => 'No', 1 => 'Si']) ?>
                </div>
                <div class="col-md-6">
                    <!-- lugar_pago -->
                    <div class="input-group">
                        <?= $form
                            ->field($model, 'lugar_pago')
                            ->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguraciones(
                                        Sds_com_configuracion_tipo::TIPO_PENSION_LUGAR_DE_PAGO,
                                        true
                                    ),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    'prompt' => 'Lugar...',
                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                    //"disabled" => !$model->isNewRecord,
                                    'id' =>
                                        'config_' .
                                        Sds_com_configuracion_tipo::TIPO_PENSION_LUGAR_DE_PAGO,
                                ]
                            ) ?>
                        <span class="input-group-btn">
                            <?php
                            $tipo =
                                Sds_com_configuracion_tipo::TIPO_PENSION_LUGAR_DE_PAGO;
                            $titulo = 'Nuevo Lugar De Pago';
                            echo botonAltaConfiguracion(
                                $model,
                                $tipo,
                                $titulo,
                                $form_principal
                            );
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- notas -->
                    <?= $form
                        ->field($model, 'notas')
                        ->textarea(['rows' => 3]) ?>
                </div>
            </div>
        </div>
    </div>

    <br>Datos De Otorgamiento
    <div style='border: 1px solid #ccc; border-radius: 4px;'>
        <div style='padding:10px; '>
            <div class="row">
                <div class="col-md-4">
                    <!-- fecha_otorgado -->
                    <?php
                    if ($model->fecha_otorgado) {
                        $model->fecha_otorgado = date(
                            'd/m/Y',
                            strtotime(
                                str_replace('/', '-', $model->fecha_otorgado)
                            )
                        );
                    }
                    echo $form
                        ->field($model, 'fecha_otorgado')
                        ->widget(DatePicker::ClassName(), [
                            'name' => 'check_issue_date',
                            'language' => 'es',
                            'readonly' => false,
                            'layout' => '{picker}{input}',

                            'options' => [
                                'class' => 'form-control input-md',
                                'id' => 'input_fecha_otorgado',
                                'placeholder' => 'DD / MM / YYYY',
                            ],
                            'pluginOptions' => [
                                'format' => 'dd/mm/yyyy',
                                'endDate' => date('d/m/Y'),
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ],
                        ]);
                    ?>

                </div>
                <div class="col-md-4">
                    <!-- tipo_otorgado -->
                    <div class="input-group">
                        <?= $form
                            ->field($model, 'tipo_otorgado')
                            ->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguraciones(
                                        Sds_com_configuracion_tipo::TIPO_PENSION_TIPO_OTORGADO,
                                        true
                                    ),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    'prompt' => 'Tipo...',
                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                    //"disabled" => !$model->isNewRecord,
                                    'id' =>
                                        'config_' .
                                        Sds_com_configuracion_tipo::TIPO_PENSION_TIPO_OTORGADO,
                                ]
                            ) ?>
                        <span class="input-group-btn">
                            <?php
                            $tipo =
                                Sds_com_configuracion_tipo::TIPO_PENSION_TIPO_OTORGADO;
                            $titulo = 'Nuevo Tipo Otorgado';
                            echo botonAltaConfiguracion(
                                $model,
                                $tipo,
                                $titulo,
                                $form_principal
                            );
                            ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <!-- anio_otorgado -->
                    <?= $form
                        ->field($model, 'anio_otorgado')
                        ->textInput()
                        ->label('Año Otorgado') ?>
                </div>
                <div class="col-md-2">
                    <!-- numero_otorgado -->
                    <?= $form->field($model, 'numero_otorgado')->textInput() ?>
                </div>
            </div>
        </div>
    </div>

    <br>Datos De Baja
    <div style='border: 1px solid #ccc; border-radius: 4px;'>
        <div style='padding:10px; '>
            <div class="row">
                <div class="col-md-4">
                    <!-- numero_baja -->
                    <?= $form->field($model, 'numero_baja')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <!-- fecha_baja -->
                    <?php
                    if ($model->fecha_baja) {
                        $model->fecha_baja = date(
                            'd/m/Y',
                            strtotime(str_replace('/', '-', $model->fecha_baja))
                        );
                    }
                    echo $form
                        ->field($model, 'fecha_baja')
                        ->widget(DatePicker::ClassName(), [
                            'name' => 'check_issue_date',
                            'language' => 'es',
                            'readonly' => false,
                            'layout' => '{picker}{input}',

                            'options' => [
                                'class' => 'form-control input-md',
                                'id' => 'input_fecha_baja',
                                'placeholder' => 'DD / MM / YYYY',
                            ],
                            'pluginOptions' => [
                                'format' => 'dd/mm/yyyy',
                                'endDate' => date('d/m/Y'),
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ],
                        ]);
                    ?>
                </div>
                <div class="col-md-2">
                    <!-- anio_baja -->
                    <?= $form
                        ->field($model, 'anio_baja')
                        ->textInput()
                        ->label('Año de Baja') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- tipo_baja -->
                    <div class="input-group">
                        <?= $form
                            ->field($model, 'tipo_baja')
                            ->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguraciones(
                                        Sds_com_configuracion_tipo::TIPO_PENSION_TIPO_BAJA,
                                        true
                                    ),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    'prompt' => 'Tipo...',
                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                    //"disabled" => !$model->isNewRecord,
                                    'id' =>
                                        'config_' .
                                        Sds_com_configuracion_tipo::TIPO_PENSION_TIPO_BAJA,
                                ]
                            ) ?>
                        <span class="input-group-btn">
                            <?php
                            $tipo =
                                Sds_com_configuracion_tipo::TIPO_PENSION_TIPO_BAJA;
                            $titulo = 'Nuevo Tipo de Baja';
                            echo botonAltaConfiguracion(
                                $model,
                                $tipo,
                                $titulo,
                                $form_principal
                            );
                            ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- causa_baja -->
                    <div class="input-group">
                        <?= $form
                            ->field($model, 'causa_baja')
                            ->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguraciones(
                                        Sds_com_configuracion_tipo::TIPO_PENSION_CAUSA_BAJA,
                                        true
                                    ),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    'prompt' => 'Causa...',
                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el combo
                                    //"disabled" => !$model->isNewRecord,
                                    'id' =>
                                        'config_' .
                                        Sds_com_configuracion_tipo::TIPO_PENSION_CAUSA_BAJA,
                                ]
                            ) ?>
                        <span class="input-group-btn">
                            <?php
                            $tipo =
                                Sds_com_configuracion_tipo::TIPO_PENSION_CAUSA_BAJA;
                            $titulo = 'Nueva Causa de Baja';
                            echo botonAltaConfiguracion(
                                $model,
                                $tipo,
                                $titulo,
                                $form_principal
                            );
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- observaciones_baja -->
                    <?= $form
                        ->field($model, 'observaciones_baja')
                        ->textarea(['rows' => 3]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <!-- transferida -->
                    <?= $form
                        ->field($model, 'transferida')
                        ->dropDownList(
                            [0 => 'No', 1 => 'Si'],
                            ['id' => 'cmb_transferido']
                        ) ?>
                </div>
                <div id="datos_transferido" style="display:none;">

                    <div class="col-md-3">
                        <!-- Dni/busqueda persona_transferida -->
                        <div class="input-group">
                            <?php
                            if ($model->persona_transferida) {
                                $persona = Sds_com_persona::findOne(
                                    $model->persona_transferida
                                );
                                $documentoaux = $persona->documento;
                                $model->documento_persona_transferida = $documentoaux;
                            }

                            echo $form
                                ->field($model, 'documento_persona_transferida')
                                ->textInput([
                                    'id' => 'inputDniPersonaTranferida',
                                ])
                                ->label('Buscar persona por Dni');
                            ?>
                            <span class="input-group-btn">
                                <?php echo Html::button(
                                    '<i class="glyphicon glyphicon-search"></i>',
                                    [
                                        'value' => Url::to([
                                            '//sds_com_configuracion/create_ext',
                                            'tipo' => $tipo,
                                        ]),
                                        'class' => 'btn btn-success btn-flat',
                                        'style' => 'margin-top:27px',
                                        'onclick' =>
                                            'buscar_persona_transferida();',
                                    ]
                                ); ?>
                            </span>
                        </div>

                    </div>
                    <div class="col-md-2">
                        <!-- id persona transferida-->
                        <?= $form
                            ->field($model, 'persona_transferida')
                            ->textInput(['id' => 'inputIdPersonaTransferida'])
                            ->label('Id Persona') ?>
                    </div>
                    <div class="col-md-5">
                        <!-- nombre persona transferida-->
                        <?php
                        if ($model->persona_transferida) {
                            $persona = Sds_com_persona::findOne(
                                $model->persona_transferida
                            );
                            $model->descripcion_persona_transferida = "$persona->apellido, $persona->nombre";
                        }
                        echo $form
                            ->field($model, 'descripcion_persona_transferida')
                            ->textInput([
                                'id' => 'input_descripcion_persona_transferida',
                            ])
                            ->label('Persona Transferida');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                'class' => $model->isNewRecord
                    ? 'btn btn-success'
                    : 'btn btn-primary',
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<!-- DIV NUEVA CONFIGURACION ##################################################################################################################################################### -->
<div class="row" id="abm_configuracion" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 id="abm_configuracion_title" class="panel-title">
                </h3>
            </header>
            <div class="panel-body" id="abm_configuracion_content">
            </div>
        </section>
    </div>
</div>




<script>
    BuscarPersonaTransferida();


    $("#cmb_localidad").change(function() {
        //alert($("#cmb_localidad option:selected").text());
        //alert($("#cmb_localidad").val());
        MostrarBarrios($("#cmb_localidad").val());

    });

    $("#cmb_transferido").change(function() {

        BuscarPersonaTransferida();

    });

    function BuscarPersonaTransferida() {
        aux = $("#cmb_transferido").val();
        if (aux == 1) {
            $("#datos_transferido").show();
        } else {
            $("#inputIdPersonaTransferida").val(null);
            $("#input_descripcion_persona_transferida").val(null);
            $("#inputDniPersonaTranferida").val(null);

            $("#datos_transferido").hide();
        }
    }


    function MostrarBarrios(idlocalidad) {

        //var aux = $("#input_idrecepcion").val();
        aux = "index.php?r=sds_pen_pension/cmb_barrio&idlocalidad=" + idlocalidad;
        //alert(aux);
        $.post(aux, function(data) {
            //alert(data);
            $("select#cmb_barrio").html(data);
            $("select#cmb_barrio").val('');

        });
    }

    function buscar_persona_transferida() {
        var dni_persona = $("#inputDniPersonaTranferida").val();
        if (dni_persona == "") {
            alert("escriba un dni");
            return;
        }


        $('#input_descripcion_persona_transferida').html("Buscando datos de Persona con dni " + dni_persona);
        $.post("index.php?r=sds_pen_pension/validar_dni&dni_persona=" + dni_persona, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                $('#input_descripcion_persona_transferida').val("No existe " + dni_persona + " en la base de datos, solicitar alta");
            } else {
                console.log(data);

                $("#inputIdPersonaTransferida").val(data[0]['idpersona']);
                $("#inputIdPersonaTransferida").prop("readonly", true);
                aux = data[0]['apellido'] + ', ' + data[0]['nombre'];
                $('#input_descripcion_persona_transferida').val(aux);
            }
            $("#input_descripcion_persona_transferida").prop("readonly", true);

        });


    }

    function datos_persona() {
        $("#VarHiddenIdPersona").val('0');
        var dni_persona = $("#inputDniPersona").val();
        if (dni_persona == "") {
            alert("escriba un dni");
            return;
        }

        $('#input_numero_documento').val(dni_persona);
        $("#input_numero_documento").prop("readonly", true);

        $('#txt_mensaje').html("Buscando datos de Persona con dni " + dni_persona);
        $.post("index.php?r=sds_pen_pension/validar_dni&dni_persona=" + dni_persona, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                $("#div_grilla").html("Sin Movimientos...");
                BloquearControlesPersona(false);
                LimpiarCamposAltaPersona(dni_persona);
                buscar_en_renaper(dni_persona);
            } else {
                console.log(data);
                $("#VarHiddenIdPersona").val(data[0]['idpersona']);
                $('#input_combo_nacionalidad').val(data[0]['nacionalidad']);
                $('#input_combo_genero').val(data[0]['genero']);
                $('#input_apellido').val(data[0]['apellido']);
                $('#input_nombre').val(data[0]['nombre']);
                $('#input_combo_tipo_documento').val(data[0]['documento_tipo']);
                $('#input_fecha_nacimiento').val(FormatearFecha(data[0]['fecha_nacimiento']));


                BloquearControlesPersona(true);
                buscar_foto_en_renaper(dni_persona);
                aux = "Pensionado: " + data[0]['apellido'] + ', ' + data[0]['nombre'];
                $('#txt_mensaje').html(aux);

                aux = $('#input_legajo_rh').val();
                //alert (aux);
                if (!(aux > 0)) {
                    $('#input_legajo_rh').val(data[1]['legajo']);
                }
            }

        });


    }

    function BloquearControlesPersona(option) {
        if (option === true) {
            $('#input_combo_nacionalidad').prop("readonly", true);
            $('#input_combo_genero').prop("readonly", true);
            $('#input_combo_tipo_documento').prop("readonly", true);
            $('#input_numero_documento').prop("readonly", true);
            $("#input_fecha_nacimiento").prop("readonly", true);
            $("#input_apellido").prop("readonly", true);
            $("#input_nombre").prop("readonly", true);
            if ($('#input_legajo_rh').val() > 0) {
                $('#input_legajo_rh').prop("readonly", true);
            } else {
                $('#input_legajo_rh').prop("readonly", false);
            }
        } else {
            $('#input_combo_nacionalidad').prop("readonly", false);
            $('#input_combo_genero').prop("readonly", false);
            $('#input_combo_tipo_documento').prop("readonly", false);
            $('#input_numero_documento').prop("readonly", false);
            $("#input_fecha_nacimiento").prop("readonly", false);
            $("#input_apellido").prop("readonly", false);
            $("#input_nombre").prop("readonly", false);
            $('#input_legajo_rh').prop("readonly", false);
        }
    }

    function ValidaringresoDni() {
        var aux = event.which;
        if (aux == 13) //pregunto si fue el enter
        {
            datos_persona();
        }
        /* else
            {
                aux = event.key;
                if (!/^([0-9])*$/.test(aux))
                    {
                        dni_campo = $('#inputDniPersona').val();
                        //alert("Solo Numeros");
                        dni_campo = dni_campo.substring(0,dni_campo.length-1);
                        $('#inputDniPersona').val(dni_campo);
                    }
            } */

    }

    function buscar_foto_en_renaper(dni_persona) {
        //gif
        /* gif = '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uvXr2LNr3869u/fv4MOL/x9Pvrz58+jTq1/Pvr379/Djy5+vvMACAvRrGlhgoOCAAvmZRACABC1gIEEGMMBAgCQlYIABBxR4oEAFKLgAgyQ9aIACAxl4oUALKDgAhiMV8GCEIE44gIUkknTAgwR6CAACCjJAYIshEaBhAgDIeICC/Q2EAAEE8IjjRAgMMACHA71owIgFVSiAQAkoQCQBCBw5kQJKKpnljAbgp5AAVxIwpZYTEdDlf0YuVKYCbaIpUZJrNjQklnJixOUAZzL0ZZ4YJSAmoDmVSSSchCpUpaGDNsQoAUwmepCVjELEKKKSHrSooZne9Genczp056eg+kkkqQhRCmmcpWqqaqQLkdh5ZZ+tFiQrkbQeNORAm+JZK0F3rjpQkQbdSSsCVrJaq6C+CiRrllcKdKWyvzLErJjRznhotRFR+mW2AHjLrajbSkskr+COqxClbaYrq7oM4UpQujNSCy9BCZBqJaz3UlSlvf0GLPDABBds8MEIJ6zwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9daSBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn6+cgAEC9GseMHCg4AD8+ZWEgAIFGWAgQQcssECAJCUwwH8EGWjAQAQoOCGDIz04AAIDSdihggNgOJICGnZ4IAADWCgiSQU8SCAAHiag4AIADpTAih0hoOGNHqa4QH8DDcAAAwXgSFECBCjA4UAtQlhQAQsI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9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dY5BQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgoAAE+ZmUAIAEEWBggQUUECBJCRgoQIEHCoRAggQsSJKBBOAnEIYDEZDggxaKhICB/W0YoQAJKhjiSPsRQCCHCaRI4EAarrhRgwbixyGKBVQ4kAIDDDCjjRE1qMCQAhg4pIQZShhkkCUSGdGIJGrY4JIFxfjkACpKKVGSGApQo0JbCullRfZh6ONCAkB5JkZUYolQk29iNGCdOxmg554HrInnQWmq6SdDexZqwJ8JtSgoRIYa0CeiCAWqJqQ3DTAmpRDJeVABCywwAKaZKtlQp50aMCioBkkapUIIHEDqAgeg4YoQmA4uNAADIBJgAKmfykoQlfzVSCdBBzDAwKEDDbDrqajiiKSoMBrLQJe+PoSjjxwCcCsDC1QbUYsvRgjAAsb26i1DVEaZLQAESHtuQy0KKy4ABhh76bsG1drhvNpSiy+gS+636r8U2XcvwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIctNmUBAQAh+QQJCgAAACwAAAAALAHlAAAI/wABCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3KtKnTp1CjSp1KtarVq1izat3KtavXr2DDih1LtqzZs2jTql3Ltq3bt3Djyp1Lt67du3jz6t3Lt6/fv4ADCx5MuLDhw4gTK17MuLHjx5AjS55MubLly5gza97MubPnz6BDix5NurTp06hTq17NurXr17Bjy55Nu7bt27hz697Nu7fv38CDCx9OvLjx48iTK1/OvLnz59CjS59Ovbr169iza9/Ovbv37+DDi/8fT768+fPo06tfz769+/fw48ufrzyBggT0ayogoKAgAvz5lZQAAgURYCBBAhwY4EgJGCgAQQYSMFCD/C1IUoQAAhDhQPsRQKCFIiFgYH8CbQiAiBWCOFKHH25IIQEZChSjihm9iN+GKD44EIoz0vhQgwp8KFCCHhrUIID2RSikjxChyB+SRSaUAJEOMkkRlQQI0ONBET5pZUVJmrgQikt+SRGZDv1nZo1lrmnTAHDGWYCObiIUZpcQxannAHUm1GGXEj605wBz9mnnn2IaStMAWyraUJsIEWCAAQU42qSBkBo06aQHBGqplH+SOOYBmxpwwKcIYUknQgUsoCMBpE7eWimqBDl530AwGjTAAgucOlABk3pKKwAULkkkgQwkSyyvCwg7bEMvCpQsAwLtuoABz0LEorTKCmQAr3xmyxCKok6LK7PiMtRhhuYOdACvjaZbYpYEtTvQAM7KW9CABS3AwAL6XlQAA7MGbPDBCCes8MIMN+zwwxBHLPHEFFds8cUYZ6zxxhx37PHHIIcs8sgkl2zyySinrPLKLLfs8sswxyzzzDTXbPPNOOes88489+zzz0AHLfTQRBdt9NFIJ6300kw37fTTUEct9dRUV2311VhnrfXWXHft9ddg8xQQACH5BAkKAAAALAAAAAAsAeUAAAj/AAEIHEiwoMGDCBMqXMiwocOHECNKnEixosWLGDNq3Mixo8ePIEOKHEmypMmTKFOqXMmypcuXMGPKnEmzps2bOHPq3Mmzp8+fQIMKHUq0qNGjSJMqXcq0qdOnUKNKnUq1qtWrWLNq3cq1q9evYMOKHUu2rNmzaNOqXcu2rdu3cOPKnUu3rt27ePPq3cu3r9+/gAMLHky4sOHDiBMrXsy4sePHkCNLnky5suXLmDNr3sy5s+fPoEOLHk26tOnTqFOrXs26tevXsGPLnk27tu3buHPr3s27t+/fwIMLH068uPHjyJMrX868ufPn0KNLn069uvXr2LNr3869u/fv4MOL/x9Pvrz58+jTq1/Pvr379/Djy5+vPIGCBPRrKiCgoCAC/PmVlAACBRFgIEECHBjgSAkYKABBBhIwUIP8LUhShAACEOFA+xFAoIUiIWBgfwJtCICIFYI4UocfbkghARkKFKOKGb2I34YoPjgQijPS+FCDCnwoUIIeGtQggPZFKKSPEKHIH5JFJpQAkQ4ySRGVBAjQ40ERPmllRUmauBCKS35JEZkO/WdmjWWuaVOXI27p5oQddgkRnCPOmVCddj6E5316IhRmn4HWBGOhZzokwAADkIhomga2eRCjlEr6KEGDOqpQAgVQOkABlyKEpY4JEWDAhwh4qmmoJ0YIKAACMOMwgEEDGGDAAQQpwKiliFK4pAEMMIDrAsQCkICtBkjIakQFBMsAfsQuIFABtuK6LEQLBDsrANEOdICtoF7b0ADBSitQtyUiK25D2TIQLrfFemurnOsOFKwBBKE7UAHK1ptQpwUZsAC+/l5EwALvFqzwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dZcd+3112CHLfbYZF8WEAAh+QQJCgAAACwAAAAALAHlAAAI/wABCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3KtKnTp1CjSp1KtarVq1izat3KtavXr2DDih1LtqzZs2jTql3Ltq3bt3Djyp1Lt67du3jz6t3Lt6/fv4ADCx5MuLDhw4gTK17MuLHjx5AjS55MubLly5gza97MubPnz6BDix5NurTp06hTq17NurXr17Bjy55Nu7bt27hz697Nu7fv38CDCx9OvLjx48iTK1/OvLnz59CjS59Ovbr169iza9/Ovbv37+DDi/8fT768+fPo06tfz769+/fw48ufrzyBggT0ayogoKAgAvz5lZQAAgURYCBBAhwY4EgJGCgAQQYSMFCD/C1IUoQAAhDhQPsRQKCFIiFgYH8CbQiAiBWCOFKHH25IIQEZChSjihm9iN+GKD44EIoz0vhQgwp8KFCCHhrUIID2RSikjxChyB+SRSaUAJEOMkkRlQQI0ONBET5pZUVJmrgQikt+SRGZDv1nZo1lrmlTlyNu6eaEHXYJEZwjzplQnXY+hOd9eiIUZp+B1qRmoRIhcECaBraJqEIDMMDAAA3VCeijChGwgKSSNjRllzpiapABnDJgQKgHCTAAknU6+mikki7kUIBACMhqEAEDDDDrjvvJiWgBklI60AELLECpAcgCkECuA7gqakEzalosfsgaIJACzD4rkQHFClvtQAXkSqK2DQ1QrLUCfUtrtuQ2xO0CEqabLLi5+truQMUuOpC6HDp770C4FnSAAfr+exEBBuxq8MIMN+zwwxBHLPHEFFds8cUYZ6zxxhx37PHHIIcs8sgkl2zyySinrPLKLLfs8sswxyzzzDTXbPPNOOes88489+zzz0AHLfTQRBdt9NFIJ6300kw37fTTUEct9dRUV2311VhnrfXWXHft9ddghy322GSXTV9AACH5BAkKAAAALAAAAAAsAeUAAAj/AAEIHEiwoMGDCBMqXMiwocOHECNKnEixosWLGDNq3Mixo8ePIEOKHEmypMmTKFOqXMmypcuXMGPKnEmzps2bOHPq3Mmzp8+fQIMKHUq0qNGjSJMqXcq0qdOnUKNKnUq1qtWrWLNq3cq1q9evYMOKHUu2rNmzaNOqXcu2rdu3cOPKnUu3rt27ePPq3cu3r9+/gAMLHky4sOHDiBMrXsy4sePHkCNLnky5suXLmDNr3sy5s+fPoEOLHk26tOnTqFOrXs26tevXsGPLnk27tu3buHPr3s27t+/fwIMLH068uPHjyJMrX868ufPn0KNLn069uvXr2LNr3869u/fv4MOL/x9Pvrz58+jTq1/Pvr379/Djy5+vPIGCBPRrKiCgoCAC/PmVlAACBRFgIEECHBjgSAkYKABBBhIwUIP8LUhShAACEOFA+xFAoIUiIWBgfwJtCICIFYI4UocfbkghARkKFKOKGb2I34YoPjgQijPS+FCDCnwoUIIeGtQggPZFKKSPEKHIH5JFJpQAkQ4ySRGVBAjQ40ERPmllRUmauBCKS35JEZkO/WdmjWWuaVOXI27p5kAFLMDAnXhCBOeIcyaE558M6AnnfX0iVCeggRZqk5qKSpTAAGka2GajCg2wwAIFNNShl5QqRIABl17a0JRd6tipQQeEusABph4kIpKbTtxKqaWXGiDhibYalCNBCOwnZ6MEXArpQAcYYACkAyQLgI2njlqgsQbgl+ywKJLYLETFGpApANNyKOm1DxVg7AEDdSujmOAuBO2t3Co7EJG/pjvQuASZu2O88pa4LZ0D7JuvRQIM0Oq/BBds8MEIJ6zwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dZcd+21fAEBACH5BAkKAAAALAAAAAAsAeUAAAj/AAEIHEiwoMGDCBMqXMiwocOHECNKnEixosWLGDNq3Mixo8ePIEOKHEmypMmTKFOqXMmypcuXMGPKnEmzps2bOHPq3Mmzp8+fQIMKHUq0qNGjSJMqXcq0qdOnUKNKnUq1qtWrWLNq3cq1q9evYMOKHUu2rNmzaNOqXcu2rdu3cOPKnUu3rt27ePPq3cu3r9+/gAMLHky4sOHDiBMrXsy4sePHkCNLnky5suXLmDNr3sy5s+fPoEOLHk26tOnTqFOrXs26tevXsGPLnk27tu3buHPr3s27t+/fwIMLH068uPHjyJMrX868ufPn0KNLn069uvXr2LNr3869u/fv4MOL/x9Pvrz58+jTq1/Pvr379/Djy5+vPIGCBPRrKiCgoCAC/PmVlAACBRFgIEECHBjgSAkYKABBBhIwUIP8LUhShAACEOFA+xFAoIUiIWBgfwJtCICIFYI4UocfbkghARkKFKOKGb2I34YoPjgQijPS+BABDDAwAIIGfkhQgwDaF6GRPkI0QJAMLFCAjB4qlECCEerYpEQGQMmAAVoqFCF/PW75UAELeNkQikyaSdGTQjr0n5sZITAknTqNOWKZeA6EppdBQqTniH0mBCiUgup5X6EI/QkoozfNCalECdzJEJuTQlSAAQZMyVCHZGa6kAIHcMppQ1eOGaaoBJVq6gFt+tgHo4ygxirqppweICEAla56ooMEIbAfn5MSwKmnAhUwwAASuoghq6j6t+wANyqIIonQOrksiSYCwGK2Dykw7UDdvgiuQ9Ma2S0AWBJ77kDL7lqigju6+65AIha0H7b3WmSfvf0GLPDABBds8MEIJ6zwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dbrBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgIAL8+ZVUwAAFEWAgQQIcGOBICTDAgAEEGUjAQAkY2N+CIznIwIQCSTjQfgQggOFIAzi4wEAeAoCAhSOStICDBALgYYUGAkhhix0VoKGIHq5IgAAE+WgjjhERsIABMQpkgIMHGFQhgPZJKCKREg2wwJUGcCgAA0kelECCEgJJ5UQHXHnlAVMuJCF/Q45ZpAFmnsiQj2m6WZGVCxTg0H92ZpRAl33etKaFbQZa4AEGJKooRIOyaChCikYK4UON3vcoQgQgKumlN/HJqUQV7mlgnZ8ypMAAA4i5EIhslroQAqjG39rQl2uq6mpBA8ZaQKFBEgAlq6TeeiqqBYj5pEE+2orAfryWCusAFwoEJo8H0ujrrbMWZC1+KfoYLbYPgThligCIC+5D3qKoIADWnusQiDaSCwCYzbob4Y/3cjiQp/YqlACp+33bb0X21TvwwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIfdXkAAOw==';
        $("#renaper_foto").attr("width", "160px");
        $("#renaper_foto").attr("src", 'data:image/gif;base64,' + gif); */
        /* $.ajax({
            data: {
                'servicio': '*get_renaper',
                'auditoria': 'motu',
                'usuario_auditoria': 'motu',
                'filtro': 'documento=' + dni_persona,
                'tipo': 0
            },
            type: "POST",
            dataType: "json",
            url: "https://apisur.neuquen.gov.ar/index.php",

            success: function(data) {
                $.each(data, function(ind, elem) {
                    //respuesta = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                    console.log(ind);
                    if (ind == 'status') {
                        elem_aux = elem;
                        console.log(elem_aux);
                        if (elem_aux == 'error') {
                            $("#renaper_foto").attr("width", "");
                            $("#renaper_foto").attr("src", "");
                            return;
                        }
                    }

                    if (ind == 'records') {
                        console.log(elem[0]);
                        $("#renaper_foto").attr("src", elem[0].result.foto);
                    }
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
            },
        }); */
    }

    function buscar_en_renaper(dni_persona) {
        /*gif*/
        /* gif = 'R0lGODlhLAHlAPcAAP///    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x9Pvrz58+jTq1/Pvr379/Djy5+vvMACAvRrGlhgoOCAAvmZRACABC1gIEEGMMBAgCQlYIABBxR4oEAFKLgAgyQ9aIACAxl4oUALKDgAhiMV8GCEIE44gIUkknTAgwR6CAACCjJAYIshEaBhAgDIeICC/Q2EAAEE8IjjRAgMMACHA71owIgFVSiAQAkoQCQBCBw5kQJKKpnljAbgp5AAVxIwpZYTEdDlf0YuVKYCbaIpUZJrNjQklnJixOUAZzL0ZZ4YJSAmoDmVSSSchCpUpaGDNsQoAUwmepCVjELEKKKSHrSooZne9Genczp056eg+kkkqQhRCmmcpWqqaqQLkdh5ZZ+tFiQrkbQeNORAm+JZK0F3rjpQkQbdSSsCVrJaq6C+CiRrllcKdKWyvzLErJjRznhotRFR+mW2AHjLrajbSkskr+COqxClbaYrq7oM4UpQujNSCy9BCZBqJaz3UlSlvf0GLPDABBds8MEIJ6zwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9daSBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn6+cgAEC9GseMHCg4AD8+ZWEgAIFGWAgQQcssECAJCUwwH8EGWjAQAQoOCGDIz04AAIDSdihggNgOJICGnZ4IAADWCgiSQU8SCAAHiag4AIADpTAih0hoOGNHqa4QH8DDcAAAwXgSFECBCjA4UAtQlhQAQsIIBCUQzIQopESIUDAlgrcCICDUiYkgAFVMnAhlhIJsOWWAni5UJk0ollRAgqs+eJCQlopJ0ZaErAkQwf8uadFCQg66E1r2unmoQfRmeiWED3KJaMJ1SlppI92SSlCjj666U2GfgpRqAj1SaqoCZnakKVJLopqo6ze2LmQmmuG+apBtLK5kJY2snqqqH22SqGrAPRpa7F1Evsqkn4SRCuHawq0prK3MsQsgNEWO2m1EVm6ZLYAeMvtQ33eCe614zpkqZvgAkBrug3pSiGkBCFALbwEFVpQnbLiO6em/gYs8MAEF2zwwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311vkFBAAh+QQJCgAAACwAAAAALAHlAAAI/wABCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3KtKnTp1CjSp1KtarVq1izat3KtavXr2DDih1LtqzZs2jTql3Ltq3bt3Djyp1Lt67du3jz6t3Lt6/fv4ADCx5MuLDhw4gTK17MuLHjx5AjS55MubLly5gza97MubPnz6BDix5NurTp06hTq17NurXr17Bjy55Nu7bt27hz697Nu7fv38CDCx9OvLjx48iTK1/OvLnz59CjS59Ovbr169iza9/Ovbv37+DDi/8fT768+fPo06tfz769+/fw48ufr1zAAAH0axYYUKBgAQL5mZQAAgUNYCBBBxhgQIAkJUAAAfgNZOAAAxGg4IIMjvQgAQlIeKBACRrQX4YiIfCgAh5SCEABCh5AIkkKPEggABMCkMCFABLU4YscOfhghzUO0CJBAyywQI48SuSgAjMKJICMBlk4o4VGLqBikhGZeOKODjZ5kAAHVLmAi1hO9OSGAuy4kJgGIFmmkjFu2FABRl75ZkVaeqnQAGreadGAfu7EwKCELjBioAglEOeGbjJE6KMMIJrQooxCBCkDhkqaKKUPanqTnp5CBCpCeYYqKpQMLapAn6YepOiGKDbYdOaDEbZq0KwQLmTiQK+iautAWhKwaoWsAqBlrcbGWGyrPnp5JoFyArDhsr8u5GOO0WoZa7UPxTllpwJ5y61D2lYIro3RjrtQnGqmC8CZ6jJEK0HuGkttvLzqGeO2+Fak6L39BizwwAQXbPDBCCes8MIMN+zwwxBHLPHEFFds8cUYZ6zxxhx37PHHIIcs8sgkl2zyySinrPLKLLfs8sswxyzzzDTXbPPNOOes88489+zzz0AHLfTQRBdt9NFIJ6300kw37fTTUEct9dRUV2311VhnrfXWkQUEACH5BAkKAAAALAAAAAAsAeUAAAj/AAEIHEiwoMGDCBMqXMiwocOHECNKnEixosWLGDNq3Mixo8ePIEOKHEmypMmTKFOqXMmypcuXMGPKnEmzps2bOHPq3Mmzp8+fQIMKHUq0qNGjSJMqXcq0qdOnUKNKnUq1qtWrWLNq3cq1q9evYMOKHUu2rNmzaNOqXcu2rdu3cOPKnUu3rt27ePPq3cu3r9+/gAMLHky4sOHDiBMrXsy4sePHkCNLnky5suXLmDNr3sy5s+fPoEOLHk26tOnTqFOrXs26tevXsGPLnk27tu3buHPr3s27t+/fwIMLH068uPHjyJMrX868ufPn0KNLn069uvXr2LNr3869u/fv4MOL/x9Pvrz58+jTq1/Pvr379/Djy5+vHEEBBPRrEihQoKAC/PmVlACAA/HXX4EDDBAgSQkQQIAABBk4EAIJKrjgSA4SkECB/A1UoQIXjoSAgyAKJCEAClQYIkkKOAighAlUSKBAG67IUYMObighAQkeaKIBBhBgI0UN/keQAC4aJMAANRJwAJAG+DgkRCOSWGODMxqEwJNQHjAlRUhmKECNC0FpwAElfjlRAi1m2FABQEqpJkVVZpkQk3NmNGCeOy3g559R8qkQmxm6+dCfiC4gaEJtFirkoYkGuShChDo66U0D2HmpQ5oeNAADDFi4KadJMgQqqAvIOapBlRKQpkICGNhwKgMGrIpQmA5CqNCIBS5wqqi2ClSlq2RqqGWuBH3KwKPB0liqQGHiZ2iGZDbrEI6PGlrlq9Yy1CaAhqL4bLe7kjhQuNiS622O5zp4pLvqKoRsu8wKW228rGbZIrf4SsTmvf0GLPDABBds8MEIJ6zwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dY5BQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgoAAE+ZmUAIAEEWBggQUUECBJCRgoQIEHCoRAggQsSJKBBOAnEIYDEZDggxaKhICB/W0YoQAJKhjiSPsRQCCHCaRI4EAarrhRgwbixyGKBVQ4kAIDDDCjjRE1qMCQAhg4pIQZShhkkCUSGdGIJGrY4JIFxfjkACpKKVGSGApQo0JbCullRfZh6ONCAkB5JkZUYolQk29iNGCdOxmg554HrInnQWmq6SdDexZqwJ8JtSgoRIYa0CeiCAWqJqQ3DTAmpRDJeVABCywwAKaZKtlQp50aMCioBkkapUIIHEDqAgeg4YoQmA4uNAADIBJgAKmfykoQlfzVSCdBBzDAwKEDDbDrqajiiKSoMBrLQJe+PoSjjxwCcCsDC1QbUYsvRgjAAsb26i1DVEaZLQAESHtuQy0KKy4ABhh76bsG1drhvNpSiy+gS+636r8U2XcvwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIctNmUBAQAh+QQJCgAAACwAAAAALAHlAAAI/wABCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3KtKnTp1CjSp1KtarVq1iza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PPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dbrBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgIAL8+ZVUwAAFEWAgQQIcGOBICTDAgAEEGUjAQAkY2N+CIznIwIQCSTjQfgQggOFIAzi4wEAeAoCAhSOStICDBALgYYUGAkhhix0VoKGIHq5IgAAE+WgjjhERsIABMQpkgIMHGFQhgPZJKCKREg2wwJUGcCgAA0kelECCEgJJ5UQHXHnlAVMuJCF/Q45ZpAFmnsiQj2m6WZGVCxTg0H92ZpRAl33etKaFbQZa4AEGJKooRIOyaChCikYK4UON3vcoQgQgKumlN/HJqUQV7mlgnZ8ypMAAA4i5EIhslroQAqjG39rQl2uq6mpBA8ZaQKFBEgAlq6TeeiqqBYj5pEE+2orAfryWCusAFwoEJo8H0ujrrbMWZC1+KfoYLbYPgThligCIC+5D3qKoIADWnusQiDaSCwCYzbob4Y/3cjiQp/YqlACp+33bb0X21TvwwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIfdXkAAOw==';
        $("#renaper_foto").attr("width", "160px");
        $("#renaper_foto").attr("src", 'data:image/gif;base64,' + gif); */
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni_persona, function(data) {
            $.each(data, function(ind, elem) {
                //respuesta = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                console.log(ind);
                if (ind == 'status') {
                    elem_aux = elem;
                    console.log(elem_aux);
                    if (elem_aux == 'error') {
                        $('#txt_mensaje').html("No se encontro informacion del dni " + dni_persona + " , complete el alta manualmente");
                        $("#input_fecha_nacimiento").prop("readonly", false);
                        $("#input_apellido").prop("readonly", false);
                        $("#input_nombre").prop("readonly", false);
                        /* $("#renaper_foto").attr("width", "");
                        $("#renaper_foto").attr("src", ""); */
                        return;
                    }
                }

                if (ind == 'records') {
                    console.log(elem[0]);

                    $('#input_apellido').val(corregir_palabra(elem[0].result.apellido));
                    $('#input_nombre').val(corregir_palabra(elem[0].result.nombres));
                    $('#input_fecha_nacimiento').val(elem[0].result.fecha_nacimiento);
                    /* $("#renaper_foto").attr("src", elem[0].result.foto); */

                    $("#input_fecha_nacimiento").prop("readonly", true);
                    $("#input_apellido").prop("readonly", true);
                    $("#input_nombre").prop("readonly", true);

                    $('#txt_mensaje').html("Persona encontrada en RENAPER, completar datos faltantes para el alta...");

                }
            });
        });
    }



    function LimpiarCamposAltaPersona(dni_persona) {
       /*  $("#renaper_foto").attr("src", ""); */
        $('#txt_mensaje_alta_persona').html("");
        $('#input_combo_nacionalidad').val("");
        $('#input_combo_genero').val("");
        $('#input_apellido').val("");
        $('#input_nombre').val("");
        $('#input_combo_tipo_documento').val("");
        $('#input_numero_documento').val(dni_persona);
        $('#input_fecha_nacimiento').val("");
    }

    function PrepararCamposAltaPersonaAMano(dni_persona) {
        /* $("#renaper_foto").attr("width", "");
        $("#renaper_foto").attr("src", ""); */
        $('#txt_mensaje_alta_persona').html("");
        $('#input_combo_nacionalidad').val("");
        $('#input_combo_genero').val("");
        $('#input_apellido').val("");
        $('#input_nombre').val("");
        $('#input_combo_tipo_documento').val("");
        $('#input_numero_documento').val(dni_persona);
        $('#input_fecha_nacimiento').val("");


    }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        palabra = palabra.toLowerCase();
        palabra = palabra.replace(/(^\w{1})|(\s+\w{1})/g, letter => letter.toUpperCase());
        return palabra;
    }

    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }
</script>