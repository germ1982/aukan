<?php

namespace app\controllers;

use app\models\Sds_com_provincia;
use app\models\Sds_com_localidad;
use yii\web\Controller;
use yii\helpers\ArrayHelper;


class Sds_com_provinciaController extends Controller
{
    public function behaviors()
    {
    }


    public function actionIndex()
    {
    }


    public function actionView($id)
    {
    }


    public function actionCreate()
    {
    }


    public function actionUpdate($id)
    {
    }

    public function actionDelete($id)
    {
    }


    public function actionBulkDelete()
    {
    }


    protected function findModel($id)
    {
    }

    public static function getListProvincias()
    {
        //Busqueda provincias
        $provincias = Sds_com_provincia::find()->where(['activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $provincias = ArrayHelper::map($provincias, 'idprovincia', 'descripcion');
        return $provincias;
    }

    public static function getListLocalidadesByProvincia($idprovincia)
    {
        //Busqueda localidades
        if ($idprovincia) {
            $localidades = Sds_com_localidad::find()->where(['idprovincia' => $idprovincia, 'activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        } else {
            $localidades = Sds_com_localidad::find()->where(['activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        }
        $localidades = ArrayHelper::map($localidades, 'idlocalidad', 'descripcion');
        return $localidades;
    }
}
