<?php

use app\models\Mds_conc_solicitud;
use yii\helpers\Html;

$fecha = '';
if ($model) {
    $fecha = date_create($model->created_at);
    $fecha = date_format($fecha, 'd/m/Y H:i');
}
?>
<div>
    <div>
        <b>Fecha:</b> <?= $fecha ?><br />
        <b>Observación:</b> <?= $model ? $model->observacion : '' ?><br />

    </div>
    <br />
    <div class="panel-group" id="accordion_adjunto">
        <div class="panel panel-accordion">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_adjunto" href="#adjunto">
                        Documentación
                    </a>
                </h4>
            </div>
            <div id="adjunto" class="accordion-body collapse in">
                <div class="panel-body" id="adjunto_content">
                    <?php if ($model->archivo) : ?>
                        <div class="row">
                            <div class='col-md-6' align="center" ;>
                                <?php if (Mds_conc_solicitud::getExtension($model->archivo) == 'pdf') : ?>
                                    <object width="90%" height="500px" type="application/pdf" data="<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->archivo" ?>">
                                        <p>Archivo Adjunto no disponible.</p>
                                    </object>
                                <?php else : ?>
                                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:90%;height=500px;' id='base64image' src='<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->archivo" ?>' />
                                <?php endif; ?>
                                <?= Html::a(
                                    'Ampliar',
                                    env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->archivo",
                                    [
                                        'target' => '_blank',
                                        'data-pjax' => '0',
                                        'class' => 'btn btn-success',
                                        'style' => 'width:80%; width:213px;',
                                    ]
                                ) ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <p>No existe documentación.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>