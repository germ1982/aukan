<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Sds_reg_movimiento;
use app\models\Sds_reg_tipo;
use kartik\bs4dropdown\Dropdown;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\models\Mds_seg_permiso;
use app\models\Sds_reg_registro;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_registro */
/* @var $form yii\widgets\ActiveForm */

function GetFechaActual()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $mydate = getdate(date("U"));

    $dia = $mydate['mday'];
    if ($dia <= 9) {
        $dia = '0' . $dia;
    }

    $mes = $mydate['mon'];
    if ($mes <= 9) {
        $mes = '0' . $mes;
    }

    $hora = $mydate['hours'];
    if ($hora <= 9) {
        $hora = '0' . $hora;
    }

    $minuto = $mydate['minutes'];
    if ($minuto <= 9) {
        $minuto = '0' . $minuto;
    }

    $Fecha = "$dia/$mes/$mydate[year] $hora:$minuto";
    //echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
    return $Fecha;
}?>
<script>
    if ($('#input_incidencia').val() == 1) {
        $('#div_incidencia').show();
    } else {
        $('#div_incidencia').hide();
    }

    mostrar_ocultar_menu_solucion();

    function mostrar_ocultar_menu_solucion() {
        if ($('#input_pendiente').val() == 1) {
            $('#div_fecha_solucion').hide();
            $('#div_combo_tecnicos_solucion').hide();
            $('#div_input_solucion').hide();

        } else {
            $('#div_fecha_solucion').show();
            $('#input_fecha_solucion').val(getFechaActual());
            $('#div_combo_tecnicos_solucion').show();
            $('#div_input_solucion').show();

        }
    }
    $('#form_movimiento').hide();
