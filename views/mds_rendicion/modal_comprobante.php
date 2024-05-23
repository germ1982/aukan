<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<div class="modal fade" id="modalRendicionComprobante" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-comprobante">Comprobante</h4>
            </div>

            <div class="modal-body">
                <input type="hidden" id="idrendicion" name="idrendicion">

                <div class="col-md-6 form-group required">
                    <label class="form-label">Fecha desde</label>
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
                </div>
                <div class="col-md-6 form-group required">
                    <label class="form-label">Fecha hasta</label>
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                </div>
                <div class="col-md-12 form-group">
                    <label class="form-label">Observaciones:</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="6"></textarea>
                </div>
                <div class="col-md-12 form-group required">
                    <label class="control-label">Documentación Adjunta</label>

                    <div class="adjuntar-text" style="display: flex; justify-content: flex-end"><i class="fa fa-upload"></i> Adjuntar archivos
                    </div>

                    <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">

                    <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </div>
                    <small class="text-muted" style="display: flex; justify-content: flex-end">La extension debe ser del tipo
                        ["pdf,jpeg,jpg,png,xls,xlsx"]. Tamaño máximo 50MB.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" style="margin-left: auto;" id="btn-guardar-comprobante">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php

$this->registerJs("
    clickGuadarComprobante();
");

?>

<script>
    function verificarCamposRequeridos() {
        $verificar = false;

        if ($("#fecha_desde").val() && $("#fecha_hasta").val() && $("#otros_adjuntos").val()) {
            $verificar = true;
        }
        return $verificar;
    }

    function clickGuadarComprobante() {

        $('#btn-guardar-comprobante').click(function() {
            if (verificarCamposRequeridos()) {
                let idrendicion = $('#idrendicion').val();
                let fecha_desde = $('#fecha_desde').val();
                let fecha_hasta = $('#fecha_hasta').val();
                let observaciones = $('#observaciones').val();
                let otros_adjuntos = $('#otros_adjuntos').val();

                $.ajax({
                    type: 'POST',
                    url: "index.php?r=mds_rendicion/storecomprobante",
                    data: {
                        idrendicion,
                        fecha_desde,
                        fecha_hasta,
                        observaciones,
                        otros_adjuntos
                    },
                    success: function(data) {
                        parseData = JSON.parse(data);
                        // alert(parseData.message);
                        location.reload();
                    },
                    error: function(message) {
                        console.log(message);
                        // alert("");
                    }
                });

            }
        })
    }
</script>