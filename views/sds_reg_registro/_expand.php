<?php

    use app\models\Sds_reg_ip;
?>

<div style='padding-left:  40px; '>
    <div style='border: 1px solid #ccc; border-radius: 4px;'>

        <div class='row'style='padding:0px 10px; '>
            <?php
                $consulta = "Select * FROM sds_reg_ip where idcontacto = $model->usuario_solicitante order by subred, ip";
                $data = Sds_reg_ip::findBySql($consulta)->all();
                if($data!=null)
                    {
                        $aux = '';
                        foreach($data as $ip)
                            {
                                if($aux) 
                                    {
                                        $aux = $aux.' - '.$ip['subred'].'.'.$ip['ip'];
                                    }
                                else
                                    {
                                        $aux = $ip['subred'].'.'.$ip['ip'];
                                    }
                                
                            }                       
                        crear_celda('Ips del solicitante',$aux, 12);
                    }
            ?>
        </div>

        <div class='row'style='padding:0px 10px;'>
            <?php crear_celda('Problema Reportado',$model->problema, 12);?>
        </div>
    </div>
</div>




