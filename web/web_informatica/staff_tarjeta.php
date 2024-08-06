<style>
      img {
            max-width: 100%;
            /* height: ; */
            display: block;
            margin: 0 auto;
            border-radius: 5%;
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

<div class="row" style="width: 90%; margin: 0 auto;" ;>

      <div class="col-md-2">

            <img src="../img/empleados-fotos/<?= $foto ?>" alt="" width="200" height="auto" class="neon-border">

      </div>

      <div class="col-md-10">

            <div class="titulo"><?= $nombre ?> - <?= $funcion ?></div>

            <div class="descripcion"><?= $descripcion ?></div>

      </div>
</div><br>
<div class="gray-border"></div>
<br><br>