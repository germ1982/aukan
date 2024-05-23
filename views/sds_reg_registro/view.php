<?php
use yii\widgets\DetailView;
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Sds_reg_tipo;
use yii\grid\GridView;
use app\models\Mds_seg_usuario;
use yii\helpers\Html;
use app\models\Sds_reg_movimiento;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_registro */
echo "<input type='hidden' id='input_hidden_id_movimiento' value=$model->incidencia_relacionada>";
echo "<input type='hidden' id='input_hidden_adjunto_recepcion' value=$model->adjunto_recepcion>";
echo "<input type='hidden' id='input_hidden_adjunto_entrega	' value=$model->adjunto_entrega	>";
?>

<style>
    .campo {
        padding: 6px 12px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>
<div class="sds-reg-registro-view">
    <div class="row">
        <div class="col-md-4">
            <h6><b>Solicitante</b></h6>
            <p class="campo">
                <?php
                    $contacto = Mds_org_contacto::findOne($model->usuario_solicitante);
                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                    echo "$persona->apellido, $persona->nombre";
                ?>
            </p>
        </div>
        <div class="col-md-2">
            <h6><b>Fecha Solicitud: </b></h6>
            <p class="campo">
                <?php echo date_format(date_create($model->fecha_hora), 'd/m/Y') ?>
            </p>
        </div>
        <div class="col-md-2">
            <h6><b>Hora: </b></h6>
            <p class="campo">
                <?php echo date_format(date_create($model->fecha_hora), 'H:i') ?>
            </p>
        </div>
        <div class="col-md-4">
            <h6><b>Derivador</b></h6>
            <p class="campo">
                <?php
                    $usuario = Mds_seg_usuario::findOne($model->usuario_derivacion);
                    $contacto= Mds_org_contacto::findOne($usuario->idcontacto);
                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                    echo "$persona->apellido, $persona->nombre";
                ?>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <h6><b>Sector</b></h6>
            <p class="campo">
                <?php
                    $dispositivo = Mds_org_dispositivo::findOne($model->iddispositivo);
                    $organismo = Mds_org_organismo::findOne($model->idorganismo);
                    echo "$dispositivo->descripcion - $organismo->descripcion";
                ?>
            </p>
        </div>
        <div class="col-md-2">  
            <h6><b>Tipo</b></h6>
            <p class="campo">
                <?php
                    $tipo = Sds_reg_tipo::findOne($model->idtipo);
                    echo "$tipo->descripcion";
                ?>
            </p>
        </div>
    </div>
    <div id="div_incidencia">
        <div class="row">
            <div class="col-md-1">  
                <h6><b>Incidencia</b></h6>
                <p class="campo">
                    <?php
                        if($model->incidencia_relacionada==1)
                            {$incidencia = 'Si';}
                        else
                            {$incidencia = 'No';}
                        echo "$incidencia";
                    ?>
                </p>
            </div>
            <div class="col-md-4">  
                <h6><b>Equipo</b></h6>
                <p class="campo">
                    <?php
                        echo $model->equipo_detalle;
                    ?>
                </p>
            </div>
            <div class="col-md-2">  
                <h6><b>Ip</b></h6>
                <p class="campo">
                    <?php
                        echo $model->ip;
                    ?>
                </p>
            </div>
            <div class="col-md-3">  
                <h6><b>Entrega</b></h6>
                <p class="campo">
                    <?php
                        if($model->usuario_ingreso!=null)
                            {
                                $contacto = Mds_org_contacto::findOne($model->usuario_ingreso);
                                $persona = Sds_com_persona::findOne($contacto->idpersona);
                                echo "$persona->apellido, $persona->nombre";
                            }
                        else
                            {
                                echo "...";
                            }
                    ?>
                </p>
            </div>
            <div class="col-md-2">  
                <h6><b>Ingreso</b></h6>
                <p class="campo">
                    <?php
                    if($model->fecha_ingreso!=null)
                        {echo date_format(date_create($model->fecha_ingreso), 'd/m/Y');}
                    else
                        {
                            echo "...";
                        }
                    
                    ?>
                </p>
            </div>
        </div>
        <div class="row">
            <?php
                if ($model->adjunto_recepcion)
                    {            
                        echo '<div class="col-md-2">';        
                        $ruta = 'uploads/registros_tecnicos';  
                        $url =  Url::to('@web/' . $ruta . '/' . $model->adjunto_recepcion, true);
                        echo Html::a('<h6><b>Adjunto Recepcion </b></h6>', $url, [
                            'title' => "Adjunto Recepcion",
                            'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            ]);
                        echo '</div>';
                    }

                if ($model->adjunto_entrega)
                    {
                        echo '<div class="col-md-2">';  
                        $ruta = 'uploads/registros_tecnicos';  
                        $url =  Url::to('@web/' . $ruta . '/' . $model->adjunto_entrega, true);
                        echo Html::a('<h6><b>Adjunto Entrega </b></h6>', $url, [
                            'title' => "Adjunto Entrega",
                            'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            ]);
                        echo '</div>';
                    }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">  
            <h6><b>Problema</b></h6>
            <p class="campo">
                <?php
                    echo $model->problema;
                ?>
            </p>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="font-size: 12px;">  
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
                                    'value' => function ($model) {
                                        $fc = date_create($model->fecha);
                                        $fc = date_format($fc, 'd/m/Y');
                                        return $fc;
                                    },
                                ],
                                [
                                    'label' => 'tipo',
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
                                    'value'=> function ($model) { return $model->descripcion;},
                                ],
                                [
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
        <div class="col-md-2">  
            <h6><b>Pendiente</b></h6>
            <p class="campo">
                <?php
                    if($model->registro_abierto==1)
                        {$pendiente = 'Si';}
                    else
                        {$pendiente = 'No';}
                    echo "$pendiente";
                ?>
            </p>
        </div>
        <div class="col-md-2">  
            <h6><b>Solucion</b></h6>
            <p class="campo">
                <?php
                    if($model->fecha_solucion!=null)
                        {echo date_format(date_create($model->fecha_solucion), 'd/m/Y');}
                    else
                        {echo "...";}
                ?>
            </p>
        </div>
    </div>
</div>

<script>
            if ($('#input_hidden_id_movimiento').val()==1) 
                {
                    $('#div_incidencia').show();
                } 
            else 
                {
                    $('#div_incidencia').hide();
                }
</script>