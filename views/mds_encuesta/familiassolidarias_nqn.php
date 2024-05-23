<?php

$this->title = "Familias solidarias"
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
        
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">                
                <iframe id="iframe_familiassolidarias_nqn" width="100%" height="600" 
                src="encuesta/familiassolidarias_nqn.php">
                </iframe>
            </div>
        </section>
    </div>
</div>

<?php

$script_resize = <<< JS
var buffer = 30; //scroll bar buffer
var iframe = document.getElementById('iframe_familiassolidarias_nqn');

function pageY(elem) {
    return elem.offsetParent ? (elem.offsetTop + pageY(elem.offsetParent)) : elem.offsetTop;
}

function resizeIframe() {
    var height = document.documentElement.clientHeight;
    console.log(height);
    height -= pageY(document.getElementById('iframe_familiassolidarias_nqn'))+ buffer ;
    height = (height < 0) ? 0 : height;
    document.getElementById('iframe_familiassolidarias_nqn').style.height = height + 'px';
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