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

    <div class="col-md-4">
        <img src="../img/web_informatica/<?=$grafico?>" alt="" width="400" height="<?=$alto_grafico?>">
    </div>

    <div class="col-md-8">

        <div class="titulo"><?=$titulo?></div>

        <div class="descripcion"><?=$descripcion?></div>

    </div>
</div><br><br>