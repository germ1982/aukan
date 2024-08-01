<style>
    img {
        max-width: 100%;
        /* height: ; */
        display: block;
        margin: 0 auto;
    }

    .titulo {
        background-color: #87B867;
        color: #2B3E4C;
        font-size: 25px;
        padding-left: 15px;
    }


    .descripcion {
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        color: #333;
        text-align: justify;
        line-height: 1.6;
        margin: 20px;
    }
</style>

<div class="row" style="width: 90%; margin: 0 auto;" ;>

    <div class="col-md-2">
        <img src="../img/empleados-fotos/<?=$foto?>" alt="" width="200" height="auto">
    </div>

    <div class="col-md-10">

        <div class="titulo"><?=$nombre?> - <?=$funcion?></div>

        <div class="descripcion"><?=$descripcion?></div>

    </div>
</div><br><br>