<?php

use app\models\Mds_legales_oficio;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$idRolSupervisor = Mds_legales_oficio::ID_ROL_SUPERVISOR;
$idRolGeneradorRespuesta = Mds_legales_oficio::ID_ROL_RECEPTOR;
?>

<input type="hidden" name="idlegalesoficio" id="idlegalesoficio" value="<?= $idlegalesoficio ?>">
<div class="row">
    <div class="col-md-12">
        <label>Supervisores/as</label>
        <?php
        $usuariosSupervisores = ArrayHelper::map(Mds_legales_oficio::getUsuariosSegunRol($idRolSupervisor), 'idusuario', 'nombre_apellido');
        ?>
        <?=
        Select2::widget([
            'name' => 'supervisores',
            'id' => 'supervisores',
            'value' => '',
            'data' => $usuariosSupervisores,
            'options' => ['multiple' => true, 'required' => false],
            'showToggleAll' => false,
        ]);

        ?>
    </div>
</div>
<div class="row" style="margin-top: 2rem;">
    <div class="col-md-12">
        <label>Generadores/as de respuestas</label>
        <?php
        $usuariosGeneradoresRespuesta = ArrayHelper::map(Mds_legales_oficio::getUsuariosSegunRol($idRolGeneradorRespuesta), 'idusuario', 'nombre_apellido');
        ?>
        <?=
        Select2::widget([
            'name' => 'generadoresRespuesta',
            'id' => 'generadoresRespuesta',
            'value' => '',
            'data' => $usuariosGeneradoresRespuesta,
            'options' => ['multiple' => true, 'required' => false],
            'showToggleAll' => false,
        ]);

        ?>
    </div>
</div>
<div style="display: flex; margin-top: 2rem;">
    <button type="button" class="btn btn-success" style="margin-left: auto;" id="boton-guardar-derivaciones">Guardar</button>
</div>


<?php

$this->registerJs("
    $('#boton-guardar-derivaciones').click(function() {
        const idlegalesoficio = $('#idlegalesoficio').val();
        const supervisores = $('#supervisores').val();
        const generadoresRespuesta = $('#generadoresRespuesta').val();

        if (supervisores.length || generadoresRespuesta.length) {
            $.ajax({
                type: 'POST',
                url: '" . Url::to(['/mds_legales_oficio/store_agregar_derivaciones']) . "', 
                data: { idlegalesoficio,
                        supervisores, 
                        generadoresRespuesta
                        },
    
                success: function (data) {
                    parseData = JSON.parse(data);
                    alert(parseData.message);
                    $('#supervisores').val(null).trigger('change');
                    $('#generadoresRespuesta').val(null).trigger('change');
                    location.reload();
                },
                error: function (errormessage) {
                    console.log(errormessage);
                    alert('not working');
                }
            });
        }
    })
");
