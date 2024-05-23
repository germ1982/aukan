var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

Dropzone.autoDiscover = true;
Dropzone.options.adjuntoComprobante = {
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
  dictDefaultMessage: "Adjunte comprobante aquí",
  url: "index.php?r=mds_legales_oficio%2Fguardararchivotemporal",
  init: function () {
    this.on("error", function (file, response) {
      // $(file.previewElement).find('.dz-error-message').text(response);
      //console.log(file);
      alert(response);
      this.removeFile(file, "errorDrop");
    });
    this.on("complete", function (file) {
      document.getElementById("btn-subir-archivo").disabled = false;
      $(".dz-remove").html(
        "<div><button class='btn btn-danger btn-sm mt-1'>Eliminar</button></div>"
      );
      if (file.type != "image/png") {
        $("#adjunto_comprobante .dz-image")
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
        //document.getElementById("comprobante").value = rtaFile.temp;
        document.getElementById("comprobante").value = JSON.stringify(rtaFile);
      }
    }); //
    this.on("removedfile", function (file, error) {
      /*Si no es un error de validcion, no se limpia el campo */
      if (error == "errorDrop") {
        document.getElementById("comprobante").value = null;
      }
      document.getElementById("btn-subir-archivo").disabled = true;
      //console.log(typeof document.getElementById("comprobante").value);
    });
  },
};

var notas = [];
var adjuntos_eliminados = [];
Dropzone.options.nota = {
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
  dictDefaultMessage: "Adjunte nueva Nota aquí",
  url: "index.php?r=mds_legales_oficio%2Fguardararchivotemporal",
  init: function () {
    let thisDropzone = this;
    this.on("error", function (file, response) {
      // $(file.previewElement).find('.dz-error-message').text(response);
      //console.log(file);
      alert(response);
      this.removeFile(file, "errorDrop");
    });
    this.on("complete", function (file) {
      $(".dz-remove").html(
        "<div><button class='btn btn-danger btn-sm mt-1'>Eliminar</button></div>"
      );
      if (file.type != "image/png") {
        $("#nota .dz-image")
          .last()
          .first()
          .html(
            `<img style='width: 128px; height: 128px' src='https://icon-library.com/images/icon-document/icon-document-3.jpg'>`
          );
        //$("#adjunto-documentacion .dz-image").html("<img style='width: 128px; height: 128px' src='https://icon-library.com/images/icon-document/icon-document-3.jpg'>")
      }
      document.getElementById("btn-subir-nota").disabled = false;
    });
    this.on("success", function (file, rta) {
      let rtaFile = JSON.parse(rta);
      if (rtaFile.subido) {
        //document.getElementById("comprobante").value = rtaFile.temp;
        document.getElementById("nota").value = JSON.stringify(rtaFile);
      }
    }); //
    this.on("removedfile", function (file, error) {
      /*Si no es un error de validcion, no se limpia el campo */
      if (error == "errorDrop") {
        document.getElementById("nota").value = null;
      }
      document.getElementById("btn-subir-nota").disabled = true;
      //console.log(typeof document.getElementById("comprobante").value);
    });
  },
};
