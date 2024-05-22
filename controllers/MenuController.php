<?php

namespace app\controllers;

use app\models\Menu;
use app\models\MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Menu models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Menu();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public static function getArbolMenu($menupadre, $items)
    {

        $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idpadre = $menupadre == null ? -1 : $menupadre->idmenu;
        $menuhijos = Sds_com_menu::getMenuHijos($idpadre);
        $tiene_hijos = !empty($menuhijos);
        $html_hijos = "";
        if ($tiene_hijos) {
            foreach ($menuhijos as $menu) {
                $cargar_hijos = false;
                //si el item de seguridad es null, busco que tenga permiso el hijo para mostrarse
                if ($menu->iditem == null) {
                    $cargar_hijos = true;
                } else {
                    //si el item de seguridad no es null, chequeo que el usuario tenga el que corresponde a ese menu.
                    foreach ($items as $item) {
                        if ($menu->iditem == $item->iditem) {
                            $cargar_hijos = true;
                        }
                    }
                }
                if ($cargar_hijos) {
                    $html_hijos = $html_hijos . Sds_com_menu::getArbolMenu($menu, $items);
                }
            }
            if ($html_hijos != "") {
                if ($idpadre != -1) {
                    $html_hijos =  '<ul class="nav nav-children">' . $html_hijos;
                }
                $html_hijos =  $html_hijos . '</ul>';
            }
        }
        $html_arbol = "";
        if ($idpadre == -1) {
            $html_arbol = '<li>
                                <a href="index.php?r=site%2Findex">
                                    <i class="fas fa-home" aria-hidden="true"></i>
                                    <span>Página Principal</span>
                                </a>
                            </li>';
        } else {
            $cargar_menu = false;
            $subpermiso_informatica = false;
            foreach ($items as $item) {
                if (42 == $item->iditem) {
                    $subpermiso_informatica = true;
                }
            }
            if ($idcontacto != null || (($menupadre->idmenu != 35 && $menupadre->idmenu != 60) && $idcontacto == null)
                 || ($subpermiso_informatica && $menupadre->idmenu == 35)) {
                //si el item de seguridad es null, busco que tengan permiso los hijos para mostrarse
                if ($menupadre->iditem == null && ($html_hijos != "" || !$tiene_hijos)) {
                    $cargar_menu = true;
                } else {
                    //si el item de seguridad no es null, chequeo que el usuario tenga el que corresponde a ese menu.
                    foreach ($items as $item) {
                        if ($menupadre->iditem == $item->iditem) {
                            $cargar_menu = true;
                        }
                    }
                }
            }
            if ($cargar_menu) {
                $html_arbol = '<li' . ($tiene_hijos ? ' class="nav-parent"' : '') . '>
                        <a ' . ($menupadre->ruta != null ? 'href="' . $menupadre->ruta . '" onclick="$(\'#loading\').show();" ' : '') . '>
                            <i class="' . $menupadre->icono . '" aria-hidden="true"></i>
                            <span>' . $menupadre->descripcion . '</span>
                        </a>';
            }
        }
        if ($html_arbol != "" && $html_hijos != "") {
            $html_arbol =  $html_arbol . $html_hijos . "</li>";
        }
        return $html_arbol;
    }
}
}
