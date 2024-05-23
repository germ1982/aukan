<div class="modal fade" id="modalCrearMandato" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Crear Mandato a <?php echo $model->nombre ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="titular">Carácter</label>
                            <select class="form-control" id="titular">
                                <option>Titular</option>
                                <option>Suplente</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" id="divFechaInicio">
                        <input type="hidden" value="<?= $model->idregistro ?>" id="idRegistro" name="idRegistro">
                        <label for="fecha-inicio">Fecha Desde</label>
                        <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" id="divDatepickerInicio">
                            <input type="text" class="form-control" name="fechaInicio" id="fechaInicio">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                        <small id="smallInicio"></small>
                    </div>
                    <div class="col-md-6" id="divFechaFin">
                        <label for="fecha-inicio">Fecha Hasta</label>
                        <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" id="divDatepickerFin">
                            <input type="text" class="form-control" name="fechaFin" id="fechaFin">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                        <small id="smallFin"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" rows="3">Presidente <?php echo $model->nombre_presidente ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="activo">Activo</label>
                            <select class="form-control" id="activo">
                                <option>Si</option>
                                <option>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="boton-guardar-mandato">Crear Mandato</button>
            </div>
        </div>
    </div>
</div>