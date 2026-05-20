<?php

use yii\helpers\Html;
use app\assets\AppAsset;
use app\models\Empleado;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Json;
use app\models\ConfiguracionDiccionario;

/** @var string $content */

$diccionarioDB = ConfiguracionDiccionario::find()
    ->select(['palabra_mal', 'palabra_correcta'])
    ->asArray()
    ->all();

$comunes = [];
foreach ($diccionarioDB as $fila) {
    $comunes[strtolower($fila['palabra_mal'])] = $fila['palabra_correcta'];
}

AppAsset::register($this);
//$this->registerCssFile("@web/css/datafam.css", ['depends' => [\yii\web\YiiAsset::className(), \yii\bootstrap\BootstrapAsset::className()]]);
$this->registerCssFile("@web/css/rolo.css", ['depends' => [\yii\web\YiiAsset::className(), \yii\bootstrap\BootstrapAsset::className()]]);
$this->registerJs("var diccPersonalizado = " . Json::encode($comunes) . ";", \yii\web\View::POS_HEAD);
$this->title = "DATAFAM | INFORMACION";

$usuario = Yii::$app->user->identity;
$empleado = Empleado::find()->where(['idpersona' => $usuario->idpersona])->one();
$id = $usuario != null ? $usuario->id : null;
if (!isset($id) || $id == null) {
      $model = new \app\models\LoginForm();
      return Yii::$app->getResponse()->redirect([
            'site/login',
            'model' => $model,
      ]);
}

?>

<style>
      .mensaje_motivador {
            background-color: black;
      }

      .centrado {
            width: 60%;
            height: 30px;
            background-color: coral;
            position: absolute;
            top: 50%;
            left: 45%;
            transform: translate(-50%, -50%);
            background-color: black;
            color: #87b867;
            /* Color del texto */
            border: 2px solid #87b867;
            /* Borde */
            border-radius: 5px;
            /* Bordes redondeados */
            padding-left: 10px;
            padding-right: 10px;
            box-shadow: 0 0 5px #87b867, 0 0 10px #87b867, 0 0 20px #87b867, 0 0 30px #87b867;
            /* Efecto neón */

            overflow: hidden;
            /* Oculta el desbordamiento del texto */
      }

      .centrado span {
            position: absolute;
            white-space: nowrap;
            /* Evita que el texto se divida en varias líneas */
            animation: desplazar 20s linear infinite;
            /* Animación de desplazamiento */
      }

      @keyframes desplazar {
            0% {
                  left: 100%;
            }

            100% {
                  left: -100%;
            }
      }
</style>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="fixed js flexbox flexboxlegacy no-touch csstransforms csstransforms3d no-overflowscrolling no-mobile-device custom-scroll sidebar-left-collapsed">
<!-- Acordarse que el estilo de bootstrap esta en web/template/stylesheets/theme.css -->

<head>
      <meta charset="<?= Yii::$app->charset ?>">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
      <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.png" type="image/x-icon" />
      <?php $this->registerCsrfMetaTags() ?>
      <title><?= Html::encode($this->title) ?></title>
      <?php $this->registerJs("var diccPersonalizado = " . Json::encode($comunes) . ";", \yii\web\View::POS_HEAD); ?>
      <?php $this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js'); ?>
      <?php $this->head() ?>
      
</head>

