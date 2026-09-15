(function() {
    let videoStream = null;
    const videoElem = document.getElementById('video-stream');
    const canvasElem = document.getElementById('canvas-procesamiento');
    const contenedorCamara = document.getElementById('contenedor-camara');
    const contenedorNuevas = document.getElementById('contenedor-previsualizacion-nuevas');
    const contenedorInputs = document.getElementById('contenedor-inputs-base64');
    let contadorFotosNuevas = 0;

    // 1. Cámara
    $('#btn-abrir-camara').on('click', function() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false })
                .then(function(stream) {
                    videoStream = stream;
                    videoElem.srcObject = stream;
                    $(contenedorCamara).slideDown();
                })
                .catch(function(err) {
                    Swal.fire('Error de Cámara', 'No se pudo acceder a la cámara: ' + err.message, 'error');
                });
        } else {
            Swal.fire('No Soportado', 'El navegador no soporta captura de video directa.', 'warning');
        }
    });

    $('#btn-cerrar-camara').on('click', function() {
        detenerCamara();
    });

    function detenerCamara() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            videoStream = null;
        }
        $(contenedorCamara).slideUp();
    }

    // 2. Captura desde Video
    $('#btn-capturar-foto').on('click', function() {
        if (!videoStream) return;
        const ctx = canvasElem.getContext('2d');
        canvasElem.width = videoElem.videoWidth || 1280;
        canvasElem.height = videoElem.videoHeight || 720;
        ctx.drawImage(videoElem, 0, 0, canvasElem.width, canvasElem.height);
        const base64Data = canvasElem.toDataURL('image/jpeg', 0.80);
        agregarFotoPrevisualizacion(base64Data);
    });

    // 3. Selección desde PC/Galería
    $('#input-archivo-fotos').on('change', function(e) {
        const files = e.target.files;
        if (!files || files.length === 0) return;

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(evt) {
                const imgTemp = new Image();
                imgTemp.onload = function() {
                    const ctx = canvasElem.getContext('2d');
                    let maxDim = 1600;
                    let width = imgTemp.width;
                    let height = imgTemp.height;

                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height = Math.round((height * maxDim) / width);
                            width = maxDim;
                        } else {
                            width = Math.round((width * maxDim) / height);
                            height = maxDim;
                        }
                    }

                    canvasElem.width = width;
                    canvasElem.height = height;
                    ctx.drawImage(imgTemp, 0, 0, width, height);
                    const base64Data = canvasElem.toDataURL('image/jpeg', 0.80);
                    agregarFotoPrevisualizacion(base64Data);
                };
                imgTemp.src = evt.target.result;
            };
            reader.readAsDataURL(file);
        });
        $(this).val('');
    });

    // 4. Helper de Previsualización
    function agregarFotoPrevisualizacion(base64Data) {
        contadorFotosNuevas++;
        const idItem = 'foto-nueva-' + contadorFotosNuevas;

        const htmlThumb = `
            <div class="col-xs-6 col-sm-3 col-md-2 text-center" id="${idItem}" style="margin-bottom: 10px;">
                <div class="thumbnail" style="padding: 4px; position: relative;">
                    <img src="${base64Data}" class="img-responsive" style="height: 100px; object-fit: cover; width: 100%;">
                    <button type="button" class="btn btn-danger btn-xs btn-remover-foto-nueva" data-target="${idItem}" style="position: absolute; top: 6px; right: 6px;">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        `;

        const htmlInput = `<input type="hidden" name="imagenes_base64[]" id="input-${idItem}" value="${base64Data}">`;

        $(contenedorNuevas).append(htmlThumb);
        $(contenedorInputs).append(htmlInput);
    }

    $(document).on('click', '.btn-remover-foto-nueva', function() {
        const targetId = $(this).data('target');
        $('#' + targetId).remove();
        $('#input-' + targetId).remove();
    });

    // 5. Borrado AJAX
    $(document).on('click', '.btn-eliminar-foto-existente', function() {
        const idFoto = $(this).data('id');
        const urlDelete = urlDeleteFoto + '?id=' + idFoto;

        Swal.fire({
            title: '¿Eliminar imagen?',
            text: 'Esta acción borrará la foto del servidor permanentemente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: urlDelete,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#foto-item-' + idFoto).fadeOut(300, function() { $(this).remove(); });
                            Swal.fire('Eliminada', response.message, 'success');
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                    }
                });
            }
        });
    });

})();