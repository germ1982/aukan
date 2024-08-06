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

      .neon-border {

            display: block;
            margin: 0 auto;
            border-radius: 5%;

            position: relative;
            box-shadow: 0 0 5px #87B867, 0 0 5px #87B867, 0 0 20px #87B867, 0 0 10px #87B867, 0 0 10px #87B867;
      }

      .gray-border {

            width: 80%;
            margin: 0 auto;
            border-radius: 5%;
            border: 0.5px solid rgb(204, 204, 204);
            /* Gray border with 90% opacity */
      }
</style>

<div class="row" style="width: 92%; margin: 0 auto;" ;>

      <div class="col-md-4">
            <img src="../img/web_informatica/<?= $grafico ?>" alt="" width="400" height="<?= $alto_grafico ?>" class="neon-border">
      </div>

      <div class="col-md-8">

            <div class="titulo"><?= $titulo ?></div>

            <div class="descripcion"><?= $descripcion ?></div>

      </div>
</div>
<br><br>
<div class="gray-border"></div>
<br><br>