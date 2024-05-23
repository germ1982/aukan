<?php

namespace app\controllers;

use Yii;

use app\models\Mds_certificacion;
use app\models\Mds_certificacion_nota;
use app\models\Mds_sys_log;

use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use app\models\Mds_seg_usuario_rol;

use kartik\mpdf\Pdf;

class Mds_certificacion_notaController extends Controller
{

    /**
     * {@inheritdoc}
     */
    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::class,
    //             'only' => ['index'],
    //             'rules' => [
    //                 [
    //                     'actions' => ['index'],
    //                     'allow' => true,
    //                     'roles' => ['@'],
    //                     'matchCallback' => function () {
    //                         return (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL));
    //                     }
    //                 ],
    //             ],
    //         ],
    //     ];
    // }


    public function actionCreate()
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
        ) {
            $idCertificacion = Yii::$app->request->queryParams['idcertificacion'];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $nota = Mds_certificacion_nota::findByCertificacionId($idCertificacion);
            $certificacion = Mds_certificacion::find()->where("idcertificacion = $idCertificacion")->one();
            $beneficiarioApellido = mb_strtoupper($certificacion->beneficiario->apellido);
            $beneficiarioNombre = mb_strtoupper($certificacion->beneficiario->nombre);
            $beneficiarioNombreCompleto = "$beneficiarioApellido $beneficiarioNombre";

            if (!$nota) {
                $nota = new Mds_certificacion_nota();
                $beneficiarioDocumento = $certificacion->beneficiario->documento;
                $certificacionMonto = $certificacion->monto0->monto;
                $textoNota = "<p style='text-align: justify;'>Tengo el agrado de dirigirme a Ud. a los efectos de solicitar tenga bien gestionar el pago de un aporte no reintegrable en concepto de XXXXXXXXXXX por un monto de <b>$$certificacionMonto (pesos XXXX  XXXX con XXXX centavos)</b> por única vez, a favor de <b>$beneficiarioApellido $beneficiarioNombre DNI: $beneficiarioDocumento</b>, destinado a cubrir necesidades básicas.</p>
            <p style='text-align: justify;'>Esta solicitud es realizada por el equipo interviniente del Centro de Dia Zona Este Lic. En Servicio Social XXXXXX XXXXXXX. El objetivo es poder garantizar tanto la autonomía económica como emocional. A su vez se encuentra intervenida por el Centro de Salud XXXXXXXXXXX por la Lic. XXXXXX XXXXX.</p>
            <p style='text-align: justify;'>Entendiéndose que le corresponde al Ministerio de Desarrollo Social, Organismo de competencia en la aplicación de la Ley 2785, <b>“DE REGIMEN DE PROTECCION INTEGRAL PARA PREVENIR, SANCIONAR Y ERRADICAR LA VIOLENCIA FAMILIAR”</b>, garantizar el cumplimiento del Art 7, inciso A, que implica el apoyo y resguardo de la víctima de violencia familiar, gestionando recursos y prestaciones sociales e inciso D, el cual implica la inclusión, en planes y programas de asistencia.</p></span>
            <p style='text-align: right;'>Sin otro particular saludo a Ud. Atte.</p>";

                $nota->referencia = "por <b>$$certificacionMonto</b> a favor de <b>{$beneficiarioNombreCompleto}, DNI: {$beneficiarioDocumento}.-</b>";
                $nota->destinatario_nombre = Mds_certificacion_nota::DESTINATARIO_NOMBRE;
                $nota->destinatario_direccion = Mds_certificacion_nota::DESTINATARIO_DIRECCION;
                $nota->nota = $textoNota;
            }

            $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
            $botonGuardar = Html::button('Guardar', ['class' => 'btn btn-success', 'id' => 'btnGuardarNota', 'type' => "submit"]);
            $urlImprimir =  Url::to(['/mds_certificacion_nota/imprimir', 'idcertificacionnota' => $nota->idcertificacionnota, 'idcertificacion' => $idCertificacion]);
            $botonImprimir = Html::a('Imprimir', $urlImprimir, [
                'role' => 'post', 'data-pjax' => 0,
                'data-toggle' => 'tooltip',
                'title' => ('Exportar PDF'),
                'target' => '_blank',
                'class' => 'btn btnPrint',
                'id' => 'btnImprimirNota'
            ]);

            $buttons = "$botonVolver";
            $buttons .= $nota->isNewRecord ? "" : " $botonImprimir";
            $buttons .= " $botonGuardar";

            return [
                'title' => "Nota de certificación <b>#$idCertificacion, $beneficiarioNombreCompleto</b>",
                'content' => $this->renderAjax('create', [
                    'model' => $nota,
                    'certificacion' => $certificacion,
                ]),
                'footer' => $buttons
            ];
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionStore($idcertificacionnota = null, $idcertificacion)
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
        ) {
            $request = Yii::$app->request;
            if (!$idcertificacionnota) {
                $model = new Mds_certificacion_nota();
                $model->created_at = date('Y-m-d H:i:s');
                $model->idusuario_carga = Yii::$app->user->id;
                $model->idcertificacion = $idcertificacion;
            } else {
                $model = $this->findModel($idcertificacionnota);
                $model->updated_at = date('Y-m-d H:i:s');
            }
            $certificacion = Mds_certificacion::find()->where("idcertificacion = $idcertificacion")->one();
            $beneficiarioApellido = mb_strtoupper($certificacion->beneficiario->apellido);
            $beneficiarioNombre = mb_strtoupper($certificacion->beneficiario->nombre);
            $beneficiarioNombreCompleto = "$beneficiarioApellido $beneficiarioNombre";

            $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $model->validate()) {
                if ($model->fecha) {
                    $fecha = ArmarDateParaMySql($model->fecha);
                    $fecha = date_create($fecha);
                    $fecha = date_format($fecha, 'Y-m-d');
                    $model->fecha = $fecha;
                }

                $transaction = Yii::$app->db->beginTransaction();
                if ($model->save()) {
                    $transaction->commit();
                    $accion = !$idcertificacionnota ? Mds_sys_log::ACCION_NUEVO : Mds_sys_log::ACCION_EDITAR;

                    Mds_sys_log::guardarLog($accion, 'mds_certificacion_nota', $model->idcertificacionnota, $model->getAttributes());

                    $textoSuccess = !$idcertificacionnota ? 'guardó' : 'actualizó';
                    $content = "<span class='text-success'>Se $textoSuccess exitosamente!</span>";

                    $botonEditar = Html::a('Editar', ['create', 'idcertificacion' => $idcertificacion], ['class' => 'btn btn-primary', 'role' => 'modal-remote']);
                    $urlImprimir =  Url::to(['/mds_certificacion_nota/imprimir', 'idcertificacionnota' => $model->idcertificacionnota, 'idcertificacion' => $idcertificacion]);
                    $botonImprimir = Html::a('Imprimir', $urlImprimir, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => ('Exportar PDF'),
                        'target' => '_blank',
                        'class' => 'btn btnPrint',
                        'id' => 'btnImprimirNota'
                    ]);
                    $botonesFooter = "$botonVolver $botonImprimir $botonEditar";
                    $footer = $botonesFooter;
                } else {
                    $transaction->rollBack();
                    $content = '<span class="text-danger">Error al guardar la nota</span>';
                    $footer = $botonVolver;
                }
            } else {
                $botonGuardar = Html::button('Guardar', ['class' => 'btn btn-success', 'id' => 'btnGuardarNota', 'type' => "submit"]);

                $content = $this->renderAjax('create', [
                    'model' => $model,
                    'certificacion' => $certificacion,
                ]);

                $footer = "$botonVolver $botonGuardar";
            }

            return [
                'title' => "Nota de certificación <b>#$idcertificacion, $beneficiarioNombreCompleto</b>",
                'content' => $content,
                'footer' => $footer
            ];
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionImprimir($idcertificacionnota, $idcertificacion)
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
        ) {
            $usuarioAuth = Yii::$app->user->identity;
            $dateToday = date('d/m/Y H:i:s');
            $nota = $this->findModel($idcertificacionnota);
            $certificacion = Mds_certificacion::find()->where("idcertificacion = $idcertificacion")->one();
            $beneficiarioDocumento = $certificacion->beneficiario->documento;
            $beneficiarioApellido = mb_strtoupper($certificacion->beneficiario->apellido);
            $beneficiarioNombre = mb_strtoupper($certificacion->beneficiario->nombre);
            $beneficiarioNombreCompleto = "$beneficiarioApellido $beneficiarioNombre";
            $beneficiarioNombreCompletoSinEspacios = str_replace(' ', '_', $beneficiarioNombreCompleto);
            $fechaHeader = dateSpanish($nota->fecha);

            $posicionCierreEtiqueta = strpos($nota->referencia, '>');
            $nota->referencia = substr_replace($nota->referencia, '<b>Ref.: </b>', $posicionCierreEtiqueta + 1, 0);
            $content = $this->renderPartial('detalle_nota', [
                'nota' => $nota,
                'fechaHeader' => $fechaHeader,
            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'filename' => "Nota_certificacion_{$idcertificacion}_{$beneficiarioNombreCompletoSinEspacios}_{$beneficiarioDocumento}.pdf",
                'content' => $content,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' =>
                '.kv-heading-1{font-size:18px}
            table{border-collapse: collapse; width: 100%;}
            .titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}
            .parrafo,td{padding: 10px .5rem 5px .5rem}
            div.saltopagina{page-break-after:always}
            .textRight{text-align: right;}
            .fechaHeader{line-height: .2; margin: 50px 0 20px 0;}
            .textJustify{text-align: justify;}
            .customSpacing{white-space: nowrap;letter-spacing: 1em;} 
            .nota p{text-indent: 150px;} 
            .ql-align-center{text-align: center}
            .ql-align-right{text-align: right}
            .ql-align-justify{text-align: justify}
            .nota{margin-top: 10px}
            ',
                'methods' => [
                    'SetTitle' => "NOTA DE CERTIFICACIÓN #$idcertificacion, $beneficiarioNombreCompleto",
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                ]
            ]);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_nota', $idcertificacionnota, array());
            return $pdf->render();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    protected function findModel($id)
    {
        if (($model = Mds_certificacion_nota::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

function ArmarDateParaMySql($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 6, 4);
    $mes  = substr($fecha, 3, 2);
    $dia = substr($fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}

function dateSpanish($fecha = null)
{
    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    if (!$fecha) {
        return ("Neuquén, " . $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y'));
    } else {
        $fecha_timestamp = strtotime($fecha);
        return ("Neuquén, " . $dias[date('w', $fecha_timestamp)] . " " . date('d', $fecha_timestamp) . " de " . $meses[date('n', $fecha_timestamp) - 1] . " del " . date('Y', $fecha_timestamp));
    }
}
