<html>
    <body>
        <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
            <img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
            <br><br>
            <div class="row">
                <div class="col-xs-5 pull-right text-right">
                    <b>
                        <?php
                        switch(date('m', strtotime($linea->fecha_entrega))){
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
                        Neuquén, <?=date('d', strtotime($linea->fecha_entrega))?> de <?=$mes?> de <?=date('Y', strtotime($linea->fecha_entrega))?>
                    </b>
        	    </div>
            </div>
        	<br>
        	<div class="text-uppercase" style="text-align: center; padding-top:10px;"><b><u>Entrega de Equipo Corporativo</u></b></div>
        	<div class="row" style="padding-top: 20px;">
        		<p style="text-align: justify">
                    Por la presente se deja constancia que <b><?=$usuario_entrega->nombre?> <?=$usuario_entrega->apellido?> 
                    legajo N° <?=$usuario_entrega->legajo?></b>, entrega a <b><?=$responsable->nombre?> <?=$responsable->apellido?> 
                    legajo N° <?=$responsable->legajo?></b>, perteneciente a <b><?=$dispositivo_resp?></b>, 
                    el equipo corporativo marca: <b><?=$equipo->marca?></b>, 
                    modelo: <b><?=($equipo->modelo!=''?$equipo->modelo:'- SIN DATOS -')?></b>, con línea 
                    <b>N° <?=$linea->numero?></b>, IMEI: <b><?=$equipo->imei?></b>, 
                    el cual cuenta con el plan: <b><?=$plan?></b>.<br>
                    - Detalles de equipo: <b><?=$equipo->observaciones?></b>.
                </p>
                <br><br>
                <ul>
                    <li>Observaciones: <?=$linea->observaciones?></li>
                </ul>
                <br><br><br><br>
                <p style="font-size:12px;">
                    Aclaraciones necesarias<br>
                    <ul style="font-size:12px;">
                        <li>En caso de robo se debe realizar la denuncia del equipo sin excepción.</li>
                        <li>En caso de rotura se debe presentar el equipo en el sector de informática correspondiente 
                            para que realicen el informe necesario para solicitar la reposición.</li>
                        <li>Tenga presente que el equipo corporativo es un bien capital, por lo que queda bajo 
                            responsabilidad del agente responsable el buen uso del mismo.</li>
                        <li>Una vez finalizada su gestión deberá devolverlo al referente, para realizar la baja 
                            del cargo que se encuentra a su nombre, y este será el encargado de reasignarlo a quien 
                            corresponda.</li>
                        <li>Cualquier reasignación, ya sea de persona o área, debe realizarse con la intervención del 
                            referente correspondiente, para que se realicen la baja y alta de cargos correspondientes.</li>
                        <li>Queda prohibido realizar cargas o compras de paquetes contra factura sin realizar el proceso 
                            administrativo correspondiente</li>
                    </ul>
                </p>
                <br><br><br><br>
                <br><br><br><br>
                <div class="row">
                    <div class="col-xs-5" style="padding-left:50px;">
                        _____________________________________
                    </div>
                    <div class="col-xs-4" style="padding-left:60px;">
                        _____________________________________
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-5" style="padding-left:105px;">
                        Firma Referente
                    </div>
                    <div class="col-xs-4" style="padding-left:50px;">
                        Firma Responsable
                    </div>
                </div>
            </div>
        </div>
    </body>
    <footer style="position: absolute; bottom:15px; left:100px;">
        <img src="img/footer_ds.png" width="90%" alt="Subsecretaría de Desarrollo Social" style="opacity: 0.87;">
    </footer>
</html>