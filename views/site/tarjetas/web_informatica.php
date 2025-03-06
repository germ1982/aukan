<style>
    .contenedor_web {
        background-color: black;
        padding: 5px;


    }


.neon_container {
  display: inline-block;
  padding: 5px;
  background-color: black;
  border-radius: 25px;
  box-shadow: 10 0 20px lime, 0 0 40px lime;
  animation: neonGlow 2.5s infinite alternate;
margin: 10px;

/* animation: neonRGB 2s infinite alternate; */
}

.neon_container:hover {
  box-shadow: 0 0 20px blue, 0 0 60px blue, 0 0 100px blue;
}

@keyframes neonRGB {
  0% { filter: drop-shadow(0 0 10px red); }
  33% { filter: drop-shadow(0 0 10px lime); }
  66% { filter: drop-shadow(0 0 10px blue); }
  100% { filter: drop-shadow(0 0 10px red); }
}

@keyframes neonGlow {
  from {
    box-shadow: 0 0 5px lime, 0 0 20px lime;
  }
  to {
    box-shadow: 0 0 20px greenyellow, 0 0 60px greenyellow;
  }
}

    /* Scrollbar general */
    ::-webkit-scrollbar {
        width: 12px;
        /* Ancho del scrollbar */
        height: 12px;
        /* Alto del scrollbar */
    }

    /* Fondo de la barra de desplazamiento */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
        /* Esquinas redondeadas */
    }

    /* Manija de la barra de desplazamiento */
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
        /* Esquinas redondeadas */
    }

    /* Manija de la barra de desplazamiento al pasar el ratón */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>







<div class="contenedor_web neon_container">
    <a href="web_informatica/informatica.php" target="_blank">
        <img src="img\tarjetas\escudo_informatica_1.png" alt="Descripción de la imagen" class="img_info">
    </a>
</div>

<!-- <a href="http://10.1.176.222/web_info/Informatica.html" target="_blank">
        <img src="img\tarjetas\web_informatica.jpg" alt="Descripción de la imagen">
    </a> -->