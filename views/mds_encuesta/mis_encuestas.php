<?php

$this->title = "Mis Encuestas"
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
                <iframe id="iframe_mis_encuestas" width="100%" height="600" 
                src="encuesta/mis_encuestas.php?id_user=<?= Yii::$app->user->identity->idusuario; ?>&name_user=<?= Yii::$app->user->identity->user; ?>">
                </iframe>
            </div>
        </section>
    </div>
</div>

<?php

$script_resize = <<< JS
var buffer = 30; //scroll bar buffer
var iframe = document.getElementById('iframe_mis_encuestas');

function pageY(elem) {
    return elem.offsetParent ? (elem.offsetTop + pageY(elem.offsetParent)) : elem.offsetTop;
}

function resizeIframe() {
    var height = document.documentElement.clientHeight;
    console.log(height);
    height -= pageY(document.getElementById('iframe_mis_encuestas'))+ buffer ;
    height = (height < 0) ? 0 : height;
    document.getElementById('iframe_mis_encuestas').style.height = height + 'px';
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