<body onload="$('#loading').hide();">

      <?php $this->beginBody() ?>
      <section class="body fondo">

            <!-- start: header -->
            <header class="fondo header ">
                  <div class="logo fondo neon">
                        <a href="index.php?r=site%2Findex" class="logo fondo" style="margin-top:1px;margin-left:20px">
                              <img src="img/datafam-rolo-style.jpg" alt="Logo" class=" logo neon">
                        </a>
                        <div class="visible-xs toggle-sidebar-left fondo" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                              <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                        </div>
                  </div>

                  <div class="centrado ">
                        <span>..."Si quieres alcanzar el exito, no esperes a que las condiciones sean perfectas, comienza donde estás y con lo que tienes"... -Anonimo-</span>
                        <!-- <span>..."Debes ser el cambio que deseas ver en el mundo"... -Gandhi-</span>
                        <span>..."LA VIDA NO SE MIDE POR LA CANTIDAD DE RESPIROS QUE TOMAS, SE MIDE POR LOS MOMENTOS QUE TE QUITAN EL ALIENTO"... -Hitch-</span>
                        <span>..."EN TIEMPOS DE CRISIS, LOS SABIOS CONSTRUYEN PUENTES, MIENTRAS QUE LOS NECIOS CONSTRUYEN BARRERAS."... -Black Panter-</span> -->
                  </div>


                  <!-- start: search & user box -->
                  <div class="header-right ">

                        <span class="separator"></span>

                        <!-- <form action="pages-search-results.html" class="search nav-form">
                              <div class="input-group input-search">
                                    <input type="text" class="form-control" name="q" id="q" placeholder="Search...">
                                    <span class="input-group-btn">
                                          <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                                    </span>
                              </div>
                        </form> -->



                        <span class="separator fondo"></span>

                        <div id="userbox" class="userbox">
                              <a href="" data-toggle="dropdown">
                                    <figure class="profile-picture neon">
                                          <img src="<?= $usuario->avatar != null ? 'img/usuarios-avatares/' . $usuario->avatar : 'img/usuarios-avatares/avatar-0.jpg' ?>" alt="Imagen Usuario" class="img-circle" data-lock-picture="<?= $usuario->avatar != null ? 'img/usuarios-avatares/' . $usuario->avatar  : 'img/usuarios-avatares/avatar-0.jpg' ?>">
                                    </figure>
                                    <div class="profile-info" data-lock-name="Usuario" data-lock-email="<?= $usuario->email ?>">
                                          <span class="name"><?= $usuario->email ?></span>

                                    </div>
                                    <i class="fa custom-caret"></i>
                              </a>
                              <div class="dropdown-menu">
                                    <ul class="list-unstyled">
                                          <li class="divider"></li>

                                          <!-- <li>
                                                <a href="#" onclick="$('#modal-cambiar-pass').modal('show'); return false;">
                                                      <i class="fa fa-lock"></i> Cambiar Contraseña
                                                </a>
                                          </li>
                                          <li class="divider"></li> -->

                                          <li>
                                                <?php 

                                                            $url = Url::to(['/usuarios/update_password']);
                                                            echo Html::a('<i class="fa fa-lock"></i> Cambiar Contraseña', $url, [
                                                            'role' => 'modal-remote', 'title' => 'Cambiar Contraseña',
                                                            'data-confirm' => false, 'data-method' => false,
                                                            'data-request-method' => 'post',
                                                            'data-toggle' => 'tooltip',
                                                            ]);
                                                ?>
                                          </li>
                                          <li class="divider"></li>


                                          

                                          <li>
                                                <?=
                                                Yii::$app->user->isGuest ?
                                                      Html::a('Ingresar', ['site/login'], ['class' => 'btn btn-success']) :
                                                      Html::a('<i class="fa fa-power-off"></i> Cerrar Sesion', ['/site/logout'], ['role' => "menuitem"])
                                                ?>
                                          </li>
                                    </ul>
                              </div>
                        </div>
                  </div>
                  <!-- end: search & user box -->
            </header>
            <!-- end: header -->

            <div class="inner-wrapper ">
                  <!-- start: sidebar -->
                  <aside id="sidebar-left" class="sidebar-left fondo" style="padding-bottom: 0%!important;">

                        <div class="nano has-scrollbar fondo">
                              <div class="nano-content" tabindex="0" style="right: -17px;">

                                    <nav id="menu" class="nav-main" role="navigation">
                                          <?= $this->render('menu') ?>
                                    </nav>
                                    <hr class="separator">
                              </div>
                              <div class="nano-pane" style="opacity: 1; visibility: visible;">
                                    <div class="nano-slider" style="height: 61px; transform: translate(0px, 0px);"></div>
                              </div>
                        </div>

                  </aside>
                  <!-- end: sidebar -->

                  <!--Contenido a llenar por yii-->
                  <section role="main" class="content-body">
                        <?= $this->render('loading') ?>
                        <?= $content ?>

                        <?php Modal::begin([
                              "id" => "ajaxCrudModal",
                              'options' => [
                                    'tabindex' => false // important for Select2 to work properly
                              ],
                              'options' => [
                                    'tabindex' => false // important for Select2 to work properly
                              ],
                              'size' => Modal::SIZE_DEFAULT,
                              'clientOptions' => [
                                    'backdrop' => 'static'
                              ],
                              "footer" => "",
                        ]);
                        echo "<div id='content_abm'></div>";
                        ?>
                        <?php Modal::end(); ?>

                        

                        <?php

                        $this->registerJs(
                              "$('#modal_main').on('hidden.bs.modal', function() {
                                    if(!$(\"#modal_abm\").hasClass('fade modal in'))
                                    location.reload();
                              });
                              $(window).on(\"beforeunload\", function () {
                                    $('#loading').show();
                              });
                              
                              $('#modal-cambiar-pass').on('shown.bs.modal', function() {
                                    $('.mostrar-checkbox').off('change').on('change', function() {
                                          const target = $(this).data('target');
                                          $(target).attr('type', this.checked ? 'text' : 'password');
                                    });
                              });"
                        );
                        ?>
                  </section>
            </div>
      </section>
      <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>