</script>
<div id="form_principal" class="sds-reg-registro-form">
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
    <!-- LINEA 1 SOLICITANTE Y FECHA ##################################################################################################################################################### -->
    <div class="row">
        <div class="col-md-9">
            <?= $form->field($model, 'usuario_solicitante')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                        join sds_com_persona p on p.idpersona=c.idpersona order by trim(p.nombre), p.apellido")->all(),
                    'idcontacto',
                    function ($model) {
                        return $model->legajo." - ".$model->nombre . " " . $model->apellido;
                    }
                ),
                'options' => ['placeholder' => 'Seleccionar Solicitante ...', 'onchange' => 'setear_sector();', 'id' => 'combo_solicitante'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label('Solicitante');
            ?>
        </div>
        <div class="col-md-3">
            <?php
            if ($model->fecha_hora != null) {
                $ban = 1;
                $fecha = $model->fecha_hora;
                $model->fecha_hora = date('d/m/Y', strtotime(str_replace('/', '-', $fecha)));
            } else {
                $ban = 0;
                $model->fecha_hora = date('d/m/Y', strtotime(str_replace('/', '-', GetFechaActual())));
            }
            echo $form->field($model, 'fecha_hora')->widget(DatePicker::class, [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'disabled' => false,
                'options' => [
                    'class' => 'form-control input-md',
                    'placeholder' => 'DD / MM / YYYY',
                    'label' => 'Fecha',
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'endDate' => date('d/m/Y'),
                    'todayHighlight' => true,
                    'autoclose' => true,
                    'label' => 'Fecha',
                ]
            ])->label('Fecha');
            ?>
        </div>
    </div>
    <!-- LINEA 2 SECTOR Y HORA ##################################################################################################################################################### -->
    <div class="row">
        <div class="col-md-9">
            <?= $form->field($model, 'iddispositivo')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Mds_org_dispositivo::findBySql("select * from mds_org_dispositivo")->all(),
                    'iddispositivo',
                    function ($model) {
                        $organismo = Mds_org_organismo::findOne($model->idorganismo);
                        return "$model->descripcion - $organismo->descripcion";
                    }
                ),
                'options' => ['placeholder' => 'Seleccionar Sector ...', 'id' => 'combo_sector'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Sector');
            ?>
        </div>
        <div class="col-md-3">
            <?php
            if ($ban == 1) {
                $model->hora = date('H:i', strtotime($fecha));
            } else {
                $model->hora = date('H:i', strtotime(GetFechaActual()));
            }
            echo $form->field($model, 'hora')->textInput(['disabled' => false])->label('Hora');
            ?>
        </div>
    </div>
    <!-- LINEA 3 DETALLES DE REGISTRO TIPO DERIVADOR INCIDENCIA##################################################################################################################################################### -->
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'idtipo')->dropDownList(
                ArrayHelper::map(
                    Sds_reg_tipo::find()->where(['entidad' => $entidad, 'activo' => 1])->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idtipo',
                    'descripcion'
                ),
            )->label('Tipo de Registro');
            ?>
        </div>
        <div class="col-md-3">
            <?php
            $idderivador = $model->usuario_derivacion;
            if ($idderivador == null) {
                $user = Yii::$app->user->identity;
                $idderivador = $user->idusuario;
            }
            $model->id_usuario_derivador = $idderivador;
            $usuario = Mds_seg_usuario::findOne($idderivador);
            $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
            $persona = Sds_com_persona::findOne($contacto->idpersona);
            $model->usuario_derivacion = "$persona->nombre $persona->apellido";
            echo $form->field($model, 'usuario_derivacion')->textInput(['disabled' => true])->label('Derivador');
            ?>
        </div>
        <!-- Muestro las incidencias sólo si la entidad es informatica -->
        <?php if ($entidad == Sds_reg_registro::ENT_INFORMATICA) : ?>
            <div class="col-md-2">
                <?= $form->field($model, 'incidencia_relacionada')->dropDownList(
                    [
                        '0' => "No",
                        '1' => "Si",
                    ],
                    [
                        'id' => 'input_incidencia',
                        'onchange' => 'mostrar_ocultar_incidencia()',
                        'disabled' => false,
                    ]
                )->label('Incidencia') ?>
            </div>
        <?php endif ?>
    </div>
    <!-- LINEA 4 DATOS DE INCIDENCIA ##################################################################################################################################################### -->
    <div id='div_incidencia'>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'equipo_detalle')->textInput()->label('Equipo') ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'ip')->textInput(['maxlength' => true])->label('Ip') ?>
            </div>
            <div class="col-md-3">
                <?php
                if ($model->fecha_ingreso != null) {
                    $model->fecha_ingreso = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_ingreso)));
                }
                echo $form->field($model, 'fecha_ingreso')->widget(DatePicker::class, [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'disabled' => false,
                    'options' => [
                        'class' => 'form-control input-md',
                        'placeholder' => 'DD / MM / YYYY',
                        'label' => 'Fecha',
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'endDate' => date('d/m/Y'),
                        'todayHighlight' => true,
                        'autoclose' => true,
                        'label' => 'Fecha',
                    ]
                ])->label('Ingreso');
                ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'usuario_ingreso')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                                            join sds_com_persona p on p.idpersona=c.idpersona order by trim(p.nombre), p.apellido")->all(),
                        'idcontacto',
                        function ($model) {
                            return $model->nombre . " " . $model->apellido;
                        }
                    ),
                    'options' => ['placeholder' => 'Seleccionar ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Usuario Ingreso');
                ?>
            </div>
            <hr>
        </div>
        <?php //DIV 
        if ($model->isNewRecord)
            echo '<div class="row" id="div_adjuntos_incidencia" style="display:none;">';
        else
            echo '<div class="row" id="div_adjuntos_incidencia">'
        ?>
        <div class="col-md-6">
            <?php if ($model->adjunto_recepcion == null) : ?>
                <?= $form->field($model, 'temp_archivo_adjunto_recepcion', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::class, [
                        'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/sds_reg_registro/update']),
                            'maxFileSize' => 1000000000,
                            'previewFileType' => 'file',
                            'initialCaption' => false,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showUpload' => false,
                            ]
                        ],
                    ])->label("Adjunto Recepcion");
                ?>
            <?php else : ?>
                <?php
                $ruta = 'uploads/registros_tecnicos';
                echo $form->field($model, 'temp_archivo_adjunto_recepcion', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::class, [
                        'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/sds_reg_registro/update']),
                            'maxFileSize' => 1000000000,
                            'previewFileType' => 'file',
                            'initialPreview' => [
                                Url::to('@web/' . $ruta . '/' . $model->adjunto_recepcion, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                            ],
                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                            'initialPreviewFileType' => Sds_reg_registro::getExtension($model->adjunto_recepcion), // image is the default and can be overridden in config below
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                            ]
                        ],
                        'pluginEvents' => [
                            "fileclear" => "function() { console.log('fileclear'); $('#borrar').val(true);}",
                            "filereset" => "function() {  }",
                        ]
                    ])->label("Adjunto Recepcion");
                ?>
            <?php endif; ?>
            <?= $form->field($model, 'borrar_adjunto_recepcion')->hiddenInput(['id' => 'borrar'])->label(false) ?>
        </div>
        <div class="col-md-6">
            <?php if ($model->adjunto_entrega == null) : ?>
                <?= $form->field($model, 'temp_archivo_adjunto_entrega', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::class, [
                        'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/sds_reg_registro/update']),
                            'maxFileSize' => 1000000000,
                            'previewFileType' => 'file',
                            'initialCaption' => false,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showUpload' => false,
                            ]
                        ],
                    ])->label("Adjunto Entrega");
                ?>
            <?php else : ?>
                <?php
                $ruta = 'uploads/registros_tecnicos';
                echo $form->field($model, 'temp_archivo_adjunto_entrega', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::class, [
                        'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/sds_reg_registro/update']),
                            'maxFileSize' => 1000000000,
                            'previewFileType' => 'file',
                            'initialPreview' => [
                                Url::to('@web/' . $ruta . '/' . $model->adjunto_entrega, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                            ],
                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                            'initialPreviewFileType' => Sds_reg_registro::getExtension($model->adjunto_entrega), // image is the default and can be overridden in config below
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                            ]
                        ],
                        'pluginEvents' => [
                            "fileclear" => "function() { console.log('fileclear'); $('#borrar').val(true);}",
                            "filereset" => "function() {  }",
                        ]
                    ])->label("Adjunto Entrega");
                ?>
            <?php endif; ?>
            <?= $form->field($model, 'borrar_adjunto_entrega')->hiddenInput(['id' => 'borrar'])->label(false) ?>
        </div>
    </div>
