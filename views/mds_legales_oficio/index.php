<?php

use app\models\Mds_legales_oficio;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_legales_respuesta_estado;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_legales_oficioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $title;
$idcontacto  = Yii::$app->user->identity->idcontacto;
$idusuario = Yii::$app->user->identity->idusuario;
$usuarioAuth = Yii::$app->user->identity;
$contacto = Mds_org_contacto::findOne($idcontacto);
$estadoAprobada = Mds_legales_respuesta_estado::APROBADA;
$estadoRechazada = Mds_legales_respuesta_estado::RECHAZADA;
$estadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;
$estadoObservada = Mds_legales_respuesta_estado::OBSERVADA;

$idDispositivo = $contacto->iddispositivo; // Este es el id dispositivo de la persona logeada

$permisos = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)")->all();
$rol_vinculacion = Mds_seg_usuario_rol::find()
    ->where(['idusuario' => $idusuario])
    ->andWhere(["idrol" => Mds_legales_oficio::ID_ROL_VINCULACION])
    ->one();
$rol_registro = Mds_seg_usuario_rol::find()
    ->where(['idusuario' => $idusuario])
    ->andWhere(["idrol" => Mds_legales_oficio::ID_ROL_REGISTRO])
    ->one();
$rol_supervisor_general = Mds_seg_usuario_rol::find()
    ->where(['idusuario' => $idusuario])
    ->andWhere(["idrol" => Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL])
    ->one();

$permiso_global = 0;
$permiso_alta = 1;
$permiso_edicion = 0;
$permiso_baja = 0;
$permiso_crear_oficio = 0;
$permiso_ver_oficio = 0;
$permiso_ver_oficio_vinculacion = 0;
$permiso_ver_oficio_registro = 0;
$permiso_editar_oficio = 0;
$permiso_eliminar_oficio = 0;
$permiso_responder_oficio = 0;
$permiso_ver_respuestas = 0;
$permiso_rechazar_oficio = 0;
$permiso_vincular = 0;
foreach ($permisos as $permiso) {
    if ($permiso->iditem == Mds_seg_item::MODULO_LEGALES_CREAR_REQUERIMIENTO) {
        $permiso_crear_oficio = 1;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_LEGALES_VER_REQUERIMIENTO) {
        $permiso_ver_oficio = 1;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_LEGALES_EDITAR_REQUERIMIENTO) {
        $permiso_editar_oficio = 1;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_LEGALES_ELIMINAR_REQUERIMIENTO) {
        $permiso_eliminar_oficio = 1;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_LEGALES_RESPONDER_REQUERIMIENTO) {
        $permiso_responder_oficio = 1;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_LEGALES_VER_RESPUESTAS) {
        $permiso_ver_respuestas = 1;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_LEGALES_RECHAZAR_REQUERIMIENTO) {
        $permiso_rechazar_oficio = 1;
    }
    if ($permiso->iditem == Mds_seg_item::MODULO_LEGALES_VINCULAR_PERSONAS) {
        $permiso_vincular = 1;
    }
}
if ($rol_vinculacion && $rol_vinculacion['idusuariorol']) {
    $permiso_ver_oficio_vinculacion = 1;
}
if ($rol_registro && $rol_registro['idusuariorol']) {
    $permiso_ver_oficio_registro = 1;
}
$string = "";
if ($permiso_vincular == 1 || $hasRolAdminGeneral) {
    $string .= "{vincular}";
}
if ($permiso_responder_oficio == 1 || $hasRolAdminGeneral) {
    $string .= "{responder}{misrespuestas}";
}
if ($permiso_ver_respuestas == 1 || $hasRolAdminGeneral || $hasRolReceptor) {
    $string .= "{respuestas}";
}
if ($permiso_editar_oficio || $hasRolAdminGeneral) {
    $string .= "{update}";
}
if ($permiso_eliminar_oficio || $hasRolAdminGeneral) {
    $string .= "{delete}";
}
if ($hasRolAdminGeneral) {
    $string .= "{reactivate}{agregarDerivaciones}{agregarArchivos}";
}
if ($permiso_rechazar_oficio) {
    $string .= "{rechazaroficio}";
}
// Precargamos derivar y ver (siempre y cuando sea el mismo usuario que cargó el oficio)
$string .= "{view}{derivar}{reDerivar}{derivarRegistro}";

$botonRequerimientosDevueltos = '';
if ($rol_registro || $rol_supervisor_general || $hasRolAdminGeneral) {
    $botonRequerimientosDevueltos = Html::button('Requerimientos Devueltos', ['id' => 'boton-requerimientos-devueltos', 'type' => "button", 'class' => 'btn btn-default pull-left btnDevueltos', 'data-toggle' => "modal", 'data-target' => "#modalRequerimientosDevueltos"]);
}
$botonManual = Html::button('Manual de Usuario', ['id' => 'boton-manual', 'type' => "button", 'class' => 'btn btn-primary pull-left btnManual']);

