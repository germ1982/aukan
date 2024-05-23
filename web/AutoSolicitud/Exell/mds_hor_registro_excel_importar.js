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
    //alert('Llega');
    XLSX.utils.json_to_sheet(data, 'out.xlsx');
    if(selectedFile){
        document.getElementById("estado").innerHTML = "Estado: importando...";
        let fileReader = new FileReader();
        fileReader.readAsBinaryString(selectedFile);
        fileReader.onload = (event)=>{
         let data = event.target.result;
         let workbook = XLSX.read(data,{type:"binary", cellDates: true, dateNF: 'yyyy/mm/dd'});
         console.log(workbook);
         sheet_hoja=1;
         workbook.SheetNames.forEach(sheet => {
              let rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
              
              console.log(rowObject);
              varjson = JSON.stringify(rowObject,undefined,4);

              if(sheet_hoja == 3)
                {
                    //alert(varjson);
                    EnviarJsonExcellaPhp(varjson);
                    //document.getElementById("jsondata").innerHTML = varjson;
                }

                sheet_hoja++;
              
         });
         
        }
    }
    else
        {
            document.getElementById("estado").innerHTML = "Antes de importar debe seleccionar un archivo...";
        }
});



function EnviarJsonExcellaPhp(varjson) //modificar aca
			{

				var parametros = {

                    "VarJson": varjson,      

				};
                //alert(varjson);
				$.ajax({
					data: parametros, //datos que se envian a traves de ajax
					url: 'AutoSolicitud/Exell/mds_hor_registro_excel_importar.php', //php que recibe la peticion
					type: 'post', //método de envio
                    //beforeSend: function() {},
                    
					success: function(response) { //aca recibe el json del php que guarda o dice si ya existia
                        
						var obj = jQuery.parseJSON(response) //parseo el json
                        anuncio_log = obj.anuncio_log;
						if (anuncio_log) 
							{
                                //alert(anuncio_log);
                                anuncio_log = "LOG DE PROCESO DE IMPORTACIÓN:\n" + anuncio_log;
                                document.getElementById("jsondata").innerHTML = anuncio_log;
                                document.getElementById("estado").innerHTML = "Estado: fin de importación...";
							}
					}
				});

			}