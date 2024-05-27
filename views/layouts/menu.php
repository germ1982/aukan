<?php

//print_r($permisos);

use app\models\Menu;

?>

<style>


    .titulo-menu {
        font-size: 32px;
        color:#fff;

  text-align: center;
  text-transform: uppercase;
    }
</style>
<ul class="nav nav-main">
    <li>
        <a>
            <i aria-hidden="true"></i>
            <span class="neon titulo-menu" styl>Mas Menu</span>
        </a>
    </li>
    <?php
    //Arranco a armar el arbol de menu
    echo Menu::getArbolMenu();

    ?>
    <!-- Hasta aca se reemplaza por dinamico -->
</ul>