<?php
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Sds_reg_tipo;
use yii\grid\GridView;
use app\models\Mds_seg_usuario;
use app\models\Sds_reg_movimiento;
use app\models\Sds_reg_registro;
use yii\data\ActiveDataProvider;
$idregistro = $_GET['idregistro'];
$model = Sds_reg_registro::findOne($idregistro);

function crear_celda($label,$contenido,$ancho)
    {
        echo "
        <div class='col-xs-$ancho'>  
            <h6><b>$label</b></h6>
            <p style='padding: 3px 6px; font-size: 12px; line-height: 1.42857143; color: #555555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px;'>
                    $contenido
            </p>
        </div>";
    }

?>

<html>
    <body>

        <img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">

        <div style="text-align: center;"><h5><b>Direccion General de Informatica y Comunicaciones <br>Registro Tecnico Numero <?=$idregistro?></b></h5></div><br>
        
        <div class="row">
            <?php
                $contacto = Mds_org_contacto::findOne($model->usuario_solicitante);
                $persona = Sds_com_persona::findOne($contacto->idpersona);
                $aux =  "$persona->apellido, $persona->nombre";
                crear_celda('Solicitante',$aux,5);

                $aux = date_format(date_create($model->fecha_hora), 'd/m/Y');
                crear_celda('Fecha Solicitud',$aux,2);

                $aux = date_format(date_create($model->fecha_hora), 'H:i');
                crear_celda('Hora',$aux,1);
            ?>
        </div>

        <div class="row">
            <?php
                $dispositivo = Mds_org_dispositivo::findOne($model->iddispositivo);
                $organismo = Mds_org_organismo::findOne($model->idorganismo);
                $aux =  "$dispositivo->descripcion - $organismo->descripcion";
                crear_celda('Sector',$aux,11);
            ?>
        </div>

        <div class="row">
            <?php
                $tipo = Sds_reg_tipo::findOne($model->idtipo);
                $aux =  "$tipo->descripcion";
                crear_celda('Tipo de Registro',$aux,3);

                $usuario = Mds_seg_usuario::findOne($model->usuario_derivacion);
                $contacto= Mds_org_contacto::findOne($usuario->idcontacto);
                $persona = Sds_com_persona::findOne($contacto->idpersona);
                $aux =  "$persona->apellido, $persona->nombre";
                crear_celda('Derivador',$aux,5);
            ?>
        </div>

        <?php // incidencia
            if($model->incidencia_relacionada==1)
                {
                    echo "<div class='row'>";
                        crear_celda('incidencia','Si',2);
                        crear_celda('Equipo',$model->equipo_detalle,6);
                        crear_celda('Ip',$model->ip,2);
                    echo "</div>";        

                    echo "<div class='row'>";
                        if($model->usuario_ingreso!=null)
                            {
                                $contacto = Mds_org_contacto::findOne($model->usuario_ingreso);
                                $persona = Sds_com_persona::findOne($contacto->idpersona);
                                $aux =  "$persona->apellido, $persona->nombre";
                            }
                        else
                            {
                                $aux =  "...";
                            }

                        crear_celda('Ingresa equipo',$aux,6);

                        if($model->fecha_ingreso!=null)
                                {$aux =  date_format(date_create($model->fecha_ingreso), 'd/m/Y');}
                            else
                                {
                                    $aux =  "...";
                                }

                        crear_celda('Fecha Ingreso',$aux,2);

                    echo "</div>";      
                }               
        ?>

        <div class="row">
            <?php crear_celda('Problema',$model->problema,12); ?>
        </div>

        <div class="row">
            <div class="col-xs-12">  
                <h6><b>Movimientos</b></h6>
                    <?php
                        $id_registro = $model->idregistro;
                        $dataProvider = new ActiveDataProvider(['query' => Sds_reg_movimiento::findBySql('Select * from sds_reg_movimiento where idregistro = '.$model->idregistro),]);  
                        echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'summary' => '',
                                'id' => 'grilla_movimientos',
                                'columns' => [
                                    [
                                        'label' => 'fecha',
                                        'contentOptions' => ['style' => 'font-size:11px; border: 1px solid #ccc;'],
                                        'value' => function ($model) {
                                            $fc = date_create($model->fecha);
                                            $fc = date_format($fc, 'd/m/Y');
                                            return $fc;
                                        },
                                    ],
                                    [
                                        'label' => 'tipo',
                                        'contentOptions' => ['style' => 'font-size:11px; border: 1px solid #ccc;'],
                                        'value' => function ($model) {
                                            switch($model->tipo)
                                                {
                                                    case 0:
                                                        {
                                                            $tipo = "Común";
                                                            break;
                                                        }
                                                    case 1:
                                                        {
                                                            $tipo = "Ingreso Equipo";
                                                            break;
                                                        }
                                                    case 2:
                                                        {
                                                            $tipo = "Solución";
                                                            break;
                                                        }
                                                }
                                            
                                            return $tipo;
                                        },
                                    ],
                                    [
                                        'label'=> 'Descripcion',
                                        'contentOptions' => ['style' => 'font-size:11px; border: 1px solid #ccc;'],
                                        'value'=> function ($model) { return $model->descripcion;},
                                    ],

                                    [
                                        'contentOptions' => ['style' => 'font-size:11px; border: 1px solid #ccc;'],
                                        'value' => function ($model) {
                                            $idderivador = $model->idusuario;
                                            $usuario = Mds_seg_usuario::findOne($idderivador);
                                            $contacto= Mds_org_contacto::findOne($usuario->idcontacto);
                                            $persona = Sds_com_persona::findOne($contacto->idpersona);
                                            return "$persona->nombre $persona->apellido";
                                        },
                                        'label'=> 'Carga',
                                    ],
                                    [
                                        'contentOptions' => ['style' => 'font-size:11px; border: 1px solid #ccc;'],
                                        'value' => function ($model) {
                                            $tecnico = $model->idtecnico;
                                            $usuario = Mds_seg_usuario::findOne($tecnico);
                                            $contacto= Mds_org_contacto::findOne($usuario->idcontacto);
                                            $persona = Sds_com_persona::findOne($contacto->idpersona);
                                            return "$persona->nombre $persona->apellido";
                                        },
                                        'label'=> 'Tecnico',
                                    ],
                                ],
                            ]);
                    ?>


            </div>
        </div>

        <div class="row">
            <?php
                if($model->registro_abierto==1)
                    {
                        crear_celda('Pendiente','Si',2);
                    }
                else
                    {
                        crear_celda('Pendiente','No',2);
                        if($model->fecha_solucion!=null)
                            {
                                $aux =  date_format(date_create($model->fecha_solucion), 'd/m/Y');
                            }
                        else
                            {
                                $aux =  "...";
                            }
                        crear_celda('Fecha de solucion',$aux,2);
                    }
            ?>
        </div>
    </body>

    <footer style="position: fixed; left: 0;bottom: 0px;width: 100%; font-size:8px">
        <div class="row">
            <div class="col-xs-12" style="text-align: center;">
                <p>
                    Direccion General de Informatica y Comunicaciones <br> Telefono: 449-8989
                </p>
            </div>
        </div>
    </footer>
</html>


