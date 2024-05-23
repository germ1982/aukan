<?php

use app\models\Mds_cap_instancia;
use yii\widgets\DetailView;
use app\models\Sds_com_persona;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_persona */
?>
<div class="mds-cap-persona-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute' => 'idpersonacap',
                    'label' => 'Inscripción',
                ],
              /*  [
                    'attribute' => 'idinstancia',
                    'label' => 'Instancia',
                    'value' => function ($model) {
                        $id = $model->idinstancia;
                        if ($id != null) {
                            $instancia = Mds_cap_instancia::findOne($id);
                            return $instancia->descripcion;
                        }
                        return "";
                    },
                ],

                [
                    'attribute' => 'termino',
                    'label' => 'Terminó',
                    'value' => function ($model) {
                        $val = $model->termino;
                        switch($val)
                        {
                            case 1:
                                return "Aprobado";   
                            case 2:
                                return "Incompleto";
                            case 0:
                                return "Desaprobado";
                        }
                    },
                ],
*/
                [
                    'attribute' => 'idpersona',
                    'label' => 'Persona',
                    'value' => function ($model) {
                        $idpersona = $model->idpersona;
                        if ($idpersona != null) {
                            $persona = Sds_com_persona::findOne($idpersona);
                            $id_tipo_doc = $persona->documento_tipo;
                            $configuracion = Sds_com_configuracion::findOne($id_tipo_doc);
                            $tipo_doc = substr($configuracion->descripcion, 2);
                            $data_persona = "$persona->apellido, $persona->nombre, $tipo_doc: $persona->documento";
                            return $data_persona;
                        }
                        return "";
                    },
                ],

                'telefono',

                [
                    'attribute' => 'mail',
                    'label' => 'E-mail',
                ],
                [
                    'attribute' => 'ultimo_año',
                    'label' => 'Último año aprobado',
                    'value' => function ($model) {
                        $id = $model->ultimo_año;
                        if ($id != null) {
                            $instancia = Sds_com_configuracion::findOne($id);
                            return $instancia->descripcion;
                        }
                        return "";
                    },
                ],
                [
                    'attribute' => 'localidad',
                    'label' => 'Localidad',
                    'value' => function ($model) {
                        $id = $model->localidad;
                        if ($id != null) {
                            $instancia = Sds_com_localidad::findOne($id);
                            return $instancia->descripcion;
                        }
                        return "";
                    },
                ],


                
            ],
    ]) ?>

</div>
