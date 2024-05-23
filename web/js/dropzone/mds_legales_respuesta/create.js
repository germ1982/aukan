var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
var adjuntos = [];
var adjuntos_eliminados = [];
Dropzone.autoDiscover = true;
Dropzone.options.adjuntos = {
  // camelized version of the `id`
  autoProcessQueue: true,
  method: "POST",
  paramName: "file", // The name that will be used to transfer the file
  maxFilesize: 50, // MB,
  maxFiles: 10,
  uploadMultiple: true, //if you want more than a file to be   uploaded
  addRemoveLinks: true,
  parallelUploads: 1,
  acceptedFiles:
    ".jpeg, .jpg, .png, .pdf, .xls, .xlsx, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/pdf",
  params: {
    _csrf: csrfToken,
  },
  dictDefaultMessage: "Adjunte documentos aqui",
  url: "index.php?r=mds_legales_oficio%2Fguardararchivotemporal",
  init: function () {
    let thisDropzone = this;
    let adjuntosRespuestaObservada = obtenerAdjuntosRespuestaObservada();
    $.each(adjuntosRespuestaObservada, function (key, adjunto) {
      let mockFile = {
        name: adjunto.nombre,
        path: adjunto.path,
        id: adjunto.idlegalesarchivo,
      };
      thisDropzone.files.push(mockFile); // add to files array
      thisDropzone.emit("addedfile", mockFile);
      thisDropzone.emit("complete", mockFile);
      //if (esImagen(adjunto.attachment.original_name)) thisDropzone.emit("thumbnail", mockFile, `/adjuntos/general/${adjunto.attachment.unique_name}`);
      thisDropzone.emit(
        "complete",
        $("#adjuntos .dz-remove").html(
          "<div><button class='btn btn-danger btn-sm mt-1'>Eliminar</button>"
        )
      );
      thisDropzone.emit(
        "complete",
        $("#adjuntos .dz-remove")
          .last()
          .after(
            `<a style="width: 100%" target="_blank" href="${mockFile.path}" class="btn btn-sm btn-info mt-1">Ver</a>`
          )
      );
    });
    this.on("error", function (file, response) {
      alert(response);
      this.removeFile(file);
    });
    this.on("complete", function (file) {
      $(".dz-remove").html(
        "<div><button class='btn btn-danger btn-sm mt-1'>Eliminar</button></div>"
      );
      if (file.type != "image/png") {
        //$("#adjunto-otrosdocumentos .dz-image").html("<img style='width: 128px; height: 128px' src='https://icon-library.com/images/icon-document/icon-document-3.jpg'>")
        $("#adjunto-otrosdocumentos .dz-image")
          .last()
          .first()
          .html(
            `<img style='width: 128px; height: 128px' src='https://icon-library.com/images/icon-document/icon-document-3.jpg'>`
          );
      }
    });
    this.on("success", function (file, rta) {
      let rtaFile = JSON.parse(rta);
      if (rtaFile.subido) {
        adjuntos.push(rtaFile);
        document.getElementById("adjuntos").value = JSON.stringify(adjuntos);
        //console.log(JSON.parse(document.getElementById("adjuntos").value));
      }
    }); //
    this.on("removedfile", function (file) {
      if (file.status != "error") {
        if (file.id) {
          eliminarArchivoExistente(file);
        } else {
          eliminarArchivo(file);
        }
      }
    });
  },
};
function eliminarArchivoExistente(file) {
  adjuntos_eliminados.push(file.id);
  $("#adjuntos_eliminados").val(JSON.stringify(adjuntos_eliminados));
}
function eliminarArchivo(file) {
  //Se elimina el archivo
  adjuntos.splice(
    adjuntos.findIndex((e) => e.nombre_original === file.name),
    1
  );
  $("#adjuntos").val(JSON.stringify(adjuntos));
  //console.log(JSON.parse(document.getElementById("adjuntos").value));
}
function obtenerAdjuntosRespuestaObservada() {
  if (adjuntosRespuestaObservada && adjuntosRespuestaObservada != "") {
    return JSON.parse(adjuntosRespuestaObservada);
  }
  return null;
}

//   window.onload = function() {
//       $(document).on('keypress', '.select2-search__field', function () {
//         var pills = [{id:0, text: "red"}, {id:1, text: "blue"}];
//         let string = $('.select2-search__field').val();
//         if(string.length >= 2) {
//             console.log('hola')
//             $('#profesionales').select2({
//                 data: pills
//             });
//         }
//     });
// };