</div>
<!-- LINEA 5 PROBLEMA ##################################################################################################################################################### -->
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'problema')->textarea(['rows' => 2]) ?>
    </div>
</div>

<!-- BLOQUE 4 GRILLA DE MOVIMIENTOS ##################################################################################################################################################### -->

<div class="row" id="div_movimientos" style="border-radius: 5px; padding: 15px; display:<?= $model->isNewRecord ? 'none' : '' ?>">
    Movimientos:
    <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;">
        Sin Movimientos...
    </div>
</div>
<!-- BLOQUE 5 SOLUCION ##################################################################################################################################################### -->
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'registro_abierto')->dropDownList(
            [
                '1' => "Si",
                '0' => "No",
            ],
            [
                'id' => 'input_pendiente',
                'onchange' => 'mostrar_ocultar_menu_solucion()',
                'disabled' => false,
            ]
        )->label('Pendiente') ?>
    </div>
    <div class="col-md-3" id='div_fecha_solucion'>
        <?php
        if ($model->fecha_solucion != null) {
            $model->fecha_solucion = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_solucion)));
        }

        echo $form->field($model, 'fecha_solucion')->widget(DatePicker::class, [
            'name' => 'check_issue_date',
            'language' => 'es',
            'readonly' => false,
            'layout' => '{picker}{input}{remove}',
            'disabled' => false,
            'options' => [
                'class' => 'form-control input-md',
                'id' => 'input_fecha_solucion',
                'placeholder' => 'DD / MM / YYYY',
                'label' => 'Fecha',
            ],
            'pluginOptions' => [
                'value' => null,
                'format' => 'dd/mm/yyyy',
                'endDate' => date('d/m/Y'),
                'todayHighlight' => true,
                'autoclose' => true,
                'label' => 'Fecha',
            ]
        ])->label('Fecha de Solucion');
        ?>
    </div>
</div>
<div class="row" id="div_combo_tecnicos_solucion">
    <div class="col-md-12">
        <?php
        $consulta = "SELECT * FROM mds_seg_usuario WHERE idusuario IN (SELECT idusuario FROM mds_seg_usuario_rol WHERE idrol in (select idrol from mds_seg_permiso where iditem=33)) order by user";
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $model->tecnicos_solucion = $idusuario;
        echo $form->field($model, 'tecnicos_solucion')->widget(Select2::class, [
            'data' => ArrayHelper::map(Mds_seg_usuario::findBySql($consulta)->all(), 'idusuario', 'user'),
            'options' => ['id' => 'input_combo_tecnicos_solucion', 'placeholder' => '', 'multiple' => true],
            'size' => Select2::MEDIUM,
            'pluginOptions' => [
                'tags' => true,
                // 'tokenSeparators' => [',', ' '],
                'allowClear' => true
            ],
        ])->label('Asignar Tecnicos');
        ?>
    </div>
