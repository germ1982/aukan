<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
      .linea {

            border-top: 5px solid #87B867;
            position: relative;
            z-index: 100;
            padding-bottom: 5px;
      }

      .header_web {
            margin: 0 auto;
            background: url('../img/web_informatica/logo_rolo.png') no-repeat;
            display: block;
            height: 85px;
            width: 90%;
      }

      .heder_titulo {
            width: 60%;
            height: 85px;
            background-color: #2B3E4C;

            margin: 0 auto;
            border-radius: 40px 0 0 0;
            float: right;
            margin-bottom: 30px;

      }

      .heder_titulo_texto {
            font-family: 'Roboto', sans-serif!important;
            text-align: center;
            padding: 10px;
            color: #f4dfb9;
            font-size: 20px !important;
            font-size: 25px;
            font-family: Arial;

            font-weight: bold;
      }

      .body_informatica {
            background: url('../img/web_informatica/office_background.jpg') no-repeat;

            width: 90%;
            height: 600px;
      }

      .titulo_seccion {
            text-align: left;
            color: #888888;

            font-size: 20px !important;
            font-family: Arial;


      }
</style>

<style>
      body {
            font-family: Arial;
      }

      /* Style the tab */
      .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            padding-left: 67px;
      }

      /* Style the buttons inside the tab */
      .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
      }

      /* Change background color of buttons on hover */
      .tab button:hover {
            background-color: #ddd;
      }

      /* Create an active/current tablink class */
      .tab button.active {
            background-color: #ccc;
      }

      /* Style the tab content */
      .tabcontent {
            display: none;
            border: 1px solid #ccc;
            border-top: none;
            -webkit-animation: fadeEffect 2s;
            animation: fadeEffect 2s;
      }

      /* Fade in tabs */
      @-webkit-keyframes fadeEffect {
            from {
                  opacity: 0;
            }

            to {
                  opacity: 1;
            }
      }

      @keyframes fadeEffect {
            from {
                  opacity: 0;
            }

            to {
                  opacity: 1;
            }
      }

      .tabcontent.active {
            display: block;
      }
</style>

<div class="row linea"></div>

<div class="row header_web">
      <div class="main clearfix">

            <div class="heder_titulo">
                  <div class="heder_titulo_texto">Subsecretaria de Familia
                        <br>Direccion De Mantenimiento De Servicios Informaticos
                  </div>
            </div>
      </div>
</div>

<div class="row linea" style="margin-top: 5px;"></div>


<div class="tab">
      <button class="tablinks titulo_seccion" onclick="openCity(event, 'Sectores')">Institucional</button>
      <button class="tablinks titulo_seccion" onclick="openCity(event, 'Staff')">Staff</button>
      <button class="tablinks titulo_seccion" onclick="openCity(event, 'Trabajos')">Trabajos</button>
</div>
<br><br>
<div id="Sectores" class="tabcontent active">
      <?php include 'sectores.php' ?>
</div>

<div id="Staff" class="tabcontent">
      <?php include 'staff.php' ?>
</div>

<div id="Trabajos" class="tabcontent px-4">
      <?php include 'eventos.php' ?>

</div>


<script>
      function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                  tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                  tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
      }
</script>