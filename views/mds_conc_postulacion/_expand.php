<style>
    .alert-persona {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
    }

    .titulo-persona {
        margin: 0 0 10px 0;
    }

    .parrafo-persona {
        margin-bottom: 7px;
    }
</style>

<div class="row" style='padding: 10px 10px 0 10px; '>
    <div class="col-md-6">
        <div class="alert alert-info" role="alert">
            <h5 class="titulo-persona"><u><b>Detalle de la persona:</b></u></h5>
            <p class="parrafo-persona"><b>Apellido y Nombre:</b> <?= "{$model->solicitud->apellido}, {$model->solicitud->nombre}" ?></p>
            <p class="parrafo-persona"><b>Documento:</b> <?= $model->solicitud->documento ?></p>
            <p class="parrafo-persona"><b>Legajo:</b> <?= $model->solicitud->legajo ?></p>
            <p class="parrafo-persona"><b>Teléfono:</b> <?= $model->solicitud->telefono ?></p>
            <p class="parrafo-persona"><b>Domicilio Fiscal:</b> <?= $model->solicitud->domicilio_fiscal ?></p>
            <p class="parrafo-persona"><b>Correo electrónico:</b> <?= $model->solicitud->mail ?></p>

        </div>
    </div>
    <div class="col-md-6">
        <div class="alert alert-info" role="alert">
            <h5><b>INFO RH: </b></h5>
            <p>
            </p>
        </div>
    </div>
</div>