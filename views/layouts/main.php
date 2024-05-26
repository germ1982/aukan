<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Empleado;
use yii\bootstrap\Modal;
use \yii\helpers\Url;

AppAsset::register($this);

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
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="dark fixed js flexbox flexboxlegacy no-touch csstransforms csstransforms3d no-overflowscrolling no-mobile-device custom-scroll sidebar-left-collapsed">
<!-- Acordarse que el estilo de bootstrap esta en web/template/stylesheets/theme.css -->

<head>
      <meta charset="<?= Yii::$app->charset ?>">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
      <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.png" type="image/x-icon" />
      <?php $this->registerCsrfMetaTags() ?>
      <title><?= Html::encode($this->title) ?></title>
      <?php $this->head() ?>
</head>

<body onload="$('#loading').hide();">
      <style>
            .content-body {
                  padding: 0px !important;
            }

            #loading-gif {
                  background: url('img/sur.gif') no-repeat center center;
                  position: fixed;
                  background-size: 100%;
                  z-index: 9999999;
                  height: 100px;
                  width: 100px;
                  top: 40%;
                  left: 47%;
            }

            #loading {
                  position: fixed;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  z-index: 9999998;
                  background-color: rgba(0, 0, 0, 0.24);


            }

            .logo {
                  margin: 0px !important;
                  width: 150px;
                  /* Ajusta el ancho según sea necesario */
                  height: auto;
                  /* Esto mantiene la proporción de la imagen */
            }

            /* Contenedor del banner en movimiento */
          
      </style>
      <?php $this->beginBody() ?>
      <section class="body">

            <!-- start: header -->
            <header class="header">
                  <div class="logo">
                        <a href="index.php?r=site%2Findex" class="logo" style="margin-top:1px;margin-left:20px">
                              <img src="img/logo datafam.png" alt="Logo" class="logo">
                        </a>
                        <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                              <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                        </div>
                  </div>



                  <!-- start: search & user box -->
                  <div class="header-right">
                        <!-- 
                <form action="pages-search-results.html" class="search nav-form">
                    <div class="input-group input-search">
                        <input type="text" class="form-control" name="q" id="q" placeholder="Search...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>

                <span class="separator"></span> -->



                        <span class="separator"></span>

                        <div id="userbox" class="userbox">
                              <a href="" data-toggle="dropdown">
                                    <figure class="profile-picture">
                                          <img src="<?= $usuario->avatar != null ? 'img/usuarios-avatares/' . $usuario->avatar . '.jpg' : 'img/usuarios-avatares/avatar-0.jpg' ?>" alt="Imagen Usuario" class="img-circle" data-lock-picture="<?= $usuario->avatar != null ? 'img/usuarios-avatares/' . $usuario->avatar . '.jpg' : 'img/usuarios-avatares/avatar-0.jpg' ?>">
                                    </figure>
                                    <div class="profile-info" data-lock-name="Usuario" data-lock-email="<?= $usuario->email ?>">
                                          <span class="name"><?= $usuario->email ?></span>

                                    </div>
                                    <i class="fa custom-caret"></i>
                              </a>
                              <div class="dropdown-menu">
                                    <ul class="list-unstyled">
                                          <li class="divider"></li>
                                          <li>

                                          </li>
                                          <li class="divider"></li>
                                          <li>

                                          </li>
                                          <li class="divider"></li>
                                          <li>

                                          </li>
                                          <li>

                                          </li>
                                          <li class="divider"></li>
                                          <li>
                                                <?=
                                                Yii::$app->user->isGuest ?
                                                      Html::a('Ingresar', ['site/login'], ['class' => 'btn btn-success']) :
                                                      Html::a('<i class="fa fa-power-off"></i> Logout', ['/site/logout'], ['role' => "menuitem"])
                                                ?>
                                          </li>
                                    </ul>
                              </div>
                        </div>
                  </div>
                  <!-- end: search & user box -->
            </header>
            <!-- end: header -->

            <div class="inner-wrapper">
                  <!-- start: sidebar -->
                  <aside id="sidebar-left" class="sidebar-left">

                        <div class="nano has-scrollbar">
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
                        <div id="loading" style="display: none;">
                              <div style="text-align: center;font-size: 24px;">
                                    <div id="loading-gif">
                                          <h4 style="padding-top:100px;color: #000;font-style:italic;font-family: Open Sans, Arial, sans-serif"><i>Cargando...</i></h4>
                                    </div>
                              </div>
                        </div>
                        <?= $content ?>

                        <?php Modal::begin([
                              "id" => "ajaxCrudModal",
                              'options' => [
                                    'tabindex' => false // important for Select2 to work properly
                              ],
                              'options' => [
                                    'tabindex' => false // important for Select2 to work properly
                              ],
                              'size' => Modal::SIZE_SMALL,
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