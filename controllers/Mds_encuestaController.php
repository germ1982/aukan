<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use Yii;
use app\models\Sds_reg_registro;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_reg_registroController implements the CRUD actions for Sds_reg_registro model.
 */
class Mds_encuestaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'mis_encuestas', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ENCUESTA_ADMIN,
                        ],
                    ],
                    [
                        'actions' => ['mis_encuestas', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ENCUESTA_MIS,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_reg_registro models.
     * @return mixed
     */
    public function actionIndex()
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_encuesta', null, array());
        return $this->render('administrar_encuesta');
    }

    public function actionMis_encuestas()
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_encuesta', null, array());
        return $this->render('mis_encuestas');
    }

    public function actionFamiliassolidarias_nqn()
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_encuesta', null, array());
        return $this->render('familiassolidarias_nqn');
    }

}
