<?php

use johnitvn\ajaxcrud\CrudAsset;

$this->title = "Mapa de Recursos";
    CrudAsset::register($this);
?>

<header class="page-header" style="margin-bottom: 10px !important; padding-left:0px !important;">
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
    <div class="col-md-6 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">                
                <iframe id="iframe_registros" width="100%" height="600" src="mapa/"></iframe>
            </div>
        </section>
    </div>
</div>