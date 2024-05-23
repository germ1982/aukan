<?php

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ris_risneu */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity;
$id = $usuario != null ? $usuario->idusuario : null;
if (!isset($id) || $id == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}

$idPermisos = Mds_seg_permiso::getPermisosRisneuByIdUsuario($id)->all();
$modulo_encuestador = false;
$indexPermisos = 0;
$idPermisosLength = count($idPermisos);
//De agregarse mas casos al switch, comentar el while y descomentar el foreach
// foreach ($idPermisos as $r) :
while ($indexPermisos < $idPermisosLength && !$modulo_encuestador) {
    $r = $idPermisos[$indexPermisos];
    switch ($r->iditem) {
        case Mds_seg_item::MODULO_RIS_ENCUESTADOR:
            //if (!$modulo_encuestador) {
            $modulo_encuestador = true;
            // }
            break;
    }
    $indexPermisos++;
}
// endforeach;

$this->title = 'Ver RISNeu N° ' . $model->idrisneu;

?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }

    .botones-container {
        display: flex;
    }

    .boton-guardar {
        margin-left: auto;
    }

    .boton-guardar-salir {
        margin-left: 1rem;
    }
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <input type="hidden" id="IS_CREATE" name="IS_CREATE" value="<?= $model->isNewRecord ? true : false ?>">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="sds-ris-risneu-form">
                    <?php $form = ActiveForm::begin(['id' => 'FORM_CREAR_RISNEU']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo Tabs::widget([
                                'items' => [
                                    [
                                        'label' => 'Encuestador / Domicilio',
                                        'options' => ['class' => 'tabs-primary'],
                                        'content' => $this->render('_form_enc_dom', [
                                            'form' => $form, 'model' => $model, 'modulo_encuestador' => $modulo_encuestador,
                                            'origen' => $origen,
                                            'encuestadores' => $encuestadores,
                                            'realizadoPor' => $realizadoPor,
                                            'localidades' => $localidades,
                                            'barrios' => $barrios,
                                            'areas' => $areas,
                                            'calles' => $calles,
                                            'callesInterseccion' => $callesInterseccion,
                                            'provincias' => $provincias,
                                            'existeJefe' => $existeJefe,
                                            'jefeNombreCompleto' => $jefeNombreCompleto,
                                            'view' => true
                                        ]),
                                        'active' => true
                                    ],
                                    [
                                        'label' => 'Grupo Conviviente',
                                        'content' => $this->render('_form_grup_fam', [
                                            'form' => $form,
                                            'model' => $model,
                                            'view' => true
                                        ]),
                                        'active' => false
                                    ],
                                    [
                                        'label' => 'Alimentación / Vivienda',
                                        'content' => $this->render('_form_alim_viv', [
                                            'form' => $form, 'model' => $model,
                                            'tipos_alimentacion' => $tipos_alimentacion,
                                            'risneu_alims' => $risneu_alims,
                                            'selectViviendaUso' => $selectViviendaUso,
                                            'selectViviendaUbicacion' => $selectViviendaUbicacion,
                                            'selectViviendaPropiedad' => $selectViviendaPropiedad,
                                            'selectViviendaTipo' => $selectViviendaTipo,
                                            'selectViviendaPiso' => $selectViviendaPiso,
                                            'selectViviendaObtieneAgua' => $selectViviendaObtieneAgua,
                                            'selectViviendaAgua' => $selectViviendaAgua,
                                            'selectViviendaBano' => $selectViviendaBano,
                                            'selectViviendaDesague' => $selectViviendaDesague,
                                            'selectViviendaIluminacion' => $selectViviendaIluminacion,
                                            'selectViviendaMedidor' => $selectViviendaMedidor,
                                            'selectViviendaCalefaccion' => $selectViviendaCalefaccion,
                                            'selectViviendaCocina' => $selectViviendaCocina,
                                            'selectViviendaTecho' => $selectViviendaTecho,
                                            'selectViviendaParedes' => $selectViviendaParedes,
                                            'view' => true
                                        ]),
                                        'active' => false
                                    ],
                                ],
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 2%">
                        <div class="col-md-1">
                            <a class="btn btn-info" href="index.php?r=sds_ris_risneu&oficial=<?= $model->oficial ?>">Volver </a>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php

Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    'id' => 'modal_abm',
    'size' => 'modal-md',
]);

echo "<div id='content_abm'></div>";

Modal::end();

$this->registerJS( // register jQuery extension

    "
        jQuery.extend(jQuery.expr[':'], {
        focusable: function (el, index, selector) {          
            /* return ($(el).is(':input') || $(el).attr('tabindex')>0)
            || ($(el).is('a,button') && $(el).attr('tabindex')>0); */  
            return $(el).attr('tabindex')>0;
        }
    });
    
    /* $( ':focusable' ).css( 'border-color', '#FF9933' );  */
    
    $(document).on('keypress', 'input,select,a,button', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            // Get all focusable elements on the page
            var canfocus = $(':focusable');            
            var index = canfocus.index(this) + 1;
            if (index >= canfocus.length) index = 0;      
            canfocus.eq(index).focus();            
        }
    });"
);

?>