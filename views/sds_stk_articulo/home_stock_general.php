<?php

use app\models\Mds_org_organismo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use yii\widgets\ActiveForm;
use app\models\Sds_com_configuracion;
use app\models\Sds_stk_entrega;
use app\models\View_stock_detalle_ent_resp;
use app\models\View_stock_detalle_oc;
use app\models\View_stock_inversion;
use yii\bootstrap\Tabs;

$model = new Sds_com_configuracion();
$model_entrega = new Sds_stk_entrega();
$model_entrega->fecha_hora = date('d/m/Y');
$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$idcontacto = Yii::$app->user->identity->idcontacto;
$permiso_consultar = Mds_seg_permiso::findBySql("SELECT * from mds_seg_permiso 
                                                where idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::STK_CONSULTAR_MINIMO . ")")->one();
$permiso_consultar = $permiso_consultar != null ? 1 : 0;
$id_organismo = 0;
if ($usuario->organismo_stock) {
    $id_organismo = $usuario->organismo_stock;
    $model->idconfiguracion = Mds_org_organismo::findOne($id_organismo)->idrubro;
}
?>


<header class="page-header">
    <h2>Stock General</h2>
</header>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="sds-ris-risneu-form">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo Tabs::widget([
                                'items' => [
                                    [
                                        'label' => 'Stock General',
                                        'options' => ['class' => 'tabs-primary'],
                                        'content' => $this->render('_tab_home_stock_general', [
                                            'form' => $form, 'model' => $model,
                                            'permiso_consultar' => $permiso_consultar,
                                            'id_organismo' => $id_organismo,
                                            'model_entrega' => $model_entrega,
                                        ]),
                                        'active' => true
                                    ],
                                    [
                                        'label' => 'Indicadores',
                                        'options' => ['class' => 'tabs-primary'],
                                        'content' => $this->render('_tab_home_indicadores', [
                                            'form' => $form, 'model' => new View_stock_detalle_oc(),
                                            'permiso_consultar' => $permiso_consultar,
                                            'id_organismo' => $id_organismo,
                                        ]),
                                        'active' => false
                                    ],
                                    [
                                        'label' => 'Entregado ($)',
                                        'options' => ['class' => 'tabs-primary'],
                                        'content' => $this->render('_tab_home_inversiones', [
                                            'form' => $form, 'model' => new View_stock_inversion(),
                                            'permiso_consultar' => $permiso_consultar,
                                            'id_organismo' => $id_organismo,
                                        ]),
                                        'active' => false
                                    ],
                                    [
                                        'label' => 'Entregado (Resp.)',
                                        'options' => ['class' => 'tabs-primary'],
                                        'content' => $this->render('_tab_home_responsables', [
                                            'form' => $form, 'model' => new View_stock_detalle_ent_resp(),
                                            'permiso_consultar' => $permiso_consultar,
                                            'id_organismo' => $id_organismo,
                                        ]),
                                        'active' => false
                                    ],
                                ],
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
    mostrar_para_mobile();
    var tiempo = 100;

    refrescar();


    function mostrar_para_mobile() {
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            // true for mobile device
            //alert("mobile device");
            var aux = "index.php?r=view_stock_deposito";
            location.href = aux

        } else {
            // false for not mobile device
            //alert("not mobile device");
        }
    }   
</script>