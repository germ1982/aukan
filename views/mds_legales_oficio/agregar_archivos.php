<?php

use app\models\Mds_legales_oficio;
use yii\helpers\Url;

$idRolSupervisor = Mds_legales_oficio::ID_ROL_SUPERVISOR;
$idRolGeneradorRespuesta = Mds_legales_oficio::ID_ROL_RECEPTOR;
?>

<div class="modal fade" id="modalAgregarArchivos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-agregar-archivos">Agregar archivos</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="idlegalesoficio-agregar-archivo" id="idlegalesoficio-agregar-archivo">
                <div class="row form-group">
                    <div class="col-md-12">
                        <label>Agregar archivo en:</label>
                        <select class="form-control" name="ARCHIVO_TIPO" id="ARCHIVO_TIPO">
                            <option value="" selected disabled>Seleccione...</option>
                            <option value="REQUERIMIENTO">Requerimiento</option>
                            <option value="REQUERIMIENTO_OTROS">Requerimiento - Otros adjuntos</option>
                            <option value="SUPERVISOR_SUGERENCIA">Supervisores - sugerencia</option>
                            <option value="SUPERVISOR_APROBACION">Supervisores - aprobación/observación</option>
                            <option value="RESPUESTA">Respuesta</option>
                            <option value="VINCULACION_COMPROBANTE">Vinculación - Comprobante</option>
                            <option value="VINCULACION_NOTA">Vinculación - Nota</option>
                        </select>
                    </div>
                </div>
                <div id="input-nota-container" style="display: none">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Número nota de la dependencia</label>
                            <input name="nro_nota_dependencia" id="nro_nota_dependencia" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label>Número de Vinculacion Judicial</label>
                            <input name="nro_vinculacion_judicial" id="nro_vinculacion_judicial" class="form-control">
                        </div>
                    </div>
                </div>
                <div id="input-comprobante-container" style="display: none">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Número de nota</label>
                                <input name="nro_nota" id="nro_nota" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 2rem;">
                    <label><strong>Archivos adjuntos</strong></label>
                    <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                    <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" style="margin-left: auto;" id="boton-guardar-archivos">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php

$this->registerJs("
    $('#ARCHIVO_TIPO').change(function() {
        if ($(this).val() === 'VINCULACION_COMPROBANTE') {
            $('#input-comprobante-container').show();
            $('#input-nota-container').hide();
        } else if ($(this).val() === 'VINCULACION_NOTA') {
            $('#input-nota-container').show();
            $('#input-comprobante-container').hide();
        } else {
            $('#input-comprobante-container, #input-nota-container').hide();
        }
    });

    $('#boton-guardar-archivos').click(function() {
        const idlegalesoficio = $('#idlegalesoficio-agregar-archivo').val();
        const otros_adjuntos = $('#otros_adjuntos').val();
        const archivo_tipo = $('#ARCHIVO_TIPO').val();
        const nro_nota_dependencia = $('#nro_nota_dependencia').val() ? $('#nro_nota_dependencia').val() : null;
        const nro_vinculacion_judicial = $('#nro_vinculacion_judicial').val() ? $('#nro_vinculacion_judicial').val() : null;
        const nro_nota = $('#nro_nota').val() ? $('#nro_nota').val() : null;

        if (otros_adjuntos.length) {
            $.ajax({
                type: 'POST',
                url: '" . Url::to(['/mds_legales_oficio/store_agregar_archivos']) . "', 
                data: { idlegalesoficio,
                        otros_adjuntos, 
                        archivo_tipo,
                        nro_nota_dependencia,
                        nro_vinculacion_judicial,
                        nro_nota
                        },
    
                success: function (data) {
                    parseData = JSON.parse(data);
                    alert(parseData.message);
                    location.reload();
                },
                error: function (errormessage) {
                    console.log(errormessage);
                    alert('Ocurrió un error al agregar los archivos.');
                }
            });
        }
    })
");


?>