</div>
<div class="row" id="div_input_solucion">
    <div class="col-md-12">
        <?= $form->field($model, 'solucion')->textarea(['rows' => 2]) ?>
    </div>
</div>
<?php if (!Yii::$app->request->isAjax) { ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php } ?>

<?php ActiveForm::end(); ?>

</div>

<!--DIV MOVIMIENTOS ##################################################################################################################################################### -->
<div id="form_movimiento" class="sds-reg-registro-form" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;">
    <!-- LINEA 1 TITULO ##################################################################################################################################################### -->
    <div class="row">
        <div class="col-md-12">
            <?= Html::label('<b><h4>Titulo</h4></b>', 'label_titulo', ['id' => 'label_titulo']) ?>
        </div>
    </div>

    <!-- LINEA 2 FECHA Y HORA Y TIPO Y TECNICO SI ES EDICION##################################################################################################################################################### -->
    <div class="row" style="padding-top:5px">
        <div class="col-md-3">
            <?= Html::label('Fecha', 'label_fecha', ['id' => 'label_fecha']) ?>
            <?=
            DatePicker::widget([
                'name'  => 'from_date',
                'value'  => '',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'value' => date('d/m/Y'),
                'options' => [
                    'class' => 'form-control input-md',
                    'id' => 'input_fecha',
                    'placeholder' => 'DD / MM / YYYY',
                    'label' => 'Fecha',
                ]
            ]);
            ?>
        </div>
        <div class="col-md-2">
            <?= Html::label('Hora', 'label_fecha', ['id' => 'label_fecha']) ?>
            <?= Html::textInput('input_hora', '00:00', ['id' => 'input_hora', 'disabled' => true, 'class' => 'form-control input-md']) ?>
        </div>

        <div class="col-md-2">
            <?= Html::label('Tipo', 'label_fecha', ['id' => 'label_fecha']); ?>
            <?= html::dropDownList('ListaTipos', '', ['0' => 'comun', '1' => 'Ingreso', '2' => 'Solucion'], ['id' => 'combo_tipos', 'class' => 'form-control input-md']); ?>
        </div>

        <div id='div_combo_tecnico' class="col-md-5">
            <?= Html::label('Tecnico Asignado', 'label_fecha', ['id' => 'label_fecha']); ?>
            <?php
            $consulta = "SELECT * FROM mds_seg_usuario WHERE idusuario IN (SELECT idusuario FROM mds_seg_usuario_rol WHERE idrol
                        in (select idrol from mds_seg_permiso where iditem=33)) order by user";
            $datos = ArrayHelper::map(Mds_seg_usuario::findBySql($consulta)->all(), 'idusuario', 'user');
            echo html::dropDownList('input_combo_tecnico', '', $datos, ['id' => 'input_combo_tecnico', 'class' => 'form-control input-md']);
            ?>
        </div>
    </div>
    <!-- LINEA 3 DESCRIPCION ##################################################################################################################################################### -->
    <div class="row">
        <div class="col-md-12">
            <?= Html::label('Descripcion', 'label_fecha', ['id' => 'label_fecha']) ?>
            <?= Html::textarea('text', 'Descripcion', $options = [
                    'id' => 'input_descripcion',
                    'rows' => 3,
                   'class' => 'form-control input-md'
            ])?>
        </div>
    </div>
    <!-- LINEA 4 TECNICOS SI ES NUEVO ##################################################################################################################################################### -->
    <div class="row">
        <div id='div_combo_tecnicos' class="col-md-12" style="padding-top:20px">
            <?php
            switch($entidad){
                case Sds_reg_registro::ENT_INFORMATICA:
                    $itemseg=Mds_seg_item::MODULO_REG_TECNICO;
                    break;
                case Sds_reg_registro::ENT_MANTENIMIENTO:
                    $itemseg=Mds_seg_item::MODULO_REG_MANTENIMIENTO_TECNICO;
                    break;
                case Sds_reg_registro::ENT_RUMBO:
                    $itemseg=Mds_seg_item::MODULO_RUM_REG_TECNICO;
                    break;
            }
            $consulta = "SELECT * FROM mds_seg_usuario WHERE idusuario IN (SELECT idusuario FROM mds_seg_usuario_rol WHERE idrol
            in (select idrol from mds_seg_permiso where iditem=".$itemseg.")) order by user";

            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;
            
            $model->tecnicos = $idusuario;

            echo $form->field($model, 'tecnicos')->widget(Select2::class, [
                'data' => ArrayHelper::map(Mds_seg_usuario::findBySql($consulta)->all(), 'idusuario', 'user'),
                'options' => ['id' => 'input_combo_tecnicos', 'placeholder' => '', 'multiple' => true],
                'size' => Select2::MEDIUM,
                'pluginOptions' => [
                    'tags' => true,
                    // 'tokenSeparators' => [',', ' '],
                    //'maximumInputLength' => 50,
                    'allowClear' => true
                ],
            ])->label('Asignar Tecnicos');
            ?>
        </div>
    </div>
    <!-- LINEA 5 MENSAJE DE ESTADO ##################################################################################################################################################### -->
    <div class="row">
        <div class="col-md-12">
            <?= Html::label('Estado: Editando...', 'label_estado_movimiento', ['id' => 'label_estado_movimiento']); ?>
        </div>
    </div>
    <!-- LINEA 6 BOTONES ##################################################################################################################################################### -->
    <div class="row">
        <div class="col-md-2" style="padding-top:27px">
            <?= Html::Button('Cancelar', [
                'id' => 'cancelar_operacion',
                'class' => 'btn btn-primary',
                'onclick' => 'js:MostrarFormularioPrincipal();'
            ]);
            ?>
        </div>
        <div class="col-md-8" style="padding-top:27px">
        </div>
        <div class="col-md-2" style="padding-top:27px; text-align:right ">
            <?= Html::Button('Guardar', [
                'id' => 'guardar_nueva_configuracion',
                'class' => 'btn btn-primary',
                'onclick' => 'js:validar_datos_movimiento();'
            ]);
            ?>
        </div>
    </div>
    <input type='hidden' id='input_hidden_id_movimiento' value='0'>
    <input type='hidden' id='input_hidden_id_registro' value='0'>
    <input type='hidden' id='input_hidden_id_derivador' value='0'>