$botonBuscarCaratula = '';
if ($permiso_crear_oficio == 1 || $hasRolAdminGeneral) {
    $botonBuscarCaratula = Html::button('<i class="glyphicon glyphicon-plus"></i>', ['id' => 'boton-buscar-caratula', 'type' => "button", 'class' => 'btn btn-success pull-left', 'style' => 'margin-right: 1rem', 'data-toggle' => "modal", 'data-target' => "#modalBuscarCaratula", 'onclick' => "limpiarDatosCaratula()"]);
}

CrudAsset::register($this);
?>
<style>
    .table>thead>tr>td.info,
    .table>tbody>tr>td.info,
    .table>tfoot>tr>td.info,
    .table>thead>tr>th.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>tbody>tr.info>td,
    .table>tfoot>tr.info>td,
    .table>thead>tr.info>th,
    .table>tbody>tr.info>th,
    .table>tfoot>tr.info>th {
        color: #777;
        background-color: #fafafa !important;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
    }

    .btnDevueltos {
        background: grey !important;
        color: white !important;
        margin-right: 1rem;
    }

    .btnManual {
        margin-left: auto !important;
    }

    .overflow-y {
        max-height: 300px;
        overflow-y: auto;
    }

    .button-like-link {
        background-color: transparent;
        border: none;
        color: #08c;
        text-decoration: underline;
        cursor: pointer;
        padding: 0;
    }
</style>
<header class="page-header">
    <h2><?= $title ?></h2>

    <div class="right-wrapper pull-right">

        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $subtitle ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-legales-oficio-index table-responsive">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>
                                $botonRequerimientosDevueltos .
                                    $botonBuscarCaratula .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                        [''],
                                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                    ) .
                                    '{toggleData}' .
                                    '{export}'],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'panel' => [
                                'type' => 'default',
                                'heading' => '',
                                'after' => "<div class='clearfix'></div>",
                                'before' => $botonManual
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<?php
require 'modal_requerimientos_devueltos.php';
require 'modal_buscar_caratula.php';
require 'agregar_archivos.php';

$this->registerJs(
    "
    $('#crud-datatable-filters').children('td').children().css('z-index', '0');
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    })

    $('#boton-requerimientos-devueltos').click(function() {
        $('#lista-requerimientos-cargando').html('Cargando...');
        $('#lista-requerimientos-devueltos').empty();
        $.ajax({
                type: 'POST',
                url: '" . Url::to(['/mds_legales_oficio/requerimientosdevueltos']) . "', 
                data: { },

                success: function (data) {
                    $('#lista-requerimientos-cargando').html('');
                    let parseData = JSON.parse(data);
                    if(parseData && parseData.data.length > 0) {
                        parseData.data.forEach(element => $('#lista-requerimientos-devueltos').prepend(
                            '<li> <b>#' + element.idlegalesoficio + '</b> - ' + element.descripcion + ' </li>'
                        ));
                    } else {
                        $('#lista-requerimientos-devueltos').prepend(
                            '<li> No hay requerimientos para re-derivar </li>'
                        );
                    }
                },
                error: function (errormessage) {
                    console.log(errormessage);
                    alert('not working');
                }
            });
    })
    $('#boton-manual').click(function() {
        $.ajax({
                type: 'POST',
                url: '" . Url::to(['/mds_legales_oficio/guardarlogmanualusuario']) . "', 
                data: { },

                success: function (success) {
                    if (success) {
                        window.open('" . Url::base() . "/instructivos/instructivo_legales.pdf', '_blank');
                    } else {
                        console.log(errormessage);
                        alert('Ocurrió un error');
                    }
                },
                error: function (errormessage) {
                    console.log(errormessage);
                    alert('Ocurrió un error');
                }
            });
    })

    $('.button-like-link').click(function() {
        const idlegalesoficio = $(this).attr('data-idlegalesoficio');
        const tituloModal = 'Agregar archivos - Requerimiento #' + idlegalesoficio;
        $('#titulo-modal-agregar-archivos').html(tituloModal);
        $('#idlegalesoficio-agregar-archivo').val(idlegalesoficio);
        $('#ARCHIVO_TIPO').val('').trigger('change');
    });
"
);

Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]);

Modal::end();

$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);

/*Se llama a la funcion js obtenerAdjuntos*/
$paramAdjunto = "let adjuntos =''; let adjuntos_oficio = ''";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerAdjuntos');

?>