function SortDeposito(x, y) {
    if (x.descripcion < y.descripcion) { return -1; }
    if (x.descripcion > y.descripcion) { return 1; }
    return 0;
}

function setear_depositos() {
    id_articulo = $('#cmb_articulo').val();
    //aux = "index.php?r=sds_stk_entrega_item/get_combo_deposito&id_articulo=" + id_articulo;
    aux = "index.php?r=sds_view_stock_detalle/get_deposito&id_articulo=" + id_articulo;
    $.post(aux, function(data) {
        data = data.sort(SortDeposito);
        var option = '';
        data.forEach(function(deposito) {
            option += '<option value="' + deposito.iddeposito + '">' + deposito.descripcion + '</option>'
        });
        $('#cmb_deposito_origen').html(option).trigger('change');
        /*
        if(dato_update!=null){
            $('#cmb_deposito_origen').val(dato_update);
            input_aux = $('#input_aux_id_recepcion_expediente').val();
            setear_expedientes(input_aux);
            input_aux = $('#input_aux_destino').val();
            setear_deposito_destino(input_aux);
        }
        */
    });
}

function setear_deposito_destino(dato_update = null) {
    id_deposito_origen = $('#cmb_deposito_origen').val();
    aux = "index.php?r=sds_stk_movimiento/get_combo_deposito_destino&id_deposito_origen=" + id_deposito_origen;
    $.post(aux, function(data) {
        var option = '';
        data.forEach(function(deposito) {
            option += '<option value="' + deposito.iddeposito + '">' + deposito.descripcion + '</option>'
        });
        $('#cmb_deposito_destino').html(option).val(null).trigger('change');
        //$('#cmb_deposito_destino').html(data);
        //$('#cmb_deposito_destino').val(null);
        /*
        if (dato_update != null) {
            $('#cmb_deposito_destino').val(dato_update);
        }
        */
    });
}

function setear_expedientes(dato_update = null) {
    id_articulo = $('#cmb_articulo').val();
    id_deposito = $('#cmb_deposito_origen').val();
    $('#input_disponible').val('');
    aux = "index.php?r=sds_view_stock_detalle/get_item_recepcion&id_articulo=" + id_articulo + "&deposito=" + id_deposito;
    $.post(aux, function(data) {
        var option = '';
        data.forEach(function(e) {
            option += '<option value="' + e.idrecepcionitem + '">' + e.descripcion + '</option>'
        });
        $('#cmb_item_recepcion').html(option);
        if ($('#cmb_item_recepcion').val() != '') {
            setear_disponible();
        }
        if (dato_update != null) {
            $('#cmb_item_recepcion').val(dato_update);
        }
    });
}


function setear_disponible() {
    id_articulo = $('#cmb_articulo').val();
    id_deposito = $('#cmb_deposito_origen').val();
    id_recepcion_expediente = $('#cmb_item_recepcion').val();
    //aux = "index.php?r=sds_stk_entrega_item/get_disponibilidad_item&id_articulo=" + id_articulo + "&id_deposito=" + id_deposito + "&id_recepcion_expediente=" + id_recepcion_expediente;
    aux = "index.php?r=sds_view_stock_detalle/get_stock&id_articulo=" + id_articulo + "&deposito=" + id_deposito + "&item_recepcion=" + id_recepcion_expediente;
    $.post(aux, function(data) {
        $('#input_disponible').val(data.cantidad);
    });
}