</div>

<script>
    function MostrarGrillaMovimientos(idregistro) {
        aux = "index.php?r=sds_reg_registro/grilla_movimientos&idregistro=" + idregistro;
        $.post(aux, function(data) {
            $("#div_grilla").html(data);
        });
    }

    function setear_sector() {
        id_contacto = $('#combo_solicitante').val();
        aux = "index.php?r=sds_reg_registro/get_iddispositivo_contacto&id_contacto=" + id_contacto;
        $.post(aux, function(data) {
            $('#combo_sector').val(data).trigger("change");
        });
    }

    function validar_datos_movimiento() {
        if ($('#input_fecha').val() == '') {
            alert('Falta Fecha');
            return false;
        }
        if ($('#input_hora').val() == '') {
            alert('Falta Hora');
            return false;
        }
        if ($('#input_descripcion').val() == '') {
            alert('Falta descripcion');
            return false;
        }
        var aux1 = $('#input_hidden_id_movimiento').val();
        var aux2 = $('#input_combo_tecnicos').val();
        //alert('aux2: ' + aux1 + ' aux2: ' + aux2)
        if ((aux1 == 0) && (aux2 == '')) {
            alert('Faltan Tecnicos');
            return false;
        }
        //alert("paso los validar");
        derivar_guardado();
    }

    function derivar_guardado() {
        var aux = $('#input_hidden_id_movimiento').val();
        if (aux == 0) {
            guardar_nuevo_movimiento();
        } else {
            actualizar_movimiento();
        }
    }

    function guardar_nuevo_movimiento() {
        var parametros = {
            "idregistro": $('#input_hidden_id_registro').val(),
            "idusuario": $('#input_hidden_id_derivador').val(),
            "fecha": $('#input_fecha').val(),
            "hora": $('#input_hora').val(),
            "descripcion": $('#input_descripcion').val(),
            "tipo": $('#combo_tipos').val(),
            "tecnicos": $('#input_combo_tecnicos').val(),
        };
        parametros = JSON.stringify(parametros);
        aux = "index.php?r=sds_reg_movimiento/insert_movimiento&datos=" + parametros;
        $.post(aux, function(data) {
            if (data == 'Guardado') {
                MostrarGrillaMovimientos($('#input_hidden_id_registro').val());
                MostrarFormularioPrincipal(); //vuelvo al formulario principal
            }
            $("#label_estado_movimiento").text(data);
        });
    }

    function actualizar_movimiento() {
        var parametros = {
            "idmovimiento": $('#input_hidden_id_movimiento').val(),
            "idregistro": $('#input_hidden_id_registro').val(),
            "idusuario": $('#input_hidden_id_derivador').val(),
            "fecha": $('#input_fecha').val(),
            "hora": $('#input_hora').val(),
            "descripcion": $('#input_descripcion').val(),
            "tipo": $('#combo_tipos').val(),
            "idtecnico": $('#input_combo_tecnico').val(),
        };
        parametros = JSON.stringify(parametros);
        aux = "index.php?r=sds_reg_movimiento/set_movimiento&datos=" + parametros;
        $.post(aux, function(data) {
            if (data == 'Guardado') {
                MostrarGrillaMovimientos($('#input_hidden_id_registro').val());
                MostrarFormularioPrincipal(); //vuelvo al formulario principal
            }
            $("#label_estado_movimiento").text(data);
        });
    }

    function mostrar_ocultar_incidencia() {
        if ($('#input_incidencia').val() == 1) {
            $('#div_incidencia').show();
        } else {
            $('#div_incidencia').hide();
        }
    }

    function MostrarFormularioPrincipal() {
        $('#form_principal').show();
        $('#form_movimiento').hide();
        $("#btnGuardar").show();
        $("#btnCerrar").show();
    }

    function MostrarDivform_movimiento(id_movimiento, id_registro, id_derivador, fecha, tipo, descripcion, idtecnico) {
        $('#form_principal').hide();
        $('#form_movimiento').show();
        $("#btnGuardar").hide();
        $("#btnCerrar").hide();
        $('#input_hidden_id_movimiento').val(id_movimiento);
        $('#input_hidden_id_registro').val(id_registro);
        $('#input_hidden_id_derivador').val(id_derivador);
        $("#label_estado_movimiento").text('Estado: Editando...');

        if (id_movimiento == 0) {
            $("#div_combo_tecnico").hide();
            $("#div_combo_tecnicos").show();
            var titulo = 'Nuevo movimiento para el registro numero ' + id_registro;
            var fechasola = getFechaActual();
            var hora = getHoraActual();
            var tipo = 0;
            var descripcion = '';
        } else {
            $("#div_combo_tecnicos").hide();
            $("#div_combo_tecnico").show();
            var titulo = 'Editando movimiento id ' + id_movimiento + ' del registro numero ' + id_registro;
            var fechasola = MostrarFecha(fecha);
            var hora = MostrarHora(fecha);
            $('#input_combo_tecnico').val(idtecnico);
        }

        $('#label_titulo').text(titulo);
        $('#input_fecha').val(fechasola);
        $('#input_hora').val(hora);
        $('#combo_tipos').val(tipo);
        $('#input_descripcion').val(descripcion);
    }

    function MostrarFecha(Fecha) {
        //el parametro fecha ya debe venir formateado en dd/mm/aaaa, el digito de separacion es indiferente
        //var date = new Date();
        var day = Fecha.substring(8, 10);
        var month = Fecha.substring(5, 7);
        var year = Fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        //alert (today);
        return today;
    }

    function MostrarHora(Fecha) {
        var h = Fecha.substring(11, 13);
        var m = Fecha.substring(14, 16);

        var aux = h + ':' + m;
        //alert (today);
        return aux;
    }

    function getFechaActual() {
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();

        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;

        var today = day + "/" + month + "/" + year;
        return today;
    }

    function getHoraActual() {
        var f = new Date();
        var h = f.getHours()
        if (h <= 9) {
            h = "0" + h;
        }
        var m = f.getMinutes()
        if (m <= 9) {
            m = "0" + m;
        }
        var aux = h + ":" + m;
        return aux;
    }
</script>

<?php
/* Este coso es para ejecutar el script que busca la grilla de movimientos en el controler,
lo pongo a lo ultimo porque para tomar la funcion de javascript nesesita haber tenido ya en memoria, 
y es la forma mas practica de ejecutarla al arrancar el formulario pasandole parametros de php
si el regstro en nuevo y por lo tanto no existe id, no pasa nada solo queda el cartelito de sin movimientos que va por defecto en el formulario
sino, va al controller a buscar la grilla indicandole el id de registro y el usurio logueado que toma como derivador..
esto ultimo es porque con la grilla al dar de alta movimientos nesesita saber quien los carga... */
if ($model->idregistro) {
    $user = Yii::$app->user->identity;
    $id_derivador = $user->idusuario;
    echo "<script>
        MostrarGrillaMovimientos($model->idregistro);
        $('#input_hidden_id_registro').val($model->idregistro);
        $('#input_hidden_id_derivador').val($id_derivador);
        </script>";
}
?>