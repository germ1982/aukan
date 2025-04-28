<?php

namespace app\controllers;
use yii\web\Controller;

class Runneu_indicadoresController extends Controller
{
    public function actionIndex()
    {
        // Renderiza la vista 'index' en la carpeta 'runneu_indicadores'
        return $this->render('index');
    }
}
