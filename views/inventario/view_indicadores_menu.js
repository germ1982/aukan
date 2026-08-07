let historial = []
let mapaPadres = new Map()

// Función auxiliar para armar la lista ordenada de equipamiento
function generarListaTotales(totales) {
     if (!totales) return ""

     // Configuración de etiquetas e íconos por categoría
     const categorias = [
          { clave: "empleados", etiqueta: "Empleados", icono: "👥" },
          { clave: "cpu", etiqueta: "CPU", icono: "💻" },
          { clave: "monitor", etiqueta: "Monitor", icono: "🖥️" },
          { clave: "teclado", etiqueta: "Teclado", icono: "⌨️" },
          { clave: "mouse", etiqueta: "Mouse", icono: "🖱️" },
          { clave: "impresora", etiqueta: "Impresora", icono: "🖨️" },
          { clave: "scanner", etiqueta: "Scanner", icono: "📄" },
          { clave: "router", etiqueta: "Router", icono: "🌐" },
          { clave: "telefono", etiqueta: "Teléfono", icono: "📞" },
          { clave: "celular", etiqueta: "Celular", icono: "📱" },
     ]

     let html = `
     <table style="width: 100%; border-collapse: collapse; margin: 12px 0; font-size: 0.8rem;">
          <tbody>`

     categorias.forEach((cat) => {
          const cantidad = totales[cat.clave] || 0
          // Si la cantidad es mayor a 0 destacamos un toque el número
          const estiloNumero = cantidad > 0 ? "color: #00ffcc; font-weight: bold;" : "color: #88a0b5;"

          html += `
               <tr style="border-bottom: 1px solid rgba(0, 191, 255, 0.15);">
                    <td style="padding: 6px 8px; text-align: left; color: #e0f0ff;">
                         <span style="margin-right: 8px;">${cat.icono}</span>${cat.etiqueta}
                    </td>
                    <td style="padding: 6px 8px; text-align: right; ${estiloNumero}">
                         ${cantidad}
                    </td>
               </tr>`
     })

     html += `
          </tbody>
     </table>`

     return html
}

function renderizarNivel(nodo) {
     const container = document.querySelector(".dashboard-body-container")
     const tarjetasActuales = container.querySelectorAll(".data-card")

     if (typeof $ !== "undefined" && $("#buscador-sectores").data("select2")) {
          $("#buscador-sectores").val(null).trigger("change.select2")
     }

     if (tarjetasActuales.length > 0) {
          tarjetasActuales.forEach((c) => c.classList.add("card-out"))

          setTimeout(() => {
               ejecutarRender(nodo, container)
          }, 300)
     } else {
          ejecutarRender(nodo, container)
     }
}

function ejecutarRender(nodo, container) {
     actualizarBreadcrumb(nodo)
     container.innerHTML = ""

     // Gestor de la toolbar
     const toolbar = document.getElementById("toolbar-actions")
     const contenedorPadre = document.getElementById("contenedor-padre-superior")

     if (toolbar) {
          let btnVolverGlobal = toolbar.querySelector(".btn-volver-custom")

          if (historial.length > 0) {
               if (!btnVolverGlobal) {
                    btnVolverGlobal = document.createElement("button")
                    btnVolverGlobal.className = "btn-volver-custom"
                    btnVolverGlobal.innerText = "⬅ Ir al nivel superior"
                    btnVolverGlobal.addEventListener("click", () => {
                         const anterior = historial.pop()
                         renderizarNivel(historial.length === 0 ? historial[0] || anterior : historial[historial.length - 1])
                    })
                    toolbar.insertBefore(btnVolverGlobal, toolbar.firstChild)
               }

               if (contenedorPadre) {
                    const nodoPadre = historial[historial.length - 1]
                    contenedorPadre.innerHTML = `<div class="contenedor-padre-banner"> <span>${nodoPadre.descripcion}</span></div>`
               }
          } else {
               // SI ESTAMOS EN LA RAÍZ: Limpiamos botón y banner
               if (btnVolverGlobal) btnVolverGlobal.remove()
               if (contenedorPadre) contenedorPadre.innerHTML = "" // <-- Limpieza obligatoria
          }
     }

     // 1. Tarjeta Única Inicial (Raíz)
     if (historial.length === 0) {
          const cardPadreUnico = document.createElement("div")
          cardPadreUnico.className = "data-card"
          cardPadreUnico.style.cursor = "pointer"
          cardPadreUnico.style.margin = "auto"

          cardPadreUnico.innerHTML = `
               <h3 class="box-title" style="border-bottom: 1px solid rgba(0, 191, 255, 0.3); padding-bottom: 8px;">${nodo.descripcion}</h3>
               ${generarListaTotales(nodo.totales)}
               <p style="text-align: center; color: #a2c8ff; margin-top: auto;">Explorar Dependencias</p>
          `

          cardPadreUnico.addEventListener("click", () => {
               historial.push(nodo)
               renderizarNivel(nodo)
          })

          container.appendChild(cardPadreUnico)
          return
     }

     // 2. Tarjetas Hijas
     if (nodo.hijos && nodo.hijos.length > 0) {
          nodo.hijos.forEach((hijo) => {
               const cardHija = document.createElement("div")
               cardHija.className = "data-card"

               const tieneHijos = hijo.hijos && hijo.hijos.length > 0

               cardHija.innerHTML = `
                    <h3 class="box-title" style="border-bottom: 1px solid rgba(0, 191, 255, 0.3); padding-bottom: 8px;">${hijo.descripcion}</h3>
                    ${generarListaTotales(hijo.totales)}
                    <p style="text-align: center; color: ${tieneHijos ? "#a2c8ff" : "#666"}; margin-top: auto;">
                        ${hijo.tipo === "organismo" ? (tieneHijos ? "Explorar Dependencias" : "Organismo Sin Dispositivos") : ""}
                    </p>
               `

               if (hijo.tipo === "organismo" && tieneHijos) {
                    cardHija.style.cursor = "pointer"
                    cardHija.addEventListener("click", () => {
                         historial.push(hijo)
                         renderizarNivel(hijo)
                    })
               } else {
                    cardHija.style.opacity = "0.8"
               }

               container.appendChild(cardHija)
          })
     }
}

