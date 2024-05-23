<?php
use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;

$movimiento=Sds_bdc_movimiento::findOne($idmovimiento);
if($movimiento==null){
    Yii::$app->response->redirect(Yii::$app->urlManager->createAbsoluteUrl(['sds_bdc_movimiento']));
    return;
}

if($movimiento->organismo_anterior!=null){
    $organismo_anterior=Mds_org_organismo::findOne($movimiento->organismo_anterior);
}
if(!isset($organismo_anterior)){
    $organismo_anterior=new Mds_org_organismo();
    $organismo_anterior->descripcion='- SIN DATOS -';
}
$tipo_mov=Sds_com_configuracion::findOne($movimiento->tipo);
if($tipo_mov->idconfiguracion==Sds_bdc_movimiento::MOV_ALTA || $tipo_mov->idconfiguracion==Sds_bdc_movimiento::MOV_BAJA){
    $tipo_mov->descripcion.=' Equipo';
}
$equipos_responsable=Sds_bdc_equipo::findBySql(
    'SELECT e.*
        FROM sds_bdc_movimiento_equipo me
        INNER JOIN sds_bdc_movimiento m ON m.idmovimiento=me.idmovimiento
        INNER JOIN sds_bdc_equipo e ON e.idequipo=me.idequipo
        WHERE me.idmovimiento='.$movimiento->idmovimiento.' ORDER BY e.responsable ASC'
)->all();
$responsable=-1;
foreach($equipos_responsable as $count=>$equipo):
    if($responsable!=$equipo->responsable):
        $responsable=$equipo->responsable;
        $contacto_responsable=Mds_org_contacto::findOne($equipo->responsable);
        $persona_responsable=Sds_com_persona::findOne($contacto_responsable->idpersona);
        ?>
        <html>
            <body>
        		<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        			<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
        			<br><br><br><br>
                    <div class="row">
                        <div class="col-xs-4 pull-right text-right">
                            <b>
                                <?php
                                switch(date('m', strtotime($movimiento->fecha_hora))){
                                    case 1:
                                        $mes='Enero';
                                        break;
                                    case 2:
                                        $mes='Febrero';
                                        break;
                                    case 3:
                                        $mes='Marzo';
                                        break;
                                    case 4:
                                        $mes='Abril';
                                        break;
                                    case 5:
                                        $mes='Mayo';
                                        break;
                                    case 6:
                                        $mes='Junio';
                                        break;
                                    case 7:
                                        $mes='Julio';
                                        break;
                                    case 8:
                                        $mes='Agosto';
                                        break;
                                    case 9:
                                        $mes='Septiembre';
                                        break;
                                    case 10:
                                        $mes='Octubre';
                                        break;
                                    case 11:
                                        $mes='Noviembre';
                                        break;
                                    case 12:
                                        $mes='Diciembre';
                                        break;
                                }
                                ?>
                                Neuquén, <?=date('d', strtotime($movimiento->fecha_hora))?> de <?=$mes?> de <?=date('Y', strtotime($movimiento->fecha_hora))?>
                            </b>
        			    </div>
                    </div>
        			<br>
        			<div class="text-uppercase" style="text-align: center; padding-top:10px;"><b><u>Informe de Movimiento de Equipo</u></b></div>
        			<div class="row" style="padding-top: 20px;">
        				<p>
                            Dejamos constancia que los equipos detallados a continuación a cargo de <b><?=$persona_responsable->apellido.', '.$persona_responsable->nombre?></b>,
                            <b>legajo N° <?=$contacto_responsable->legajo?></b>, correspondientes al sector <b><?=$organismo_anterior->descripcion?></b>, 
                            han sido afectados por el movimiento <b><?=$tipo_mov->descripcion?></b>.
                        </p>
                        <br>
                        <b>Equipos Afectados:</b>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                <th class="text-center my-border"># Equipo</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Marca</th>
                                <th class="text-center">Modelo</th>
                                <th class="text-center">Matrícula</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach($equipos_responsable as $equipo):
                                    if($responsable==$equipo->responsable):?>
                                        <tr>
                                            <td><?='#'.str_pad($equipo->idequipo,6,"0", STR_PAD_LEFT)?></td>
                                            <?php $tipo=Sds_com_configuracion::findOne($equipo->tipo)?>
                                            <td><?=$tipo->descripcion?></td>
                                            <?php $marca=Sds_com_configuracion::findOne($equipo->marca)?>
                                            <td><?=$marca->descripcion?></td>
                                            <td><?=($equipo->modelo!=''?$equipo->modelo:'S/D')?></td>
                                            <td><?=($equipo->matricula!=''?$equipo->matricula:'S/D')?></td>
                                        </tr>
                            
                                    <?php 
                                    endif;
                                endforeach;?>
                            </tbody>
                        </table>
                        <?php
                        if($tipo_mov->idconfiguracion==Sds_bdc_movimiento::MOV_CAM_RESPONSABLE):?>
                            <p style="padding-top:25px;">
                                <?php 
                                if($movimiento->responsable_nuevo!=null):
                                    $nuevo_responsable_contacto=Mds_org_contacto::findOne($movimiento->responsable_nuevo);
                                    $nuevo_responsable=Sds_com_persona::findOne($nuevo_responsable_contacto->idpersona);
                                    if($nuevo_responsable!=null):?>
                                        Nuevo Responsable: <b><?=$nuevo_responsable->apellido?>, <?=$nuevo_responsable->nombre?></b>
                              <?php endif;
                                endif;
                                if($movimiento->usuario_nuevo!=null):
                                    $nuevo_usuario_contacto=Mds_org_contacto::findOne($movimiento->usuario_nuevo);
                                    $nuevo_usuario=Sds_com_persona::findOne($nuevo_usuario_contacto->idpersona);
                                    if($nuevo_usuario!=null):?>
                                        <br>
                                        Nuevo Usuario: <b><?=$nuevo_usuario->apellido?>, <?=$nuevo_usuario->nombre?></b>
                              <?php endif;
                                endif;
                                if($movimiento->organismo_nuevo!=null):
                                    $organismo=Mds_org_organismo::findOne($movimiento->organismo_nuevo);
                                    if($organismo!=null):?>
                                        <br>
                                        Nuevo Sector: <b><?=$organismo->descripcion?></b>
                            <?php   endif;
                                endif;?>
                            </p>
                <?php   endif;
                        if($movimiento->observaciones!=null):?>
                          <div class="row">
                              <p style="padding-left: 14px; padding-top:25px;"><u>Observaciones del Movimiento:</u></p>
                              <p style="padding-left: 25px;"><?=$movimiento->observaciones?></p>
                          </div>
                        <?php
                        endif;?>
                    </div>
                    <div class="row" style="margin-top:250px;">
                        <div class="col-xs-5 text-center">
                            <?php $solicitante_contacto=Mds_org_contacto::findOne($movimiento->solicitante);
                            $solicitante=sds_com_persona::findOne($solicitante_contacto->idpersona);
                            ?>
                            __________________________________ <br>
                            Solicitante: <?=$solicitante->apellido?>, <?=$solicitante->nombre?>
                        </div>
                        <div class="col-xs-5 text-right">
                        __________________________________<br>
                        Responsable Informática.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>       
                <footer style="position: fixed; left: 0;bottom: 20px;width: 100%; font-size:14px">
                    <div class="row">
                        <div class="col-xs-12" style="text-align: center;">
                            <p>
                                Planas y Anaya - (0299) 449/3800) - Neuquén Capital<br>
                                Ministerio de Desarrollo Social y Trabajo
                            </p>
                        </div>
                    </div>
                </footer>
            </body>
        </html>
        <?php
        if($count!=count($equipos_responsable)-1):?>
            <div style="page-break-after:always;"></div>
<?php   endif;
    endif;?>
<?php
endforeach;
?>