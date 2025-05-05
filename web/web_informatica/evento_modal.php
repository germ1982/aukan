<style>
      /* Fondo oscuro del modal: ocupa toda la pantalla y se muestra al activar la clase "show" */
      .modal-backdrop {
            position: fixed !important;
            /* Posición fija respecto a la ventana */
            top: 0 !important;
            /* Sin separación desde arriba */
            left: 0 !important;
            /* Sin separación desde la izquierda */
            width: 100% !important;
            /* Ocupa el 100% del ancho de la ventana */
            height: 100% !important;
            /* Ocupa el 100% de la altura de la ventana */
            background-color: rgba(0, 0, 0, 0.8) !important;
            /* Fondo negro casi opaco */
            display: none !important;
            /* Oculto por defecto */
            justify-content: center !important;
            /* Centra horizontalmente el contenido */
            align-items: center !important;
            /* Centra verticalmente el contenido */
            z-index: 1000 !important;
            /* Se muestra sobre otros elementos */
            opacity: 0 !important;
            /* Comienza completamente transparente */
            transition: opacity 0.3s ease !important;
            /* Transición suave para el cambio de opacidad */
      }

      /* Cuando se activa el modal (se le agrega la clase "show"), se muestra y se vuelve opaco */
      .modal-backdrop.show {
            display: flex !important;
            /* Se muestra usando flex */
            opacity: 1 !important;
            /* Opacidad completa */
            transition: opacity 0.3s ease !important;
      }

      /* Estilo del contenido interno del modal */
      .modal-content {
            color: #777 !important;
            background: white !important;
            /* Fondo blanco */
            /* padding: 20px !important; */
            /* Espacio interno */
            border-radius: 10px !important;
            /* Bordes redondeados */
            max-width: 100%!important;
            /* Ancho máximo de 600px */
            width: 90% !important;
            /* 90% del ancho disponible */
            max-height: 100% !important;
            /* Altura máxima del 90% */
            overflow-y: auto !important;
            /* Si el contenido es alto, se activa scroll vertical */
            box-shadow: 0 0 5px #87B867, 0 0 5px #87B867, 0 0 20px #87B867, 0 0 10px #87B867, 0 0 10px #87B867 !important;
            /* Sombra para resaltar el modal */

      }

      /* Botón de cerrar alineado con Flexbox */
      .modal-header {
            display: flex !important;
            /* Activamos Flexbox */
            justify-content: space-between !important;
            /* Espaciamos entre título y botón */
            align-items: center !important;
            /* Centramos verticalmente */
            margin-bottom: 15px !important;
            background-color: #2b3e4c;
            color: #f4dfb9;

            padding: 8px 15px !important;

      }

      .neon {

            text-shadow:
                  0 0 2px #5f913d,
                  0 0 4px #5f913d,
                  0 0 8px #5f913d,
                  0 0 10px #5f913d,
                  0 0 10px #5f913d,
                  0 0 0px #5f913d,
                  0 0 0px #5f913d,
                  0 0 0px #5f913d;
      }

      .modal-header h2 {
            margin: 0 !important;
            /* margin-right: 100%!important;
        padding-right: 71%!important; */
            /* Elimina márgenes que desplacen el título */
      }

      .close-btn {
            cursor: pointer !important;
            color: #F4DFB9 !important;
            opacity: 1 !important;
            font-size: 1.5rem !important;
            /* Tamaño ajustado */
            line-height: 1 !important;
            /* Para centrar verticalmente */
            position: relative !important;
            width: 30px !important;
            height: 30px !important;
            display: flex !important;
            align-items: center !important;
            /* Centra vertical */
            justify-content: center !important;
            /* Centra horizontal */
            border-radius: 50% !important;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 60%, rgba(0, 0, 0, 0) 70%) !important;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5), 0 0 20px rgba(255, 255, 255, 0.3) !important;
            transition: background 0.3s, box-shadow 0.3s !important;
            padding-bottom: 1px;
      }

      .close-btn:hover {
            background: radial-gradient(circle, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.4) 60%, rgba(0, 0, 0, 0) 70%) !important;
            box-shadow: 0 0 30px rgba(255, 255, 255, 1.0), 0 0 60px rgba(255, 255, 255, 0.8) !important;
            color: greenyellow !important;
      }

      .body_modal_evento {
            padding: 0px 25px !important;
            max-height: 80vh; /* o el alto que quieras, relativo a la ventana */
    overflow-y: auto;
    padding-right: 10px; /* opcional, para evitar que tape el contenido el scroll */
      }

      .body_modal_evento p {
            text-align: justify !important;
            word-break: break-word !important;
            white-space: pre-wrap !important;
            
      }

      .carousel_modal {
            width: 70%;
            left: 15%;
      }
      .carousel-inner-modal img {
            width: 100%!important;
            height: 100%!important;
            object-fit: contain;
            border-radius: 1px;
            position: relative;
            z-index: 1;
      }
      .titulo_modal {
            border-bottom: 2px solid #ccc;
            text-align: center !important;
            padding-bottom: 15px;
      }
      .linea_modal{
            border-bottom: 2px solid #ccc;
            
            padding-bottom: 4px;
            margin-bottom: 15px;
            }

      .contenido_modal{
            font-size: 12px;
            padding: 0% 10px;
      }
