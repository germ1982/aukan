<?php
use app\models\Sds_gis_capa_item;
use app\models\Sds_reg_interno;
use kartik\form\ActiveForm;

if(!isset($edificio)){ //Verifico, si no está seteado el Id edificio muestro el select  ?>
    <div class="col-md-offset-3">
        <?php
        //Cargo todos los internos para filtrar solo los edificios que tienen internos.
        $allInternos=Sds_reg_interno::find()->all();
        $form = ActiveForm::begin();?>
        <select name="edificio" id="select-edificio">
            <?php
            $norepeat=''; //Para almacenar el valor del edificio y evitar repetirlo en el select
            foreach($allInternos as $interno){
                $edificioSelect=Sds_gis_capa_item::findOne($interno->idcapaitem);
                if($norepeat!=$edificioSelect->descripcion)
                    echo '<option value="'.$edificioSelect->idcapaitem.'">'.$edificioSelect->descripcion.'</option>';
                $norepeat=$edificioSelect->descripcion;
            }
            ?>
        </select>
    </div>
<?php ActiveForm::end();
}else{// Si está seteado el edificio genero el reporte
    $allInternos=Sds_reg_interno::findBySql(
        'SELECT i.idinterno, o.abreviatura organismo, d.descripcion dispositivo, i.responsable, i.recepcion 
	        FROM sds_reg_interno i
                INNER JOIN mds_org_dispositivo d ON i.iddispositivo=d.iddispositivo
		        INNER JOIN mds_org_organismo o ON d.idorganismo=o.idorganismo
            WHERE i.idcapaitem='.$edificio.($type=='recepcion' ? ' AND i.recepcion=1':''). //Modifico la consulta en base al tipo de reporte.
            ' ORDER BY organismo, dispositivo ASC'
        )->all();
    ?>
    <html>
        <body>
            <table class="table table-condensed table-internos">
                <thead>
                    <tr>
                        <?php $edificioObj=Sds_gis_capa_item::findOne($edificio); //Con el idEdificio Busco los datos del Objeto Edificio?>
                        <th colspan="<?= $type=='completo' ? '4':'3' ?>">
                            <h3><?= $edificioObj->descripcion ?></h3>
                            <h5>-Reporte <?= $type?>-</h5>
                        </th>
                    </tr>
                    <tr>
                        <th class="internos-head-items">Interno</th>
                        <th class="internos-head-items">Dirección</th>
                        <th class="internos-head-items">Nombre/Referente</th>
                        <?php if($type=='completo'):?>
                            <th class="internos-head-items">Recepción</th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $saltoLinea=''; //Para verificar cuando se cambia de Dirección y realizar espacios en blanco
                    if(empty($allInternos)){
                        echo '<tr><td colspan="'.($type=='completo' ? '4':'3').'"><b>No se encontraron registros.</b></td></tr>';
                    }
                    foreach ($allInternos as $interno){
                        $direccion=$interno->organismo.' - '.$interno->dispositivo;
                        if($saltoLinea!='' && $saltoLinea!=$direccion):?>
                            <tr>
                                <td class="internos-body-items-space"></td>
                                <td class="internos-body-items-space"></td>
                                <td class="internos-body-items-space"></td>
                                <?php if($type=='completo'):?>
                                    <td class="internos-body-items-space"></td>
                                <?php endif ?>
                            </tr>
                            <tr>
                                <td class="internos-body-items"><?= $interno->idinterno ?></td>
                                <td class="internos-body-items"><?= $direccion ?></td>
                                <td class="internos-body-items"><?= $interno->responsable?></td>
                                <?php if($type=='completo'):?>
                                    <td class="internos-body-items"><?= $interno->recepcion ==1 ? 'Si':'No'?></td>
                                <?php endif ?>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td class="internos-body-items"><?= $interno->idinterno ?></td>
                                <td class="internos-body-items"><?= $direccion ?></td>
                                <td class="internos-body-items"><?= $interno->responsable?></td>
                                <?php if($type=='completo'):?>
                                    <td class="internos-body-items"><?= $interno->recepcion ==1 ? 'Si':'No'?></td>
                                <?php endif ?>
                            </tr>
                    <?php endif;
                    $saltoLinea=$direccion;
                }?>
                </tbody>
            </table>
        </body>
    </html>
<?php } ?>

<?php
//Actualizo el href del boton 'Generar Reporte' al cargar la pagina y en cada cambio de estado.

$script = <<<  JS

$(document).ready(function(){
    var edi=$('#select-edificio option:selected').val();
    var href=$('#generate-report').attr('href');
    $('#generate-report').attr('href', href+'&edificio='+edi);
    
    $('#select-edificio').change(function(){
        var edi=$('#select-edificio option:selected').val()
        var href=$('#generate-report').attr('href');
        var fin=href.lastIndexOf('=');
        href=href.slice(0, fin);
        $('#generate-report').attr('href', href+'='+edi);
    });
});

JS;

$this->registerJs($script);
?>