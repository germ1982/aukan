<?php $imagen = '../web/img/escudo_oscuro.jpg'; /* Ruta al archivo de imagen */ ?>
<div id="loading" style="display: none;">
                    <div class="cargando">
                        <div class="pelotas neon"></div>
                        <div class="pelotas neon"></div>
                        <div class="pelotas neon "></div>


                    </div>
                    <div class="texto-cargando neon">
                        <span class="neon">CARGANDO...</span>
                    </div>

                    <!-- <div class="spinner-rolo"></div> -->
</div>

<style>
    /* Spinners INICIO */

    #loading {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: 9999998;
    background-color: rgba(0, 0, 0, 0.8)!important;


}
.spinner-rolo {
    position: fixed;
    
    background-size: cover; /* Ajusta el tamaño del icono para cubrir todo el div*/
    z-index: 9999999;
    height: 100px;
    width: 100px;
    top: 40%;
    left: 47%;

    /* background: radial-gradient(#2c3c4b, transparent); /* Gradiente radial */ 
    background-image: url(<?= $imagen ?>); /* Ruta al archivo de imagen */
    
    background-repeat: no-repeat; /* Evita que la imagen se repita */
    border-radius: 50%; /* Hace que el spinner tenga forma de círculo */
    animation: beat 2500ms linear infinite; /* Aplica la animación de latido infinito */
    box-shadow: 0 0 10px rgba(44, 60, 75, 0.8), 
                0 0 20px rgba(44, 60, 75, 0.8), 
                0 0 30px rgba(44, 60, 75, 0.8), 
                0 0 40px rgba(44, 60, 75, 0.8), 
                0 0 50px rgba(44, 60, 75, 0.8), 
                0 0 60px rgba(44, 60, 75, 0.8); /* Borde de neon oscuro */
  }
  
  @keyframes beat {
    0% {
      transform: scale(0.4); /* Tamaño más pequeño que el normal */
    }
    25% {
      transform: scale(0.8); /* Tamaño más pequeño que el normal */
    }
    50% {
      transform: scale(1.2); /* Aumenta el tamaño */
    }
    75% {
      transform: scale(0.8); /* Tamaño más pequeño que el normal */
    }
    100% {
      transform: scale(0.4); /* Vuelve al tamaño más pequeño */
    }
  }

  .cargando{
    position: fixed;
    
    background-size: cover; /* Ajusta el tamaño del icono para cubrir todo el div*/
    z-index: 9999999;
    height: 100px;
    width: 150px;
    top: 40%;
    left: 45%;
  
    
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
  margin: 0 auto; 
}
.texto-cargando{ 
    position: fixed;
    top: 58%;
    left: 47%;
      color:#87b867;
  padding-top:20px;
  text-align: center;
  font-size: 18px;
  
}
.cargando span{
    font-size: 20px;
    text-transform: uppercase;
}
.pelotas {
    width: 30px;
    height: 30px;
    background-image: url(<?= $imagen ?>);  /* Ruta al archivo de imagen */
    /* background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRLf77s-S6Jlm8q_qV5cjDz5vO1G7Q-1h4dpQ&s'); */ /* Ruta al archivo de imagen */
    
    background-size: cover; /* Ajusta el tamaño del icono para cubrir todo el div */
    animation: salto .5s alternate
    infinite;
    background-repeat: no-repeat; /* Evita que la imagen se repita */
    border-radius: 50%; /* Hace que el spinner tenga forma de círculo */

    box-shadow: 0 0 10px rgba(44, 60, 75, 0.8), 
            0 0 20px rgba(44, 60, 75, 0.8), 
            0 0 30px rgba(44, 60, 75, 0.8), 
            0 0 40px rgba(44, 60, 75, 0.8), 
            0 0 50px rgba(44, 60, 75, 0.8), 
            0 0 60px rgba(44, 60, 75, 0.8); /* Borde de neon oscuro */
  border-radius: 50%  
}
.pelotas:nth-child(2) {
    animation-delay: .18s;
}
.pelotas:nth-child(3) {
    animation-delay: .37s;
}
@keyframes salto {
    from {
        transform: scaleX(1.25);
    }
    to{
        transform: 
        translateY(-50px) scaleX(1);
    }
}

 /* Spinners FINAL */
</style>