<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HighchartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'template/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.min.css'
    ];
    public $js = [
        'js/highcharts/highcharts.js',
        'js/highcharts/exporting.js',
        'js/indicadores_graph.js',
        'js/highcharts/series-label.js',
        'js/highcharts/export-data.js',
        'js/highcharts/accessibility.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
