<?php

$idfactura = $_GET['idfactura'];

?>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <div class="panel-body">                
                <iframe id="iframe_importar_excell" width="100%" style="padding: 20px;"
                src="AutoSolicitud/Exell/sds_cel_factura_items_index_importar.php?idfactura=<?=$idfactura?>">
                </iframe>
            </div>
        </section>
    </div>
</div>

<?php

$script_resize = <<< JS
var buffer = 30; //scroll bar buffer
var iframe = document.getElementById('iframe_importar_excell');

function pageY(elem) {
    return elem.offsetParent ? (elem.offsetTop + pageY(elem.offsetParent)) : elem.offsetTop;
}

function resizeIframe() {
    var height = document.documentElement.clientHeight;
    console.log(height);
    height -= pageY(document.getElementById('iframe_importar_excell'))+ buffer ;
    height = (height < 0) ? 0 : height;
    document.getElementById('iframe_importar_excell').style.height = height + 'px';
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