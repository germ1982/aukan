<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Progress;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_licencia */
/* @var $form yii\widgets\ActiveForm */
?>


<script>
    gif = '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';
    let selectedFile;
    var resultado = new Array();

    document.getElementById('input').addEventListener("change", (event) => {
        selectedFile = event.target.files[0];
        document.getElementById("estado").innerHTML = "Estado: esperando orden para realizar la importación...";
    })

    let data = [{
        "name": "jayanth",
        "data": "scd",
        "abc": "sdef"
    }]

    document.getElementById('button').addEventListener("click", () => { //aca espera el clic al boton de importar
        document.getElementById("jsondata").innerHTML = "";
        document.getElementById("otro_progreso").style.width = '0%';
        //alert('click');
        document.getElementById("estado").innerHTML = "Estado: importando...";
        XLSX.utils.json_to_sheet(data, 'out.xlsx');
        if (selectedFile) {
            document.getElementById("estado").innerHTML = "Estado: Importando datos de excel a JSON " + '<img src="data:image/gif;base64,' + gif + '" alt=""  height="10px" />';
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(selectedFile);
            fileReader.onload = (event) => {
                let data = event.target.result;
                let workbook = XLSX.read(data, {
                    type: "binary",
                    cellDates: true,
                    dateNF: 'yyyy/mm/dd'
                });
                let rows = new Array();
                let sheetObject = null;
                workbook.SheetNames.forEach(sheet => {
                    sheetObject = workbook.Sheets[sheet];
                    let rowObject = XLSX.utils.sheet_to_row_object_array(sheetObject);
                    varjson = JSON.stringify(rowObject, undefined, 4);
                    rows.push(JSON.parse(varjson));
                });
                var resultado_html = "";
                var index_fila = 0;
                document.getElementById("estado").innerHTML = "Estado: Procesando informacion...";
                rows.forEach(sheet => {
                    sheet.forEach(fila => {
                        procesarFila(sheetObject, fila);
                        index_fila++;
                    });
                });
                document.getElementById("jsondata").innerHTML = varjson;                
                //var aux = procesar_info(varjson);
                aux = procesar_licencias();
                document.getElementById("jsondata").innerHTML = aux;
                marcar_final();
            }
        }
    });

    function procesar_licencias() {
        var aux
        var parametros = {
            "datos": JSON.stringify(resultado),
        };
        $.ajax({
            url: "index.php?r=mds_hor_licencia/procesar_licencias", //php que recibe la peticion
            data: parametros,
            type: 'post',
            async: false,
            success: function(data) { //aca recibe el json del php que guarda o dice si ya existia
                console.log('idlicencia: ' + data);
                aux = data;
            }
        });
        return aux;
    }

    function get_header_row(sheetObject) {
        var headers = [];
        var range = XLSX.utils.decode_range(sheetObject['!ref']);
        var C, R = range.s.r; /* start in the first row */
        /* walk every column in the range */
        for (C = range.s.c; C <= range.e.c; ++C) {
            var cell = sheetObject[XLSX.utils.encode_cell({
                c: C,
                r: R
            })] /* find the cell in the first row */

            var hdr = "SIN_NOMBRE_" + C; // <-- replace with your desired default 
            if (cell && cell.t) hdr = XLSX.utils.format_cell(cell);

            headers.push(hdr);
        }
        return headers;
    }

    function alphaToNum(alpha) {
        var i = 0,
            num = 0,
            len = alpha.length;

        for (; i < len; i++) {
            num = num * 26 + alpha.charCodeAt(i) - 0x40;
        }
        return num - 1;
    }

    function procesarFila(sheetObject, fila) {
        var headers = get_header_row(sheetObject);
        //Serv.(A) PSU Hist.(B) F.Ingreso(C) Legajo(D) Apellido y Nombre(E) T.Lic.(F) Descripcion(G)
        //Año(H) Desde(I) Hasta(J)	Dias Lab(K)	Cant.(L) Estado(M) Observ.(N) Liq.(O)
        var legajo = fila[headers[alphaToNum('D')]];
        var apenom = fila[headers[alphaToNum('E')]];
        var t_lic = fila[headers[alphaToNum('F')]];
        var descripcion = fila[headers[alphaToNum('G')]];
        var desde = fila[headers[alphaToNum('I')]];
        var hasta = fila[headers[alphaToNum('J')]];
        var cant = fila[headers[alphaToNum('L')]];
        var observ = fila[headers[alphaToNum('N')]];
        var liq = fila[headers[alphaToNum('O')]];
        var licencia_elto = {
            'legajo': legajo,
            'apenom': apenom,
            't_lic': t_lic,
            'descripcion': descripcion,
            'desde': desde,
            'hasta': hasta,
            'cant': cant,
            'observ': observ,
            'liq': liq,
        };
        resultado.push(licencia_elto);
    }

    /* function procesar_info(datos) {
        var columna_Serv = "Serv.";
        var columna_PSU_Hist = "PSU Hist.";
        var columna_F_Ingreso = "F.Ingreso";
        var columna_Legajo = "Legajo";
        var columna_Apellido_y_Nombre = "Apellido y Nombre";
        var columna_T_Lic = "T.Lic.";
        var columna_Descripcion = "Descripcion";
        var columna_Año = "Año";
        var columna_Desde = "Desde";
        var columna_Hasta = "Hasta";
        var columna_Dias_Lab = "Dias Lab";
        var columna_Cant = "Cant.";
        var columna_Estado = "__EMPTY_11";
        var columna_Observ = "Observ.";
        var columna_Liq = "Liq.";

        var idcontacto = "";
        var anuncio_log = "";
        var fondo = "<div>";
        var cant_filas = JSON.parse(datos).length;
        var i = 1;
        porcentaje = 0

        $.each(JSON.parse(datos), function(ind, elem) {

            legajo = elem[columna_Legajo];
            nombre = elem[columna_Apellido_y_Nombre];
            nombre = nombre.trim();

            porcentaje = porcentaje + (100 / cant_filas);
            marcar_proceso(i, porcentaje, cant_filas, nombre);
            i++;

            idcontacto = getIdContacto(legajo, i, porcentaje, cant_filas);

            anuncio_log = anuncio_log + fondo;
            if (fondo == "<div>") {
                fondo = "<div style='background-color:#D8D8D8;'>";
            } else {
                fondo = "<div>";
            }

            //-----------------------------------------------------------------------------------------
            if (idcontacto > 0) {
                ban_fechas = 0;
                desde = elem[columna_Desde];
                desde = desde.substring(0, 10)
                hasta = elem[columna_Hasta];
                hasta = hasta.substring(0, 10)


                //alert("desde: " + desde + "\n hasta: " +hasta + "\n detalle: " + detalle + "\n cantidad_dias: " + cantidad_dias + "\n idusuario: " + idusuario );
                ban_fechas = ValidarFechas(desde, hasta, idcontacto);

                if (ban_fechas == 0) {

                    motivo_inasistencia = elem[columna_T_Lic];
                    if (!($.isNumeric(motivo_inasistencia))) {
                        motivo_inasistencia = motivo_inasistencia.trim();
                    }

                    //motivo_inasistencia = GetIdMotivoInasistencia(motivo_inasistencia);

                    descripcion = elem[columna_Descripcion];
                    descripcion = descripcion.trim();

                    observacion = elem[columna_Observ];
                    observacion = observacion.trim();

                    liquidacion = elem[columna_Liq];
                    liquidacion = liquidacion.trim();

                    detalle = descripcion + " - " + observacion + " - " + liquidacion;

                    cantidad_dias = elem[columna_Cant];
                    idusuario = ;

                    aux = GuardarLicencia(desde, hasta, detalle, idcontacto, cantidad_dias, idusuario, motivo_inasistencia, descripcion);
                    anuncio_log = anuncio_log + "<p style='color:#017E25'>   Se ha guardado la licencia de: " + nombre + "<br>   Legajo: " + legajo + "<br>   Guardado con Id: " + aux + "</p>";
                } else {
                    anuncio_log = anuncio_log + " <p style='color:red'>   No se ha guardado la licencia de: " + nombre + " <br>   Legajo: " + legajo + "<br>   Razón: ya hay licencias cargadas en las fechas solicitadas</p>";
                }

            } else {
                anuncio_log = anuncio_log + "<p style='color:red'>   No se ha guardado la licencia de: " + nombre + " <br>   Legajo: " + legajo + "<br>   Razón: No existe en mds_org_contacto</p>";
            }
            //anuncio_log = anuncio_log + "idcontacto: " + idcontacto  + " Nombre: " + nombre + "legajo: " + legajo + '<br>';
            //-----------------------------------------------------------------------------------------
            anuncio_log = anuncio_log + "</div>";

        });
        return anuncio_log;

    } */
