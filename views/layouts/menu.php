<?php

//print_r($permisos);

use app\models\Menu;

?>
<ul class="nav nav-main">
    <?php
    //Arranco a armar el arbol de menu
    echo Menu::getArbolMenu();
                                      
    ?>
    <!-- Hasta aca se reemplaza por dinamico -->
</ul>