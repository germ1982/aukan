<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'template/vendor/jstree/themes/default/style.min.css',/* * */
		/* 'template/vendor/bootstrap/css/bootstrap.css', */
        'template/stylesheets/theme-admin-extension.min.css',/* * */
        'template/stylesheets/invoice-print.min.css',/* * */
		'template/vendor/font-awesome/css/font-awesome.min.css',/* * */
		'template/vendor/font-awesome/css/all.min.css',/* * */
		'template/vendor/elusive-icons/css/elusive-webfont.min.css',/* * */
		'template/vendor/magnific-popup/magnific-popup.min.css',/* * */	
		'template/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.min.css',/* * */
		'template/vendor/bootstrap-multiselect/bootstrap-multiselect.css',/* - */
		'template/vendor/morris/morris.css',/* - */
		'template/stylesheets/theme.min.css',/* * */
		'template/stylesheets/skins/default.min.css',/* * */
		'template/stylesheets/theme-custom.css',/* - */
		'template/vendor/select2/select2.min.css',/* * */
		'template/vendor/jquery-datatables-bs3/assets/css/datatables.css',/* * */
		'css/indicadores_home.css',/* - */
        'css/impresion_ris.css',/* - */
		'css/load-animated.css',/* - */
		"template/vendor/jquery-confirm/jquery-confirm.min.css",/* * */
		//"https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css",
    ];
    public $js = [
		/* 'template/vendor/bootstrap/js/bootstrap.js', */
		'template/vendor/modernizr/modernizr.min.js',/* * */
		'template/vendor/jquery-browser-mobile/jquery.browser.mobile.js',/* - */
		'template/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.min.js',/* * */
		'template/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js',/* * */
		'template/vendor/jquery-appear/jquery.appear.min.js',/* * */
		'template/vendor/bootstrap-multiselect/bootstrap-multiselect.min.js',/* * */
		'template/vendor/jquery-easypiechart/jquery.easypiechart.min.js',/* * */
		'template/vendor/flot/jquery.flot.min.js',/* * */
		'template/vendor/flot-tooltip/jquery.flot.tooltip.min.js',/* * */
		'template/vendor/flot/jquery.flot.pie.min.js',/* * */
		'template/vendor/flot/jquery.flot.categories.min.js',/* * */
		'template/vendor/flot/jquery.flot.resize.min.js',/* * */
		'template/vendor/jquery-sparkline/jquery.sparkline.min.js',/* * */
		'template/vendor/raphael/raphael.js',/* - */
		'template/vendor/morris/morris.min.js',/* * */
		'template/vendor/gauge/gauge.js',/* - */
		'template/vendor/snap-svg/snap.svg.min.js',/* * */
		'template/vendor/liquid-meter/liquid.meter.min.js',/* * */
		'template/vendor/jqvmap/jquery.vmap.min.js',/* * */
		'template/vendor/jqvmap/data/jquery.vmap.sampledata.js',/* - */
		'template/vendor/jqvmap/maps/jquery.vmap.world.js',/* * */
		'template/vendor/jqvmap/maps/continents/jquery.vmap.africa.js',/* ? */
		'template/vendor/jqvmap/maps/continents/jquery.vmap.asia.js',/* ? */
		'template/vendor/jqvmap/maps/continents/jquery.vmap.australia.js',/* ? */
		'template/vendor/jqvmap/maps/continents/jquery.vmap.europe.js',/* ? */
		'template/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js',/* ? */
		'template/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js',/* ? */
		'template/vendor/nanoscroller/nanoscroller.min.js',/* * */
		'template/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js',/* * */
		'template/vendor/magnific-popup/magnific-popup.min.js',/* * */
		'template/vendor/jquery-placeholder/jquery.placeholder.min.js',/* * */
		'template/vendor/jquery-validation/jquery.validate.min.js',/* * */
		'template/vendor/jstree/jstree.min.js',/* * */
		'template/javascripts/theme.min.js',/* * */
		'template/javascripts/theme.custom.js',/* - */
		'template/javascripts/theme.init.min.js', /* * */
		'template/javascripts/forms/examples.validation.js',/* - */	
		'template/javascripts/ui-elements/examples.treeview.js', /* - */
		//'template/vendor/select2/select2.js',
		'template/vendor/jquery-datatables/media/js/jquery.dataTables.min.js',/* * */
		'template/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js',/* * */
		'template/vendor/jquery-datatables-bs3/assets/js/datatables.min.js',/* * */
		'template/javascripts/tables/examples.datatables.default.js',/* - */
		'template/javascripts/tables/examples.datatables.row.with.details.js',/* - */
		'template/javascripts/tables/examples.datatables.tabletools.js',/* - */
		'template/vendor/ios7-switch/ios7-switch.js',/* - */
		"template/javascripts/xlsx/xlsx.full.min.js",/* * */
		"template/vendor/jquery-confirm/jquery-confirm.min.js",/* * */
		//"https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js",
		'js/common-index-select2-foco.js',//esto es para que el foco en los search de los select2
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
	];
	
}