</style>
</head>

<body>
      <!-- Modal personalizado (lo ideal es que esté en un archivo separado, por ejemplo, evento_modal.php) -->
      <div id="eventoModal" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="modal-content">
                  <div class="modal-header panel-title neon">

                        <div class="col-md-11">
                              <h3 id="modalPreTitle">PRe Título del Modal</h3>
                        </div>
                        <div class="col-md-1">
                              <span id="closeModalBtn" class="close-btn">&times;</span>
                        </div>

                  </div>
                  <div class="body_modal_evento">

                        <h2 class="titulo_modal" id="modalTitle">Título del Modal</h2>
                        <!-- Aquí iría el contenido dinámico, como un carrusel o descripción -->

                        <div id="carouselModalFotos" class="carousel carousel_modal slide" data-bs-ride="carousel">

                              <div class="carousel-indicators" id="div_carousel-indicators">
                              </div>

                              <div class="carousel-inner carousel-inner-modal" id="div_carousel-inner">
                              </div>

                              <button class="carousel-control-prev" type="button" data-bs-target="#carouselModalFotos" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                              </button>
                              <button class="carousel-control-next" type="button" data-bs-target="#carouselModalFotos" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                              </button>
                        </div>

                        <div class="linea_modal"></div>
                        <p class="contenido_modal" id="modalDescripcion">Contenido del modal</p>


                  </div>
            </div>
      </div>

      <script>
            // Esperamos a que el DOM esté cargado
            document.addEventListener("DOMContentLoaded", () => {
                  // Obtenemos el modal y el botón de cerrar mediante sus IDs
                  const modal = document.getElementById("eventoModal");
                  const closeBtn = document.getElementById("closeModalBtn");

                  // Al hacer clic en el botón de cerrar, se elimina la clase "show" del modal
                  closeBtn.addEventListener("click", () => modal.classList.remove("show"));

                  // Si se hace clic fuera del contenido del modal, se cierra
                  modal.addEventListener("click", e => {
                        if (e.target === modal) modal.classList.remove("show");
                  });

                  // Cierra el modal al presionar la tecla Escape
                  document.addEventListener("keydown", e => {
                        if (e.key === "Escape") modal.classList.remove("show");
                  });
            });

            // Función para abrir el modal y cambiar su contenido dinámicamente
            function abrirModalEvento(titulo, descripcion, fotos, tipo_evento, fecha) {
                  const modal = document.getElementById("eventoModal");
                  document.getElementById("modalPreTitle").innerText = tipo_evento + ' ' + fecha;
                  document.getElementById("modalTitle").innerText = titulo;
                  document.getElementById("modalDescripcion").innerText = descripcion;
                  // Aquí podrías construir un carrusel dinámico usando 'fotos', por ejemplo.
                  // Por ahora, dejamos la función básica.
                  armar_carrousel(fotos)

                  // Se añade la clase "show" para mostrar el modal con transición
                  modal.classList.add("show");
            }

            function armar_carrousel(fotos) {
                  const imageNames = fotos.split(',').map(f => f.trim()).filter(f => f !== '');

                  const indicators = document.getElementById("div_carousel-indicators");
                  const inner = document.getElementById("div_carousel-inner");

                  indicators.innerHTML = "";
                  inner.innerHTML = "";

                  imageNames.forEach((img, index) => {
                        // Indicadores (los botones de abajo)
                        const btn = document.createElement("button");
                        btn.type = "button";
                        btn.setAttribute("data-bs-target", "#carouselModalFotos");
                        btn.setAttribute("data-bs-slide-to", index);
                        if (index === 0) btn.classList.add("active");
                        indicators.appendChild(btn);

                        // Items del carrusel
                        const div = document.createElement("div");
                        div.className = "carousel-item" + (index === 0 ? " active" : "");
                        div.innerHTML = `<img src="../img/evento-fotos/${img}" class="d-block w-100" alt="Foto ${index + 1}">`;
                        inner.appendChild(div);
                  });
            }
      </script>