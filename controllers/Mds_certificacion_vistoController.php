<?php

namespace app\controllers;

use app\models\Mds_certificacion_visto;
use app\models\Mds_certificacion;
use app\models\Mds_sys_log;
use app\models\Mds_seg_item;
use Yii;
use yii\filters\AccessControl;
use app\components\AccessRule;

class Mds_certificacion_vistoController extends \yii\web\Controller
{
    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::class,
    //             'ruleConfig' => [
    //                 'class' => AccessRule::class,
    //             ],
    //             'only' => [
    //                 'store',
    //             ],
    //             'rules' => [
    //                 [
    //                     'actions' => ['store'],
    //                     'allow' => true,
    //                     'roles' => [
    //                         Mds_seg_item::MODULO_CERTIFICACION
    //                     ],
    //                 ],
    //             ],
    //         ],
    //     ];
    // }

    public function actionStore($llamadoDesde, $idCertificaciones = null)
    {
        /*
        Si es llamado por el view: se envia por parametro 'idcertificacion_visto' con el idcertificacion
        Si es llamado por el index, se envia idCertificaciones con los idcertificaciones separados por comas
        */
        $fechaCarga = date('Y-m-d H:i:s');
        $usuarioAuth = Yii::$app->user->identity;
        $success = true;
        $indexCertificaciones = 0;
        $messageSuccess = " Se marcaron correctamente como vistas las certificaciones: ";
        $idsCertificaciones = "";

        if ($llamadoDesde === 'view' && empty($idCertificaciones) && isset(Yii::$app->request->post()['idcertificacion_visto'])) {
            //Si es llamado por el view
            $idCertificaciones = [];
            array_push($idCertificaciones, Yii::$app->request->post()['idcertificacion_visto']);
        } else if ($llamadoDesde === 'index') {
            //Si es llamado por el index
            $idCertificaciones = explode(",", $idCertificaciones);
        }

        if (!empty($idCertificaciones)) {
            $countIdCertificaciones = count($idCertificaciones);
            while ($indexCertificaciones < $countIdCertificaciones && $success) {
                $idCertificacion = $idCertificaciones[$indexCertificaciones];

                $modelCertificacionVisto = new Mds_certificacion_visto();
                $modelCertificacionVisto->idcertificacion = $idCertificacion;
                $modelCertificacionVisto->idusuario = $usuarioAuth->idusuario;
                $modelCertificacionVisto->fecha_carga = $fechaCarga;
                $modelCertificacionVisto->activo = 1;

                if ($modelCertificacionVisto->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_visto', $modelCertificacionVisto->idcertificacionvisto, $modelCertificacionVisto->getAttributes());

                    if ($countIdCertificaciones === 1) {
                        $messageSuccess = " Se marcó correctamente como vista la certificación: #$idCertificacion.";
                    } else {
                        $idsCertificaciones .= "#$idCertificacion";
                        if ($indexCertificaciones !== $countIdCertificaciones - 1) {
                            $idsCertificaciones .= ", ";
                        } else {
                            $idsCertificaciones .= ".";
                        }
                    }
                } else {
                    $success = false;
                }

                $indexCertificaciones++;
            }
        } else {
            $success = false;
        }

        if ($success) {
            if ($countIdCertificaciones > 1) {
                $messageSuccess .= $idsCertificaciones;
            }
            Yii::$app->session->setFlash('success', $messageSuccess);
        } else {
            Yii::$app->session->setFlash('error', " Error al marcar como vista/s la/s certificación/es.");
        }

        if ($llamadoDesde === 'view') {
            $sector = array_key_exists('sector_visto', Yii::$app->request->post()) ? Yii::$app->request->post()['sector_visto'] : null;
            return $this->redirect(['mds_certificacion/view', 'id' => $idCertificacion, 'sector' => $sector]);
        } else {
            $url = Yii::$app->request->headers['referer'];
            parse_str($url, $params);
            return $this->redirect(['mds_certificacion/index', 'area' => $params['area']]);
        }
    }
}
