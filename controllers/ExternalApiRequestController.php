<?php

namespace app\controllers;

use app\models\Mds_legales_oficio;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use Yii;

class ExternalApiRequestController
{

    public function verificarUsuarioActivo()
    {
        return Mds_seg_usuario::verificarUsuarioActivo();
    }

    public function verificarPermiso($arrayidItemPermiso, $verifyPermiso)
    {
        return Mds_seg_permiso::verificarPermiso($arrayidItemPermiso, $verifyPermiso);
    }

    public function runneuLogin()
    {
        $idUsuario = Yii::$app->user->identity->idusuario;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_LOGIN', $idUsuario, array());
        $token = '';
        if ($this->verificarUsuarioActivo()) {

            $postData = [
                'user' => env('RUNNEU_API_USER'),
                'pass' => env('RUNNEU_API_PASS')
            ];

            try {
                // Inicializa la biblioteca Curl
                $curl = curl_init();

                // Configura la URL de destino
                $endPointApiRunneu = env('ENDPOINT_API_RUNNEU');
                $endPointApiRunneuLogin = env('ENDPOINT_API_RUNNEU_LOGIN');
                $url = "$endPointApiRunneu/$endPointApiRunneuLogin";

                // Configura las opciones de la solicitud Curl
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                // Realiza la solicitud GET
                $response = curl_exec($curl);
                $response = json_decode($response);
                // Cierra la sesión Curl
                curl_close($curl);

                if ($response && $response->success && $response->token) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_LOGIN_SUCCESS', $idUsuario, array());
                    $token = $response->token;
                } else {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_LOGIN_FAIL', $idUsuario, array());
                }
            } catch (\Exception $e) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_LOGIN_FAIL', $idUsuario, array());
            }
        } else {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_LOGIN_ACCESS_DENIED', $idUsuario, array());
        }
        return $token;
    }

    public function runneuIntervencionByModulo($arrayDocumentos, $modulo, $idModulo, $accion, $tipo = null)
    {
        $idUsuario = Yii::$app->user->identity->idusuario;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_INTERVENCION_BY_MODULO', $idUsuario, array());
        $response = [
            'success' => false,
            'message' => 'No posee permisos para realizar esta acción',
        ];

        /*Si se desea que se verifique si el usuario posee todos los permisos 
        en arrayidItemPermiso, redefinir verifyPermiso por 'all' */
        $verifyPermiso = 'one';
        switch ($modulo) {
            case Mds_legales_oficio::RUNNEU_API_MODULO:
                $arrayidItemPermiso = [
                    Mds_seg_item::MODULO_LEGALES_CREAR_REQUERIMIENTO,
                    Mds_seg_item::MODULO_LEGALES_ACCIONAR_RESPUESTA,
                    Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                ];
                break;
            default:
                $arrayidItemPermiso = array();
                break;
        }

        if ($this->verificarUsuarioActivo() && $this->verificarPermiso($arrayidItemPermiso, $verifyPermiso)) {
            try {
                $token = $this->runneuLogin();

                if ($token) {
                    $headers = array(
                        "Authorization: Bearer $token"
                    );

                    $params = "";
                    if ($modulo) {
                        $modulo = strtolower($modulo);
                        $params .= "modulo=$modulo";
                    }
                    if ($idModulo) {
                        $params .= $params ? "&id=$idModulo" : "id=$idModulo";
                    }
                    if ($accion) {
                        $accion = strtolower($accion);
                        $params .= $params ? "&accion=$accion" : "accion=$accion";
                    }
                    if ($tipo) {
                        $tipo = strtolower($tipo);
                        $params .= $params ? "&tipo=$tipo" : "tipo=$tipo";
                    }

                    // Configura la URL de destino
                    $url = env('ENDPOINT_API_RUNNEU') . "/" . env('ENDPOINT_API_RUNNEU_INTERVENCION_BY_MODULO') . "?$params";

                    // Inicializa la biblioteca Curl
                    $curl = curl_init();

                    // Configura las opciones de la solicitud Curl
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arrayDocumentos));
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                    // Realiza la solicitud GET
                    $data = curl_exec($curl);
                    $data = json_decode($data);


                    // Cierra la sesión Curl
                    curl_close($curl);

                    if ($data && $data->success) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_INTERVENCION_BY_MODULO_SUCCESS', $idUsuario, array());
                        $response['success'] = true;
                    } else {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_INTERVENCION_BY_MODULO_FAIL', $idUsuario, array());
                    }

                    if ($data && $data->message) {
                        $response['message'] = $data->message;
                    }
                }
            } catch (\Exception $e) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_INTERVENCION_BY_MODULO_FAIL', $idUsuario, array());
            }
        } else {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'RUNNEU_API_INTERVENCION_BY_MODULO_ACCESS_DENIED', $idUsuario, array());
        }

        return json_encode($response);
    }
}
