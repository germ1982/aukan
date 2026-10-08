<style>
    .contenedor_web {
        padding: 5px;
        display: inline-block;
        margin: 10px;
    }

    .img_info {
        /* Mantiene la animación del neón */
        animation: neonGlow 2.5s infinite alternate;
        margin-top: 10%!important; /* Ajusta la posición vertical al pasar el mouse */
    }

    /* Efecto al pasar el mouse: vibración continua */
    .img_info:hover {
        animation: neonGlow 2.5s infinite alternate, vibrar 0.2s infinite;
        
    }

    /* Animación del neón */
    @keyframes neonGlow {
        from {
            filter: drop-shadow(0 0 5px lime) drop-shadow(0 0 20px lime);
        }
        to {
            filter: drop-shadow(0 0 20px greenyellow) drop-shadow(0 0 60px greenyellow);
        }
    }

    /* Animación de vibración */
    @keyframes vibrar {
        0% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(-2px, 2px) rotate(-1deg); }
        50% { transform: translate(2px, -2px) rotate(1deg); }
        75% { transform: translate(-2px, -1px) rotate(0deg); }
        100% { transform: translate(1px, 2px) rotate(1deg); }
    }
</style>







<div class="">
    <a href="web_informatica/informatica.php" target="_blank">
        <img src="img\tarjetas\escudo_informatica.png" alt="Descripción de la imagen" class="img_info">
    </a>
</div>

<!-- <a href="http://10.1.176.222/web_info/Informatica.html" target="_blank">
        <img src="img\tarjetas\web_informatica.jpg" alt="Descripción de la imagen">
    </a> -->