<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_bdc_movimiento_equipo;
use app\models\Sds_bdc_movimiento_equipoSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Sds_bdc_movimiento_equipoController implements the CRUD actions for Sds_bdc_movimiento_equipo model.
 */
class Sds_bdc_movimiento_equipoController extends Controller
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
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::BDC_EQUIPO
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($equipo=0)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $searchModel = new Sds_bdc_movimiento_equipoSearch();
        if($equipo!=0){
            $searchModel->idequipo=$equipo;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Sds_bdc_movimiento_equipo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
