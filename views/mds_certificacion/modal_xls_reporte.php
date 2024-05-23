<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use app\models\Mds_certificacion;

$areaFuncionario = Mds_certificacion::AREA_FUNCIONARIO;
?>

<style>
    .select2-search__field {
        width: 100% !important;
    }

    .btn-exportar {
        margin-left: auto;
    }

    .boton-container {
        display: flex;
    }
</style>
<div class="row form-group">
    <div class="col-md-6 col-sm-12 form-group">
        <label class="col-form-label" for="MODAL_XLS_REPORTE_PERIODO_DESDE"><b>Periodo desde:</b></label>
        <input class="form-control" type="date" name="MODAL_XLS_REPORTE_PERIODO_DESDE" id="MODAL_XLS_REPORTE_PERIODO_DESDE">
    </div>
    <div class="col-md-6 col-sm-12 form-group">
        <label class="col-form-label" for="MODAL_XLS_REPORTE_PERIODO_HASTA"><b>Periodo hasta:</b></label>
        <input class="form-control" type="date" name="MODAL_XLS_REPORTE_PERIODO_HASTA" id="MODAL_XLS_REPORTE_PERIODO_HASTA">
    </div>
</div>
<div class="row form-group">
    <div class="col-sm-12 form-group">
        <label class="col-form-label" for="MODAL_XLS_REPORTE_ESTADO"><b>Estado:</b></label>
        <?= Select2::widget([
            'name' => 'MODAL_XLS_REPORTE_ESTADO',
            'data' => $estados,
            'options' => [
                'id' => 'MODAL_XLS_REPORTE_ESTADO',
                'placeholder' => 'Seleccione',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'showToggleAll' => false
        ]); ?>
    </div>
</div>
<div class="row form-group">
    <div class="col-sm-12 form-group">
        <label class="col-form-label" for="MODAL_XLS_REPORTE_PROGRAMA"><b>Programa:</b></label>
        <?= Select2::widget([
            'name' => 'MODAL_XLS_REPORTE_PROGRAMA',
            'data' => $programas,
            'options' => [
                'id' => 'MODAL_XLS_REPORTE_PROGRAMA',
                'placeholder' => 'Seleccione',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'showToggleAll' => false
        ]); ?>
    </div>
    <?php if ($area == $areaFuncionario) { ?>
        <div class="col-sm-6 form-group">
            <label class="col-form-label" for="MODAL_XLS_REPORTE_DIRECCION_POSICION"><b>Dirección:</b></label>
            <?= Select2::widget([
                'name' => 'MODAL_XLS_REPORTE_DIRECCION_POSICION',
                'data' => $direccionPosicion,
                'options' => [
                    'id' => 'MODAL_XLS_REPORTE_DIRECCION_POSICION',
                    'placeholder' => 'Seleccione',
                    // 'multiple' => true,
                    'onChange' => 'precargarDependientes()',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'showToggleAll' => false
            ]); ?>
        </div>
        <div class="col-sm-6 form-group">
        <?php } else { ?>
            <div class="col-sm-12 form-group">
            <?php } ?>
            <label class="col-form-label" for="MODAL_XLS_REPORTE_DIRECCION"><b>Proveniente de:</b></label>
            <?= Select2::widget([
                'name' => 'MODAL_XLS_REPORTE_DIRECCION',
                'data' => $direcciones,
                'options' => [
                    'id' => 'MODAL_XLS_REPORTE_DIRECCION',
                    'placeholder' => 'Seleccione',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => true,
                ]
            ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 form-group boton-container">
                <button type="button" class='btn btn-info' id="boton-limpiar-fechas" onclick="limpiarFiltros()">Limpiar filtros</button>
                <?= $botonExportar ?>
            </div>
        </div>

        <script>
            function exportarByFecha() {
                $('#loading').show();
                let area = '<?= $area ?>';
                let periodoDesde = $('#MODAL_XLS_REPORTE_PERIODO_DESDE').val();
                let periodoHasta = $('#MODAL_XLS_REPORTE_PERIODO_HASTA').val();
                let programas = $('#MODAL_XLS_REPORTE_PROGRAMA').val();
                let direcciones = $('#MODAL_XLS_REPORTE_DIRECCION').val();
                let direccionesPosicion = $('#MODAL_XLS_REPORTE_DIRECCION_POSICION').val();
                let estados = $('#MODAL_XLS_REPORTE_ESTADO').val();

                window.open(`index.php?r=mds_certificacion/xls_reporte&area=${area}&periodoDesde=${periodoDesde}&periodoHasta=${periodoHasta}&programas=${programas}&direccionesPosicion=${direccionesPosicion}&direcciones=${direcciones}&estados=${estados}`, '_blank');
                $('#loading').hide();
            }

            function limpiarFiltros() {
                $('#MODAL_XLS_REPORTE_PERIODO_DESDE').val("");
                $('#MODAL_XLS_REPORTE_PERIODO_HASTA').val("");
                $('#MODAL_XLS_REPORTE_PROGRAMA').val(null).trigger('change');
                $('#MODAL_XLS_REPORTE_DIRECCION').val(null).trigger('change');
                $('#MODAL_XLS_REPORTE_DIRECCION_POSICION').val(null).trigger('change');
                $('#MODAL_XLS_REPORTE_ESTADO').val(null).trigger('change');
            }

            function precargarDependientes() {
                let direccionesUser = $('#MODAL_XLS_REPORTE_DIRECCION_POSICION').val();
                let dato = ``;
                $('#MODAL_XLS_REPORTE_DIRECCION').html(``);

                $.post(`index.php?r=mds_certificacion/filter_direcciones_previas&direccionesUser=${direccionesUser}`, function(data) {
                    data = $.parseJSON(data);
                    if (data.length !== 0) {
                        $.each(data, function(ind, elem) {
                            dato += `<option value="${ind}">${elem}</option>`;
                        });
                        $("#MODAL_XLS_REPORTE_DIRECCION").html(dato);
                        $("#MODAL_XLS_REPORTE_DIRECCION").prop("disabled", false);
                    }
                });
            }
        </script>

        <?php
        $this->registerJs(
            "
            $(document).ready(function(){
                $('#MODAL_XLS_REPORTE_ESTADO').on('select2:select', function(e) {
                    const selectedValue = e.params.data.id;
                
                    if (selectedValue === 'select_all') {
                        $(this).find('option').prop('selected', true);
                        $(this).find('option[value=\"select_all\"]').prop('selected', false);
                        $(this).trigger('change');
                    }
                });

            });

            if('$area' !== '$areaFuncionario'){
                $('#MODAL_XLS_REPORTE_DIRECCION').prop('disabled', false);
            }

            $('#btn_buscar_todos').click(function() {
                exportarByFecha()
            });
        "
        ); ?>