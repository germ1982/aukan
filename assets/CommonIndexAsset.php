<?php

namespace app\assets;

use yii\web\AssetBundle;

class CommonIndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/css_index_views.css', // <-- ¡Añade esta línea aquí!
    ];
    public $js = [
        'js/common-index-modales-apertura-cierre.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        \kartik\select2\Select2Asset::class, // Asegúrate de esta dependencia
        AppAsset::class,
    ];
}
?>