<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;

use app\models\Mds_seg_permiso;
use app\models\Mds_seg_item;
use app\models\Mds_legales_oficio;

class Mds_notificacionController extends Controller
{
    public function actionIndex()
    {
        $usuario = Yii::$app->user->identity;
        if ($usuario) {
            $idUsuario = $usuario->idusuario;
            $permisosUsuario = Mds_seg_permiso::getPermisosByIdUsuario($idUsuario)->all();
            $dataNotificaciones = array();

            foreach ($permisosUsuario as $permiso) {
                switch ($permiso->iditem) {
                    case Mds_seg_item::MODULO_LEGALES_NOTIFICACIONES:
                        $dataNotificaciones['LEGALES']['titulo'] = 'Legales';
                        break;
                    default:
                        break;
                }
            }

            if (!empty($dataNotificaciones)) {
                return $this->render('index', [
                    'dataNotificaciones' => $dataNotificaciones
                ]);
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
    }

    public function actionRendervista($modulo)
    {
        switch (strtoupper($modulo)) {
            case 'LEGALES':
                $total = 0;
                $notificacionesLegales = Mds_legales_oficio::getNotificaciones('controller');
                $notificaciones = [
                    'oficiosConRespuestasVistas' => [
                        'titulo' => 'Respuestas vistas'
                    ],
                    /*
                    'requerimientosConObservacionFinal' => [
                        'titulo' => 'Respuestas enviadas con observación final'
                    ],*/
                    'vencimientoPlazoOficios' => [
                        'titulo' => 'Próximas a vencer / vencidas recientemente'
                    ],
                    'oficiosSinRespuestas' => [
                        'titulo' => 'Requieren respuesta'
                    ],
                    'respuestasObservadas' => [
                        'titulo' => 'Respuestas observadas'
                    ],
                    'respuestasSinSupervisar' => [
                        'titulo' => 'Requieren supervisión'
                    ],
                    'oficiosRespuestasAprobadasNoEnviadas' => [
                        'titulo' => 'Respuestas no supervisadas'
                    ],
                    'oficiosSinDerivarAUsuarios' => [
                        'titulo' => 'Requieren derivación'
                    ],
                    'oficiosParaReDerivar' => [
                        'titulo' => 'Requieren re-derivación'
                    ],
                    'respuestasRechazadas' => [
                        'titulo' => 'Devueltas por Equipo de Supervisión Final'
                    ],
                    'oficiosParaReDerivarASupervisor' => [
                        'titulo' => 'Requieren re-derivación a supervisión'
                    ],
                ];

                $poseeNotificacion = false;
                foreach ($notificaciones as $key => $value) {
                    $notificaciones[$key]['notificaciones'] = [];
                    if (key_exists($key, $notificacionesLegales['notificaciones']) && !empty($notificacionesLegales['notificaciones'][$key])) {
                        $poseeNotificacion = true;
                        $notificaciones[$key]['notificaciones'] = $notificacionesLegales['notificaciones'][$key];
                        $total += count($notificacionesLegales['notificaciones'][$key]);
                    }
                }

                if (!$poseeNotificacion) {
                    $notificaciones = array();
                }

                $html = $this->renderPartial('/mds_notificacion/modulos/mds_legales', ['notificaciones' => $notificaciones, 'total' => $total]);
                break;
            default:
                $html = '';
                break;
        }


        return $html;
    }
}