/* 
    function GuardarLicencia(desde, hasta, detalle, idcontacto, cantidad_dias, idusuario, motivo_inasistencia, descripcion) {
        var aux
        $.ajax({
            url: "index.php?r=mds_hor_licencia/create_ext&desde=" + desde + "&hasta=" + hasta + "&detalle=" + detalle + "&idcontacto=" + idcontacto + "&cantidad_dias=" + cantidad_dias + "&idusuario=" + idusuario + "&motivoinasistencia=" + motivo_inasistencia + "&descripcion=" + descripcion, //php que recibe la peticion
            type: 'post',
            async: false,
            success: function(data) { //aca recibe el json del php que guarda o dice si ya existia
                console.log('idlicencia: ' + data);
                aux = data;
            }
        });
        return aux;
    }
 */
   /*  function ValidarFechas(desde, hasta, idcontacto) {
        var aux
        $.ajax({
            url: "index.php?r=mds_hor_licencia/validar_fechas&desde=" + desde + "&hasta=" + hasta + "&idcontacto=" + idcontacto, //php que recibe la peticion
            type: 'post',
            async: false,
            success: function(data) { //aca recibe el json del php que guarda o dice si ya existia
                console.log('ban de validar fechas: ' + data);
                aux = data;
            }
        });
        //alert("idcontacto: " + idcontacto + "\n ban: " + aux) 
        return aux;
    } */



