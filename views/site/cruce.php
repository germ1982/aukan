<?php
use yii\helpers\Url;

$this->title = "Cruce de Datos MDS";
$dni = isset($_GET['dni']) ? $_GET['dni'] : '';
$unificado = isset($_GET['unificado']) ? $_GET['unificado'] : 0;

if (strpos($dni, ',') > 0) {
    $unificado = 0;
} else if ($dni != '') {
    $unificado = 1;
}

switch ($unificado) {
    case 0:
        $link = "https://portalmds.neuquen.gov.ar/iframe/cruce?auditoria="
            . Yii::$app->user->identity->user . "&documento=" . $dni;
        break;
    case 1:
        $link = "https://pui.neuquen.gov.ar/iframe/home?documento=" . $dni;
        break;
    case 2:
        $this->title = "Cruce de Datos RH Proneu";
        $link = "https://portalmds.neuquen.gov.ar/iframe/cruceRH?";
        break;
    default:
        $url = Url::to(['/']);
        return $this->context->redirect($url); 
        break;
}
$token = Yii::$app->user->identity->accessToken ? Yii::$app->user->identity->accessToken : '';
$tokenNest = $_SESSION["tokenNest"] ? $_SESSION["tokenNest"] : '';
$link = $link . "&token_sur=" . $token . "&token_surV2=" . $tokenNest;
?>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <iframe id="iframe_cruce" width="100%" height="600" src="<?= $link ?>">
                </iframe>
            </div>
        </section>
    </div>
</div>

<?php
$script_resize = <<< JS
var buffer = 30; //scroll bar buffer
var iframe = document.getElementById('iframe_cruce');

function pageY(elem) {
    return elem.offsetParent ? (elem.offsetTop + pageY(elem.offsetParent)) : elem.offsetTop;
}

function resizeIframe() {
    var height = document.documentElement.clientHeight;
    console.log(height);
    height -= pageY(document.getElementById('iframe_cruce'))+ buffer ;
    height = (height < 0) ? 0 : height;
    document.getElementById('iframe_cruce').style.height = height + 'px';
}

// .onload doesn't work with IE8 and older.
if (iframe.attachEvent) {
    iframe.attachEvent("onload", resizeIframe);
} else {
    iframe.onload=resizeIframe;
}

window.onresize = resizeIframe;
JS;
$this->registerJS($script_resize);
