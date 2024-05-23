<?php

namespace app\controllers;

use app\models\Mds_legales_respuesta_visto;
use app\models\Mds_legales_respuesta;
use app\models\Mds_sys_log;
use app\models\Mds_seg_item;
use Yii;
use yii\filters\AccessControl;
use app\components\AccessRule;

class Mds_legales_respuesta_vistoController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => [
                    'store',
                ],
                'rules' => [
                    [
                        'actions' => ['store'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_ACCIONAR_RESPUESTA, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionStore()
    {
        $modelRespuestaVisto = new Mds_legales_respuesta_visto();
        $modelRespuesta = new Mds_legales_respuesta();

        $idRespuesta = Yii::$app->request->post()['idlegalesrespuesta_visto'];

        $respuesta = $modelRespuesta->find()->select("idlegalesoficio")->where("idlegalesrespuesta = $idRespuesta")->one();
        $idOficio = $respuesta['idlegalesoficio'];

        $fechaCarga = date('Y-m-d H:i:s');
        $usuarioAuth = Yii::$app->user->identity;

        $modelRespuestaVisto->idlegalesrespuesta = $idRespuesta;
        $modelRespuestaVisto->idlegalesoficio = $idOficio;
        $modelRespuestaVisto->idusuario = $usuarioAuth->idusuario;
        $modelRespuestaVisto->fecha_carga = $fechaCarga;
        $modelRespuestaVisto->activo = 1;
        $modelRespuestaVisto->save();

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_respuesta_visto', $modelRespuestaVisto->idlegalesrespuestavisto, $modelRespuestaVisto->getAttributes());

        return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
    }
}