document.addEventListener("DOMContentLoaded", function () {
     if (window.arbolDatos) {
          if (typeof $ !== "undefined") {
               const listaSectores = procesarArbol(window.arbolDatos)
               const $select = $("#buscador-sectores")

               listaSectores.forEach((s) => {
                    $select.append(new Option(s.descripcion, s.id, false, false))
               })

               if ($.fn.select2) {
                    $select
                         .select2({
                              placeholder: "🔍 Buscar sector...",
                              allowClear: true,
                         })
                         .on("select2:select", function (e) {
                              const idSeleccionado = parseInt(e.params.data.id)
                              if (idSeleccionado) {
                                   irANodoEspecifico(idSeleccionado)
                              }
                         })
               }
          } else {
               console.warn("jQuery no está definido. Revisa la carga de librerías.")
          }

          renderizarNivel(window.arbolDatos)
     }

     setTimeout(() => {
          const splash = document.getElementById("intro-splash-overlay")
          if (splash) splash.classList.add("fade-out")
     }, 4000)
})

function actualizarBreadcrumb(nodoActual) {
     const breadcrumbContainer = document.getElementById("header-breadcrumb")
     if (!breadcrumbContainer) return

     breadcrumbContainer.innerHTML = ""
     const ruta = [...historial, nodoActual]

     ruta.forEach((nodo, index) => {
          const spanNodo = document.createElement("span")
          spanNodo.innerText = nodo.descripcion
          spanNodo.style.cursor = "pointer"
          spanNodo.style.transition = "color 0.2s ease"

          if (index === ruta.length - 1) {
               spanNodo.style.color = "#30c5a7"
               spanNodo.style.fontWeight = "bold"
               spanNodo.style.cursor = "default"
          } else {
               spanNodo.addEventListener("click", () => {
                    historial = ruta.slice(0, index)
                    renderizarNivel(nodo)
               })

               spanNodo.addEventListener("mouseenter", () => (spanNodo.style.color = "#00bfff"))
               spanNodo.addEventListener("mouseleave", () => (spanNodo.style.color = ""))
          }

          breadcrumbContainer.appendChild(spanNodo)

          if (index < ruta.length - 1) {
               const separador = document.createTextNode(" ❯ ")
               breadcrumbContainer.appendChild(separador)
          }
     })
}

function procesarArbol(nodo, padre = null, lista = []) {
     if (!nodo) return lista

     if (padre) {
          mapaPadres.set(nodo.id, padre)
     }

     lista.push({ id: nodo.id, descripcion: nodo.descripcion, nodoOriginal: nodo })

     if (nodo.hijos && nodo.hijos.length > 0) {
          nodo.hijos.forEach((hijo) => procesarArbol(hijo, nodo, lista))
     }

     return lista
}

function irANodoEspecifico(idNodoTarget) {
     let nodoActual = buscarNodoPorId(window.arbolDatos, idNodoTarget)
     if (!nodoActual) return

     let nuevoHistorial = []
     let curr = mapaPadres.get(nodoActual.id)

     while (curr) {
          nuevoHistorial.unshift(curr)
          curr = mapaPadres.get(curr.id)
     }

     historial = nuevoHistorial

     if (historial.length > 0) {
          let padreInmediato = historial[historial.length - 1]
          renderizarNivel(padreInmediato)
     } else {
          renderizarNivel(nodoActual)
     }
}

function buscarNodoPorId(nodo, id) {
     if (nodo.id === id) return nodo
     if (nodo.hijos) {
          for (let hijo of nodo.hijos) {
               let encontrado = buscarNodoPorId(hijo, id)
               if (encontrado) return encontrado
          }
     }
     return null
}
