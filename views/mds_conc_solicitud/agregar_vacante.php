<?php

use kartik\select2\Select2;
use yii\helpers\Url;

?>

<input type="hidden" name="idsolicitud" id="idsolicitud" value="<?= $id ?>">
<div class="row">
    <div class="col-md-12">
        <?= Select2::widget([
            'name' => 'nuevas_vacantes',
            'id'  => 'nuevas_vacantes',
            'data' => $arrayVacantes,
            'options' => ['multiple' => true],
            'showToggleAll' => false,
        ]); ?>
    </div>
</div>
<div style="display: flex; margin-top: 2rem;">
    <button type="button" class="btn btn-success" style="margin-left: auto;" id="btn-add-vacante">Guardar</button>
</div>


<?php

$this->registerJs("
    $('#btn-add-vacante').click(function() {
        const idsolicitud = $('#idsolicitud').val();
        const nuevas_vacantes = $('#nuevas_vacantes').val();

        if (nuevas_vacantes.length) {
            $.ajax({
                type: 'POST',
                url: '" . Url::to(['/mds_conc_solicitud/store_add_vacante']) . "', 
                data: { idsolicitud,
                        nuevas_vacantes, 
                        },
    
                success: function (data) {
                    parseData = JSON.parse(data);
                    alert(parseData.message);
                    location.reload();
                },
                error: function (errormessage) {
                    console.log(errormessage);
                    alert('error');
                }
            });
        }
    })
");