/*     function getIdContacto(legajo, i, porcentaje, cant_filas) {
        var aux
        $.ajax({
            url: "index.php?r=mds_org_contacto/get_id_contacto_por_legajo&legajo=" + legajo, //php que recibe la peticion
            type: 'post',
            async: false,
            success: function(data) { //aca recibe el json del php que guarda o dice si ya existia
                console.log('idcontacto: ' + data);
                aux = data;
            }
        });
        return aux;
    } */

    async function marcar_final() {
        const result = await resolveonesecond();
        document.getElementById("estado").innerHTML = "Estado: Proceso Finalizado...";
    }
    async function marcar_proceso(i, porcentaje, cant_filas, nombre) {
        const result = await resolveonesecond();
        document.getElementById("estado").innerHTML = "Estado: Procesando fila " + i + " de " + cant_filas + " de " + nombre;
        document.getElementById("otro_progreso").style.width = porcentaje + '%';
    }

    function resolveonesecond() {
        return new Promise(resolve => {
            setTimeout(() => {
                resolve('resolved');
            }, 500);
        });
    }
</script>
<div class="mds-hor-licencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-md-12'>

            <?= html::fileInput(
                'input',
                'lalalla',
                [
                    'id' => 'input',
                    'class' => 'btn btn-default pull-left',
                    'accept' => '.xls,.xlsx',
                ]

            ); ?>
            <?= Html::button('Importar', ['id' => 'button', 'class' => 'btn btn-default pull-left']); ?>
            <?= Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]); ?>

        </div>
    </div>
    <br>
    <div class="row">
        <div class='col-md-12'>
            <pre id="estado">Estado: esperando seleccion de archivo</pre>
        </div>
    </div>
    <div class="row">
        <div class='col-md-12'>
            <?= Progress::widget([
                'percent' => 0,
                'label' => '',
                'barOptions' => ['class' => 'progress-bar-success', 'id' => 'otro_progreso'],
                'options' => ['class' => 'active progress-striped', 'id' => 'progreso']
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class='col-md-12'>
            <pre id="jsondata"></pre>
        </div>
    </div>



    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>