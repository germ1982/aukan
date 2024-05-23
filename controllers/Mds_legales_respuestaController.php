<?php

namespace app\controllers;

use app\models\Mds_legales_archivo;
use app\models\Mds_legales_derivacion;
use app\models\Mds_legales_oficio;
use app\models\Mds_legales_respuesta;
use app\models\Mds_legales_respuesta_estado;
use app\models\Mds_legales_profesionales_intervinientes;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use app\models\Mds_seg_item;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use app\components\AccessRule;

class Mds_legales_respuestaController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['create', 'store', 'mis-respuestas'],
                'rules' => [
                    [
                        'actions' => ['create', 'store', 'mis-respuestas'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_RESPONDER_REQUERIMIENTO, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate($idDerivacion, $idrespuesta = null)
    {
        $estadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;
        $estadoAprobada = Mds_legales_respuesta_estado::APROBADA;

        $usuarioAuth = Yii::$app->user->identity;
        $model = new Mds_legales_respuesta();
        $derivacionOriginal = Mds_legales_derivacion::find()->where(['idlegalesderivacion' => $idDerivacion])->one();

        if ($derivacionOriginal) {
            $idOficio = $derivacionOriginal->idlegalesoficio;
            $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $idOficio, 'activo' => 1])->one();

            if ($oficio && count($oficio->getLastRespuestasEstadoByEstado($estadoAprobada)) === 0 && count($oficio->getLastRespuestasEstadoByEstado($estadoPendiente)) === 0) {
                $derivacion = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $oficio->idlegalesoficio, 'idusuario' => $usuarioAuth->idusuario, 'supervisor' => 0, 'activo' => 1])->one();

                if ($derivacion && Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_RECEPTOR)) {
                    $profesionales = Mds_seg_usuario::find('idusuario', 'nombre', 'apellido')->where('idcontacto IS NOT NULL')->andWhere(['=', 'activo', 1])->orderBy(['apellido' => SORT_ASC])->all();
                    $respuestaObservada = null;
                    /* Si esta seteado el id respuesta significa que es una respuesta a observar d otra respuesta*/
                    if ($idrespuesta != null) {
                        $respuestaObservada = Mds_legales_respuesta::find()->where(['idlegalesrespuesta' => $idrespuesta])->one();
                        /*Si el estado actual de la respuesta no es aprobado, no permite editarla*/
                        if ($respuestaObservada->ultimoEstado->estado != Mds_legales_respuesta_estado::OBSERVADA) {
                            Yii::$app->session->setFlash('error', "No se puede editar una respuesta que está en <strong>ESTADO ENVIADO</strong>.");
                            return $this->redirect(['mds_legales_oficio/index']);
                        }
                    }

                    $devoluciones = Mds_legales_derivacion::find()->where("idlegalesoficio =$idOficio")->andWhere('fecha_usu_no_corresponde IS NOT NULL')->orderBy(['fecha_usu_no_corresponde' => SORT_ASC])->all();

                    return $this->render('create', [
                        'listaProfesionales' => $profesionales,
                        'oficio' => $oficio,
                        'derivacionOriginal' => $derivacionOriginal,
                        'model' => $model,
                        'respuestaObservada' => $respuestaObservada,
                        'devoluciones' => $devoluciones,
                    ]);
                } else {
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionStore()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $parametroIndex = isset($_SESSION["MdsLegalesOficioIndexParametroNotificacion"]) ? $_SESSION["MdsLegalesOficioIndexParametroNotificacion"] : null;
        $redirect = $parametroIndex ? ['mds_legales_oficio/index', 'notificacion' => $parametroIndex] : ['mds_legales_oficio/index'];

        $usuarioAuth = Yii::$app->user->identity;
        $idlegalesOficio = Yii::$app->request->post()['idlegalesoficio'];
        $idRespuestaCorreccion = (Yii::$app->request->post()['idrespuestacorreccion'] != "")  ?  Yii::$app->request->post()['idrespuestacorreccion'] : null;
        $modelRespuesta = new Mds_legales_respuesta();
        $modelRespuesta->idlegalesoficio = $idlegalesOficio;
        $modelRespuesta->idusuario = $usuarioAuth->idusuario;
        $modelRespuesta->texto_repuesta = Yii::$app->request->post()['Mds_legales_respuesta']['texto_repuesta'];
        $modelRespuesta->fecha_carga = date('Y-m-d H:i:s');

        if ($modelRespuesta->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            if ($modelRespuesta->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_respuesta', $modelRespuesta->idlegalesrespuesta, $modelRespuesta->getAttributes());

                if ($idRespuestaCorreccion) {
                    $arrayAdjuntosEliminadosId = array();
                    if (Yii::$app->request->post()['adjuntos_eliminados'] != "") {
                        // $this->eliminarAdjuntos(Yii::$app->request->post()['adjuntos_eliminados']);
                        $arrayAdjuntosEliminadosId = json_decode(Yii::$app->request->post()['adjuntos_eliminados'], true);
                    }

                    $modelRespuestaAnterior = Mds_legales_respuesta::find()->where(['idlegalesrespuesta' => $idRespuestaCorreccion])->one();
                    $modelRespuestaAnterior->idrespuestacorreccion = $modelRespuesta->idlegalesrespuesta;
                    $modelRespuestaAnterior->update();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_respuesta', $modelRespuestaAnterior->idlegalesrespuesta, $modelRespuestaAnterior->getAttributes());

                    $this->replicarArchivosANuevaRespuesta($modelRespuestaAnterior, $modelRespuesta, $arrayAdjuntosEliminadosId);
                }

                if (isset(Yii::$app->request->post()['Mds_legales_respuesta']['adjuntos']) && Yii::$app->request->post()['Mds_legales_respuesta']['adjuntos']) {
                    $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
                    $pathRespuestas = __DIR__ . '/../web/uploads/legales/respuestas/';
                    $date = date('Y-m-d_H_i_s', time());

                    $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_respuesta']['adjuntos'], true);
                    foreach ($adjuntos as $key => $adjunto) {
                        $path_info = pathinfo($adjunto['temp']);
                        $extension = $path_info['extension'];
                        $nameFile = "requerimiento_{$modelRespuesta->idlegalesoficio}_{$date}_{$key}.{$extension}";
                        if (rename($pathTemp . $adjunto['temp'], $pathRespuestas . $nameFile)) {
                            Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_legales_respuesta', 'respuesta', $modelRespuesta->idlegalesrespuesta, $nameFile);
                        }
                    }
                }

                $fechaIni = date('Y-m-d H:i:s');
                $fechaFin = null;
                $obs = null;
                Mds_legales_respuesta_estado::actualizarEstado($modelRespuesta->idlegalesrespuesta, $fechaIni, $fechaFin, $obs, Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION);
                if (isset(Yii::$app->request->post()['profesionales'])) {
                    $this->guardarProfesionalesIntervinientes($modelRespuesta, Yii::$app->request->post()['profesionales']);
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', "Se generó correctamente la respuesta al requerimiento.");
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', "Error al generar respuesta.");
            }
        }

        return $this->redirect($redirect);
    }

    public function actionMisRespuestas($idOficio)
    {
        $userAuth =  Yii::$app->user->identity;
        $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $idOficio])->one();
        $respuestas = Mds_legales_respuesta::find()->where(['idlegalesoficio' => $idOficio, 'idusuario' => $userAuth->idusuario])->orderBy([
            'idlegalesrespuesta' => SORT_DESC
        ])->all();
        //$oficio = Mds_legales_oficio::find()->where(['idlegalesoficio'=>$idOficio])->one();
        $consultaDerivador = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $idOficio, 'idusuario' => $userAuth->idusuario, 'activo' => 1, 'fecha_usu_no_corresponde' => null, 'supervisor' => 0])->one();

        $devoluciones = Mds_legales_derivacion::find()->where("idlegalesoficio =$idOficio")->andWhere('fecha_usu_no_corresponde IS NOT NULL')->orderBy(['fecha_usu_no_corresponde' => SORT_ASC])->all();
        $esAdminGeneral =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        if ($esAdminGeneral || $consultaDerivador) {
            return $this->render('my_answers', [
                'respuestas' => $respuestas,
                'oficio' => $oficio,
                'devoluciones' => $devoluciones,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function replicarArchivosANuevaRespuesta($modelRespuestaAnterior, $nuevaRespuesta, $arrayAdjuntosEliminadosId = array())
    {
        $adjuntos = $modelRespuestaAnterior->getAdjuntos('replicarArchivos');
        if ($adjuntos) {
            foreach ($adjuntos as $adjunto) {
                $wantUpload = true;
                if (!empty($arrayAdjuntosEliminadosId)) {
                    $index = 0;
                    while ($wantUpload && $index < count($arrayAdjuntosEliminadosId)) {
                        if ($adjunto->idlegalesarchivo == $arrayAdjuntosEliminadosId[$index]) {
                            //Si en los eliminados coincide el id del archivo entonces no queremos replicarlo
                            $wantUpload = false;
                        }
                        $index++;
                    }
                }
                if ($wantUpload) {
                    Mds_legales_archivo::saveFile($adjunto->nombre, 'mds_legales_respuesta', 'respuesta', $nuevaRespuesta->idlegalesrespuesta, $adjunto->path);
                }
            }
        }
        //Se comento porque generaba confusion, si se necesita descomentar
        // $adjuntosRespuestaSupervisor =  $modelRespuestaAnterior->getAdjuntosRespuestaSupervisor();
        // if ($adjuntosRespuestaSupervisor) {
        //     foreach ($adjuntosRespuestaSupervisor as $adjunto) {
        //         $arrayPaths = explode('/', $adjunto->path);
        //         $pathKey = count($arrayPaths) - 1;
        //         $path = $arrayPaths[$pathKey];
        //         Mds_legales_archivo::saveFile($adjunto->nombre, 'mds_legales_respuesta', 'respuesta_supervisor', $nuevaRespuesta->idlegalesrespuesta, $path);
        //     }
        // }
    }

    public function eliminarAdjuntos($adjuntosAEliminar)
    {
        $adjuntosEliminados = json_decode($adjuntosAEliminar, true);
        foreach ($adjuntosEliminados as $idAdjunto) {
            $modelArchivo = Mds_legales_archivo::findOne($idAdjunto);
            $modelArchivo->activo = 0;
            $modelArchivo->save();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_archivo', $modelArchivo->idlegalesarchivo, $modelArchivo->getAttributes());
        }
    }

    public function guardarProfesionalesIntervinientes($respuesta, $profesionales)
    {
        foreach ($profesionales as $user_id) {
            $model = new Mds_legales_profesionales_intervinientes;
            $model->idrespuesta = $respuesta->idlegalesrespuesta;
            $model->idusuario = $user_id;
            $model->save();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_profesionales_intervinientes', $model->idlegalesprofesionalesinterviniente, $model->getAttributes());
        }
    }
}
