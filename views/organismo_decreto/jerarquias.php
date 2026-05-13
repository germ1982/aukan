<div class="df-leyenda-horizontal">
    <div class="df-item-h nivel-1"><strong>NIVELES:</strong></div>
    <div class="df-item-h nivel-1"><span class="df-color"></span> 1 - Ministerios</div>
    <div class="df-item-h nivel-2"><span class="df-color"></span> 2 - Subsecretarías</div>
    <div class="df-item-h nivel-3"><span class="df-color"></span> 3 - Coordinaciónes</div>
    <div class="df-item-h nivel-4"><span class="df-color"></span> 4 - Dir. Provinciales</div>
    <div class="df-item-h nivel-5"><span class="df-color"></span> 5 - Dir. Generales</div>
    <div class="df-item-h nivel-6"><span class="df-color"></span> 6 - Direcciónes</div>
    <div class="df-item-h nivel-7"><span class="df-color"></span> 7 - Departamentos</div>
    <div class="df-item-h nivel-8"><span class="df-color"></span> 8 - Dispositivos</div>
</div>

<style>
.df-leyenda-horizontal {
    /* --- COMPORTAMIENTO FLOTANTE --- */
    position: fixed;
    margin-top: 15px;
    left: 650px;
    transform: translateX(-50%); /* Lo centra horizontalmente */
    z-index: 1000;
    
    /* --- DISEÑO DE BARRA --- */
    display: flex;      /* Alinea los items en una fila */
    gap: 15px;          /* Espacio entre cada jerarquía */
    background: rgba(255, 255, 255, 0.9);
    padding: 8px 20px;
    border: 1px solid #ccc;
    border-radius: 50px; /* Forma de píldora/sticker */
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    font-family: 'Segoe UI', sans-serif;
    white-space: nowrap; /* Evita que se rompa en varias líneas */
}

.df-item-h {
    display: flex;
    align-items: center;
    font-size: 12px;
    font-weight: 600;
    color: #444;
}

.df-color {
    width: 14px;
    height: 14px;
    border-radius: 50%; /* Círculos para que ocupe menos espacio */
    margin-right: 6px;
    display: inline-block;
}

/* Colores por nivel */
.nivel-1 .df-color { background-color: #d9534f; }
.nivel-2 .df-color { background-color: #f0ad4e; }
.nivel-3 .df-color { background-color: #f39c12; }
.nivel-4 .df-color { background-color: #5bc0de; }
.nivel-5 .df-color { background-color: #3498db; }
.nivel-6 .df-color { background-color: #d2e052; }
.nivel-7 .df-color { background-color: #7ade5b; }
.nivel-8 .df-color { background-color: #9b59b6; }

/* Se desvanece al pasar el mouse por si tapa algo importante */
.df-leyenda-horizontal:hover {
    /* opacity: 0.15; */
    transition: opacity 0.3s;
}
</style>