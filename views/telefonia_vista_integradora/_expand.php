<?php

    use app\models\Telefonia_vista_integradora;
?>

<div style='padding-left:  40px; '>
    <div style='border: 1px solid #ccc; border-radius: 4px;'>

        <div class='row'style='padding:0px 10px;'>
            <?php 
                crear_celda('Linea Inicial',$model->lineanro, 4);
                crear_celda('Empresa',$model->empresa, 4);
                crear_celda('Equipo',$model->equipo, 4);
            ?>
        </div>
    </div>
</div>


