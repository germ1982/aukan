<?php

namespace app\controllers;

use app\models\Mds_legales_respuesta;
use app\models\Mds_legales_respuesta_estado;
use app\models\Mds_legales_archivo;
use app\models\Mds_seg_item;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\Mds_legales_oficio;

class Mds_legales_respuesta_estadoController extends \yii\web\Controller
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
                    'enviado',
                    'descargar',
                    'comprobante',
                    'nota',
                    'rechazar',
                    'aprobar',
                ],
                'rules' => [
                    [
                        'actions' => ['store'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_ACCIONAR_RESPUESTA, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['descargar', 'comprobante', 'nota', 'rechazar', 'aprobar'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_ENVIAR_RESPUESTA, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
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
        $estado = Yii::$app->request->post()['estado'];
        $estadoId = $this->getEstado($estado);
        $respuestaId = Yii::$app->request->post()['idlegalesrespuesta'];
        $respuesta = Mds_legales_respuesta::findOne($respuestaId);
        $idOficio = $respuesta->oficio->idlegalesoficio;
        $obs = Yii::$app->request->post()['observaciones'];
        $fechaIni = date('Y-m-d H:i:s');
        Mds_legales_respuesta_estado::actualizarFechaFinUltimoEstado($respuestaId);
        Mds_legales_respuesta_estado::actualizarEstado($respuestaId, $fechaIni, null, $obs, $estadoId);

        if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
            $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
            $respuesta = Mds_legales_respuesta::find()->where(['idlegalesrespuesta' => $respuestaId])->one();
            $this->storeAdjuntoOtros($adjuntos, $respuesta);
        }

        if ($estadoId === Mds_legales_respuesta_estado::APROBADA) {
            $arrayDocumentos = array();
            $personasVinculadas = $respuesta->oficio->getPersonasVinculadas();
            
            if (count($personasVinculadas)) {
                foreach ($personasVinculadas as $personaVinculada) {
                    if ($personaVinculada->documento) {
                        array_push($arrayDocumentos, $personaVinculada->documento);
                    } else if ($personaVinculada->persona) {
                        array_push($arrayDocumentos, $personaVinculada->persona->documento);
                    }
                }
                
                if (count($arrayDocumentos)) {
                    $externalApiRequest = new ExternalApiRequestController();
                    $externalApiRequest->runneuIntervencionByModulo($arrayDocumentos, Mds_legales_oficio::RUNNEU_API_MODULO, $idOficio, 'create', Mds_legales_oficio::RUNNEU_API_TIPO_RESPUESTA);
                }
            }
        }
        return $this->redirect(['mds_legales_oficio/respuestas', 'idOficio' => $idOficio]);
    }

    public function actionDescargar($idlegalesrespuesta)
    {

        //$fechaIni = date('Y-m-d h:i:s');
        $respuesta = Mds_legales_respuesta::find()->where(['idlegalesrespuesta' => $idlegalesrespuesta])->one();
        $oficio = $respuesta->oficio;
        $content = $this->renderPartial('@app/views/mds_legales_oficio/vinculacion/respuestas_para_enviar/pdf_respuesta', ['respuesta' => $respuesta, 'oficio' => $oficio]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'Respuesta Oficio',
                'SetHeader' => null,
                'SetFooter' => ["Respuesta N° {$respuesta->idlegalesrespuesta} |Subsecretaria de Familia <br> Ministerio de Desarrollo Social y Trabajo | Página {PAGENO} de {nb}"],
            ]
        ]);
        $pdf->marginTop = 5;

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_respuesta', $idlegalesrespuesta, array());

        return $pdf->render();
    }

    public function actionComprobante()
    {
        $respuestaId = Yii::$app->request->post()['idrespuesta_para_comprobante'];
        $respuesta = Mds_legales_respuesta::find()->where(['idlegalesrespuesta' => $respuestaId])->one();
        $comprobante = Yii::$app->request->post()['comprobante'];
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathComprobantes = __DIR__ . '/../web/uploads/legales/comprobantes/';

        if (isset($comprobante) && $comprobante != null) {
            $tmpfile = (Yii::$app->request->post()['comprobante']) ? json_decode(Yii::$app->request->post()['comprobante'], true) : null;
            if ($tmpfile) {
                /*Se mueve el archivo de la carpeta temporal a la carpeta original*/
                $date = date('Y-m-d_H_i_s', time());
                $path_info = pathinfo($tmpfile['temp']);
                $extension = $path_info['extension'];
                $nameFile = "requerimiento_{$respuesta->idlegalesoficio}_{$date}.{$extension}";
                if (rename($pathTemp . $tmpfile['temp'], $pathComprobantes . $nameFile)) {
                    $fechaIni = date('Y-m-d H:i:s');
                    Mds_legales_respuesta_estado::actualizarFechaFinUltimoEstado($respuestaId);
                    Mds_legales_respuesta_estado::actualizarEstado($respuestaId, $fechaIni, null, null, Mds_legales_respuesta_estado::ENVIADA);
                    $respuesta->entregado = 1;
                    $respuesta->comprobante = $nameFile;
                    $respuesta->nro_nota = isset(Yii::$app->request->post()['nro_nota']) ? Yii::$app->request->post()['nro_nota'] : NULL;
                    $respuesta->observacion_final = isset(Yii::$app->request->post()['observacion_final']) ? Yii::$app->request->post()['observacion_final'] : NULL;
                    $respuesta->update();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_respuesta', $respuesta->idlegalesrespuesta, $respuesta->getAttributes());
                    Yii::$app->session->setFlash('success', "Se cargo correctamente el archivo, la respuesta paso a estado ENVIADO.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al subir archivo, intente nuevamente.");
                }
            }
            return $this->redirect(['mds_legales_oficio/vinculacionenviar']);
        } else {
            Yii::$app->session->setFlash('error', "Error al subir archivo, intente nuevamente.");
            return $this->redirect(['mds_legales_oficio/vinculacionenviar']);
        }
    }

    public function actionNota()
    {
        $respuestaId = Yii::$app->request->post()['idrespuesta_para_comprobante_nota'];
        $respuesta = Mds_legales_respuesta::find()->where(['idlegalesrespuesta' => $respuestaId])->one();
        $nota = Yii::$app->request->post()['nota'];
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathNotas = __DIR__ . '/../web/uploads/legales/notas/';

        if (isset($nota) && $nota != null && $respuesta) {
            $tmpfile = (Yii::$app->request->post()['nota']) ? json_decode(Yii::$app->request->post()['nota'], true) : null;
            if ($tmpfile) {
                $path_info = pathinfo($tmpfile['temp']);
                $extension = $path_info['extension'];

                /*Se mueve el archivo de la carpeta temporal a la carpeta original*/
                $date = date('Y-m-d_H_i_s', time());
                $nameFile = "requerimiento_{$respuesta->idlegalesoficio}_{$date}.{$extension}";

                if (rename($pathTemp . $tmpfile['temp'], $pathNotas . $nameFile)) {

                    $respuesta->nota = $nameFile;
                    $respuesta->nro_nota_dependencia = Yii::$app->request->post()['nro_nota_dependencia'];
                    $respuesta->nro_vinculacion_judicial = Yii::$app->request->post()['nro_vinculacion_judicial'];

                    Yii::$app->session->setFlash('success', "Se cargo correctamente el archivo/nota.");
                    $respuesta->update();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_respuesta', $respuesta->idlegalesrespuesta, $respuesta->getAttributes());
                } else {
                    Yii::$app->session->setFlash('error', "Error al subir archivo, intente nuevamente.");
                }
            }
        } else {
            Yii::$app->session->setFlash('error', "Error al subir archivo/nota, intente nuevamente.");
        }
        return $this->redirect(['mds_legales_oficio/vinculacionenviar']);
    }

    public function actionRechazar()
    {
        $respuestaId = Yii::$app->request->post()['idlegalesrespuesta_para_rechazar'];
        $obs = Yii::$app->request->post()['observaciones'];
        $fechaIni = date('Y-m-d H:i:s');
        Mds_legales_respuesta_estado::actualizarFechaFinUltimoEstado($respuestaId);
        Mds_legales_respuesta_estado::actualizarEstado($respuestaId, $fechaIni, null, $obs, Mds_legales_respuesta_estado::RECHAZADA);
        return $this->redirect(['mds_legales_oficio/vinculacionenviar']);
    }

    public function getEstado($labelEstado)
    {
        //$idestado = ;
        switch ($labelEstado) {
            case "observar":
                $idestado = Mds_legales_respuesta_estado::OBSERVADA;
                break;
            case "rechazar":
                $idestado = Mds_legales_respuesta_estado::RECHAZADA;
                break;
            case "aprobar":
                $idestado = Mds_legales_respuesta_estado::APROBADA;
                break;
        }
        return $idestado;
    }

    public function actionAprobar()
    {
        $respuestaId = Yii::$app->request->post()['idrespuesta_para_aprobar'];
        $respuesta = Mds_legales_respuesta::find()->where(['idlegalesrespuesta' => $respuestaId])->one();

        $fechaIni = date('Y-m-d H:i:s');
        Mds_legales_respuesta_estado::actualizarFechaFinUltimoEstado($respuestaId);
        Mds_legales_respuesta_estado::actualizarEstado($respuestaId, $fechaIni, null, null, Mds_legales_respuesta_estado::ENVIADA);
        $respuesta->entregado = 1;
        $respuesta->nro_nota = isset(Yii::$app->request->post()['nro_nota']) ? Yii::$app->request->post()['nro_nota'] : NULL;
        $respuesta->observacion_final = isset(Yii::$app->request->post()['observacion_final_aprobado']) ? Yii::$app->request->post()['observacion_final_aprobado'] : NULL;
        Yii::$app->session->setFlash('success', "La respuesta se actualizó a estado ENVIADA.");
        $respuesta->update();
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_respuesta', $respuesta->idlegalesrespuesta, $respuesta->getAttributes());

        return $this->redirect(['mds_legales_oficio/vinculacionenviar']);
    }

    public function storeAdjuntoOtros($adjuntos, $model)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathReproam = __DIR__ . '/../web/uploads/legales/respuestas_supervisor/';
        $date = date('Y-m-d_H_i_s', time());
        foreach ($adjuntos as $key => $adjunto) {
            $path_info = pathinfo($adjunto["temp"]);
            $extension = $path_info['extension'];
            $nameFile = "respuesta_supervisor_{$model->idlegalesrespuesta}_{$date}_{$key}.{$extension}";
            if (rename($pathTemp . $adjunto['temp'], $pathReproam  . $nameFile)) {
                Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_legales_respuesta', 'respuesta_supervisor', $model->idlegalesrespuesta, $nameFile);
            }
        }
    }
}
