var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
var otros_adjuntos = [];
var adjuntos_eliminados = [];
//Dropzone.autoDiscover = false;
Dropzone.autoDiscover = true;
Dropzone.options.adjuntoDocumentacion = {
  // camelized version of the `id`
  dictMaxFilesExceeded: "No puedes adjuntar más archivos",
  autoProcessQueue: true,
  method: "POST",
  paramName: "file", // The name that will be used to transfer the file
  maxFilesize: 10, // MB,
  maxFiles: 1,
  uploadMultiple: true, //if you want more than a file to be   uploaded
  addRemoveLinks: true,
  acceptedFiles:
    ".jpeg, .jpg, .png, .pdf, .xls, .xlsx, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/pdf",
  params: {
    _csrf: csrfToken,
  },
  dictDefaultMessage: "Adjunte requerimiento aquí",
  url: "index.php?r=mds_legales_oficio%2Fguardararchivotemporal",
  init: function () {
    let thisDropzone = this;
    let adjunto_oficio = obtenerAdjuntoOficio();
    if (adjunto_oficio?.length) {
      //console.log(otros_adjuntos);
      var mockFile = {
        name: adjunto_oficio[0].nombre,
        path: adjunto_oficio[0].path,
        id: adjunto_oficio[0].idlegalesarchivo,
      };
      //console.log(mockFile);
      thisDropzone.files.push(mockFile);
      thisDropzone.emit("addedfile", mockFile);
      thisDropzone.emit("complete", mockFile);
      thisDropzone.emit(
        "complete",
        $(".dz-remove").html(
          "<div><button class='btn btn-danger btn-sm mt-1'>Eliminar</button>"
        )
      );
      thisDropzone.emit(
        "complete",
        $("#adjunto-documentacion .dz-remove")
          .last()
          .after(
            `<a target="_blank" href="${mockFile.path}" class="btn btn-info" style="width: 100%">Ver</a>`
          )
      );
    }

    this.on("addedfile", function (file) {
      if (this.files.length > 1) {
        this.removeAllFiles();
        this.addFile(file);
      }
    });

    this.on("error", function (file, response) {
      // $(file.previewElement).find('.dz-error-message').text(response);
      //console.log(file);
      alert(response);
      this.removeFile(file);
    });
    this.on("complete", function (file) {
      $(".dz-remove").html(
        "<div><button class='btn btn-danger btn-sm mt-1'>Eliminar</button></div>"
      );
      if (file.type != "image/png") {
        $("#adjuntos-datos-personales .dz-image")
          .last()
          .first()
          .html(
            `<img style='width: 128px; height: 128px' src='https://icon-library.com/images/icon-document/icon-document-3.jpg'>`
          );
        //$("#adjunto-documentacion .dz-image").html("<img style='width: 128px; height: 128px' src='https://icon-library.com/images/icon-document/icon-document-3.jpg'>")
      }
    });
    this.on("success", function (file, rta) {
      let rtaFile = JSON.parse(rta);
      if (rtaFile.subido) {
        //document.getElementById("archivo_oficio").value = rtaFile.temp;
        document.getElementById("archivo_oficio").value =
          JSON.stringify(rtaFile);
      }
    }); //
    this.on("removedfile", function (file, error) {
      /*Si no es un error de validcion, no se limpia el campo */
      if (file.status == "error") {
        document.getElementById("archivo_oficio").value = null;
      }

      if (file.id) {
        //file.tipo = 'oficio';
        eliminarArchivoExistente(file);
      } else {
        eliminarArchivo(file);
      }
    });
  },
};
Dropzone.options.adjuntoOtrosdocumentos = {
  // camelized version of the `id`
  autoProcessQueue: true,
  method: "POST",
  paramName: "file", // The name that will be used to transfer the file
  maxFilesize: 50, // MB,
  maxFiles: 30, // Cantidad
  uploadMultiple: true, //if you want more than a file to be   uploaded
  addRemoveLinks: true,
  parallelUploads: 1,
  acceptedFiles:
    ".jpeg, .jpg, .png, .pdf, .xls, .xlsx, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/pdf",
  params: {
    _csrf: csrfToken,
  },
  dictDefaultMessage: "Adjunte documentos complementarios aqui",
  url: "index.php?r=mds_legales_oficio%2Fguardararchivotemporal",
  init: function () {
    let thisDropzone = this;
    let adjuntosPrecargados = obtenerOtrosAdjuntosOficio();
    let descripcion = "";
    $.each(adjuntosPrecargados, function (key, adjunto) {
      var mockFile = {
        name: adjunto.nombre,
        path: adjunto.path,
        id: adjunto.idlegalesarchivo,
        objeto: adjunto.objeto,
        tipoAdjunto: adjunto.tipoAdjunto
      };
      thisDropzone.files.push(mockFile); // add to files array
      thisDropzone.emit("addedfile", mockFile);
      thisDropzone.emit("complete", mockFile);
      //if (esImagen(adjunto.attachment.original_name)) thisDropzone.emit("thumbnail", mockFile, `/adjuntos/general/${adjunto.attachment.unique_name}`);
      thisDropzone.emit(
        "complete",
        $("#adjunto-otrosdocumentos .dz-remove").html(
          "<div><button class='btn btn-danger btn-sm mt-1'>Eliminar</button>"
        )
      );
      descripcion = mockFile.objeto === 'mds_certificacion' ? `<span style="width: 100%;text-align: center">${mockFile.tipoAdjunto}</span>` : '';
      thisDropzone.emit(
        "complete",
        $("#adjunto-otrosdocumentos .dz-remove")
          .last()
          .after(
            `<a style="width: 100%" target="_blank" href="${mockFile.path}" class="btn btn-sm btn-info mt-1">Ver</a>`
          )
          .after(descripcion)
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
      
      if($("#TIPO_ADJUNTO")?.length){
        rtaFile["tipo"] = $("#TIPO_ADJUNTO").val(); //guardo el idadjunto (Certificacion)
        $(".dz-button").remove();
      }

      if (rtaFile.subido) {
        otros_adjuntos.push(rtaFile);
        document.getElementById("otros_adjuntos").value =
          JSON.stringify(otros_adjuntos);
        //console.log(JSON.parse(document.getElementById("otros_adjuntos").value));
      }
    }); //
    this.on("removedfile", function (file) {
      /*Si no es un error de validacion\error server, se utiliza el comportamiento del borrado a traves del boton */
      if (file.status != "error") {
        if (file.id) {
          //file.tipo = 'otros';
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
  let archivoOficio = document.getElementById("archivo_oficio")?.value;
  let archivoUnico = {};
  if (archivoOficio) {
    archivoUnico = JSON.parse(archivoOficio);
  }

  // if por si es archivo adjunto unico
  if (archivoUnico?.nombre_original === file?.name) {
    archivoUnico = {};
    $("#archivo_oficio").val(JSON.stringify(archivoUnico));
  }

  //Se elimina el archivo
  otros_adjuntos.splice(
    otros_adjuntos.findIndex((e) => e.nombre_original === file.name),
    1
  );
  $("#otros_adjuntos").val(JSON.stringify(otros_adjuntos));
  //console.log(JSON.parse(document.getElementById("otros_adjuntos").value));
}
function obtenerAdjuntoOficio() {
  if (archivo_oficio != "") {
    return JSON.parse(archivo_oficio);
  }
  return null;
}
function obtenerOtrosAdjuntosOficio() {
  if (adjuntos_oficio != "") {
    return JSON.parse(adjuntos_oficio);
  }
  return null;
}
function esImagen(archivo) {
  if (/\.(jpe?g|png|gif|bmp)$/i.test(archivo)) {
    return true;
  }
  return false;
}
