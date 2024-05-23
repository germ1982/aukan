<?php
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Sds_com_persona;
use yii\widgets\DetailView;
use app\models\Mds_org_documento;
use app\models\Mds_org_contacto;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use app\models\Sds_com_provincia;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_contacto */
?>
<div class="mds-org-contacto-view">
    <div class="row">
        <div class="col-md-5">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'documento',
                        'label' => 'Dni',
                        'value' => function ($model) {
                            $idpersona = $model->idpersona;
                            if ($idpersona != null) {
                                $persona = Sds_com_persona::findOne($idpersona);
                                return $persona->documento;
                            }
                            return "";
                        },
                    ],
                    [
                        'attribute' => 'legajo',
                    ],
                    [
                        'attribute' => 'ubicacion_fisica',
                    ],
                    [
                        'attribute' => 'nombre',
                        'label' => 'Nombre',
                        'value' => function ($model) {
                            $idpersona = $model->idpersona;
                            if ($idpersona != null) {
                                $persona = Sds_com_persona::findOne($idpersona);
                                return $persona->nombre;
                            }
                            return "";
                        },
                    ],
                    [
                        'attribute' => 'apellido',
                        'label' => 'Apellido',
                        'value' => function ($model) {
                            $idpersona = $model->idpersona;
                            if ($idpersona != null) {
                                $persona = Sds_com_persona::findOne($idpersona);
                                return $persona->apellido;
                            }
                            return "";
                        },
                    ],
                    [
                        'label' => 'Domicilio',
                        'value' => function ($model) {
                            if($model->idpersona != null) {
                                $persona=Sds_com_persona::findOne($model->idpersona);
                                if($persona!=null){
                                    if($persona->domicilio_calle!=''){
                                        return $persona->domicilio_calle.', '.$persona->domicilio_numero;
                                    }
                                }
                            }
                            return "- SIN DATOS -";
                        },
                    ],
                    [
                        'label' => 'Localidad',
                        'value' => function ($model) {
                            if($model->idpersona != null) {
                                $persona=Sds_com_persona::findOne($model->idpersona);
                                if($persona!=null){
                                    if($persona->idlocalidad!=''){
                                        $localidad=Sds_com_localidad::findOne($persona->idlocalidad);
                                        $provincia=Sds_com_provincia::findOne($localidad->idprovincia);
                                        return $localidad->descripcion.' ('.$localidad->codigo_postal.') - '.$provincia->descripcion;
                                    }
                                }
                            }
                            return "- SIN DATOS -";
                        }
                    ],
                    [
                        'attribute' => 'mail',
                    ],
                    [
                        'attribute' => 'telefono',
                    ],
                    [
                        'attribute' => 'titulo',
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-md-7">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'iddispositivo',
                        'value' => function ($model) {
                            $iddispositivo = $model->iddispositivo;
                            if ($iddispositivo != null) {
                                $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
                                $organismo = Mds_org_organismo::findOne($dispositivo->idorganismo);
                                return $dispositivo->descripcion . " - " . $organismo->descripcion;
                            }
                            return "";
                        },
                    ],
                    [
                        'attribute' => 'rotativo',
                        'value' => function ($model) {
                            return $model->rotativo == 1 ? 'Si' : 'No';
                        },
                    ],
                    [
                        'attribute' => 'eventual',
                        'value' => function ($model) {
                            return $model->eventual == 1 ? 'Si' : 'No';
                        },
                    ],
                    [
                        'attribute' => 'esencial',
                        'value' => function ($model) {
                            return $model->esencial == 1 ? 'Si' : 'No';
                        },
                    ],
                    [
                        'attribute' => 'activo',
                        'value' => function ($model) {
                            return $model->activo == 1 ? 'Si' : 'No';
                        },
                    ],
                    [
                        'attribute' => 'turno_rotativo',
                        'value' => function ($model) {
                            return $model->turno_rotativo == 1 ? 'Si' : 'No';
                        },
                    ],
                    [
                        'attribute' => 'ficha',
                        'value' => function ($model) {
                            return $model->ficha == 1 ? 'Si' : 'No';
                        },
                    ],
                    [
                        'attribute' => 'retenido',
                        'value' => function ($model) {
                            return $model->retenido == 1 ? 'Si' : 'No';
                        },
                    ],
                    [
                        'attribute' => 'tipo_contratacion',
                        'value' => function ($model) {
                            $tipo_contratacion = $model->tipo_contratacion;
                            switch ($tipo_contratacion) {
                                case Mds_org_contacto::TIPO_CONTRATACION_CONTRATO:
                                    return "Contratado";
                                case Mds_org_contacto::TIPO_CONTRATACION_EVENTUALES:
                                    return "Eventual";
                                case Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE:
                                    return "Planta Permanente";
                                case Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA:
                                    return "Planta Política";
                            }
                            return "";
                        }
                    ],
                    [
                        'attribute' => 'categoria',
                        'value' => function ($model) {
                            $categoria = $model->categoria;
                            if ($categoria != null) {
                                $categoria = Sds_com_configuracion::findOne($categoria);
                                return $categoria->descripcion;
                            }
                            return "";
                        }
                    ],
                    [
                        'attribute' => 'fecha_ingreso',
                        'value' => function($model){
                            if($model->fecha_ingreso!=''){
                                return date('d-m-Y', strtotime(str_replace('-', '/', $model->fecha_ingreso)));
                            }else{
                                return 'S/D';
                            }
                        }
                    ],
                    [
                        'attribute' => 'fecha_ingreso_planta',
                        'value' => function($model){
                            if($model->fecha_ingreso_planta!=''){
                                return date('d-m-Y', strtotime(str_replace('-', '/', $model->fecha_ingreso_planta)));
                            }else{
                                return 'S/D';
                            }
                        }
                    ]
                ],
            ]) ?>

        </div>
    </div>
    <div class="row" style="padding-top: 15px;">
        <div class="col-md-12">
            <h4 style="text-align: center;"><b>Archivos Adjuntos</b></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <?php
            $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]);
            $model_doc_aux = new Mds_org_documento();
            echo $form->field($model_doc_aux, 'tipo')->dropdownList(
                ArrayHelper::map(
                    Sds_com_configuracion::find()->where("idconfiguraciontipo=" .
                        Sds_com_configuracion_tipo::TIPO_CONTACTO_DOCUMENTO_TIPO . " and idconfiguracion in (
                        SELECT doc.tipo FROM mds_org_documento doc
                        WHERE idcontacto = " . $model->idcontacto . ")")
                        ->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idconfiguracion',
                    'descripcion'
                ),
                [
                    'prompt' => '- Seleccionar Tipo -',
                    'id' => 'tipo_documento'
                ]
            );
            ActiveForm::end();
            ?>
        </div>
        <div class="col-md-7">
            <div id="tabla_docs" style="padding-top: 15px;overflow: hidden">

            </div>
        </div>
    </div>
</div>
<script>
    $("#tipo_documento").change(function() {
        var idtipo = $("#tipo_documento").val();
        var idcontacto = <?= $model->idcontacto ?>;
        $.post("index.php?r=mds_org_contacto/get_documentos&idcontacto=" +
            idcontacto + "&idtipo=" + idtipo,
            function(data) {
                $("#tabla_docs").html(data);
            });
    });
</script>