let selectedFile;
console.log(window.XLSX);
document.getElementById('input').addEventListener("change", (event) => {
    selectedFile = event.target.files[0];
    document.getElementById("estado").innerHTML = "Estado: esperando orden para realizar la importación...";
})

let data=[{
    "name":"jayanth",
    "data":"scd",
    "abc":"sdef"
}]


document.getElementById('button').addEventListener("click", () => {
    XLSX.utils.json_to_sheet(data, 'out.xlsx');
    if(selectedFile){
        document.getElementById("estado").innerHTML = "Estado: importando...";
        let fileReader = new FileReader();
        fileReader.readAsBinaryString(selectedFile);
        fileReader.onload = (event)=>{
         let data = event.target.result;
         let workbook = XLSX.read(data,{type:"binary", cellDates: true, dateNF: 'yyyy/mm/dd'});
         console.log(workbook);
         workbook.SheetNames.forEach(sheet => {
            let rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
              
            console.log(rowObject);
            varjson = JSON.stringify(rowObject,undefined,4);
            document.getElementById("estado").innerHTML = "Estado: JSON Generado...";
            document.getElementById("jsondata").innerHTML = varjson;
            document.getElementById("estado").innerHTML = "Estado: Procesando informacion...";
            var aux = procesar_info(varjson);
            document.getElementById("jsondata").innerHTML = aux;
            document.getElementById("estado").innerHTML = "Estado: Proceso Finalizado...";

            
              
              //EnviarJsonExcellaPhp(varjson);
              
              
         });
        }
    }
});

function procesar_info(datos)
    {
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
        var aux = "";

        $.each(JSON.parse(datos),function(ind,elem){

            legajo = elem[columna_Legajo];
            nombre = elem[columna_Apellido_y_Nombre];
            idcontacto = getIdContacto(legajo);
            //aux = aux + nombre;
            aux = aux + "idcontacto: " + idcontacto  + " Nombre: " + nombre + "legajo: " + legajo + '<br>';
            
        });
        return aux;
    }

function getIdContacto(legajo) //modificar aca
    {
        var parametros = {
            "r": "mds_org_contacto/get_id_contacto_por_legajo",
            "legajo": legajo,
            
        };
        $.ajax({
            data: parametros, //datos que se envian a traves de ajax
            url: "../../index.php", //php que recibe la peticion
            type: 'get', 
            success: function(response) { //aca recibe el json del php que guarda o dice si ya existia
                
                console.log(response);
                return reponse;
            }
        });

    }


/* function EnviarJsonExcellaPhp(varjson) //modificar aca
			{
                idusuariocarga = $('#VarIdUsuarioCarga').val();

				var parametros = {

                    "VarJson": varjson,
                    "VarIdUsuarioCarga":idusuariocarga,         

				};
                //alert(varjson);
				$.ajax({
					data: parametros, //datos que se envian a traves de ajax
					url: 'mds_org_licencia_add_exell_info.php', //php que recibe la peticion
					type: 'post', //método de envio
                    //beforeSend: function() {},
                    
					success: function(response) { //aca recibe el json del php que guarda o dice si ya existia
                        
						var obj = jQuery.parseJSON(response) //parseo el json
                        anuncio_log = obj.anuncio_log;
						if (anuncio_log) 
							{
                                anuncio_log = "   LOG DE PROCESO DE IMPORTACIÓN (imprimir: ctrl + p) :\n" + anuncio_log;
                                document.getElementById("jsondata").innerHTML = anuncio_log;
                                document.getElementById("estado").innerHTML = "Estado: fin de importación...";
							}
					}
				});

			} */