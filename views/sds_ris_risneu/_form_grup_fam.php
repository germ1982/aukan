<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_ris_personaSearch;

if ($model->idrisneu != null) {
    $searchModel = new Sds_ris_personaSearch();
    $searchModel->idrisneu = $model->idrisneu;
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    if ($dataProvider->getTotalCount() > 0) {
        $searchModel->documento = null;
    } else {
        $searchModel->documento = $model->dni_beneficiario;
        if ($searchModel->documento == null) {
            //Le mando algo para forzar que tome padre de familia obligatoriamente al no haber otro miembro cargado
            $searchModel->documento = 0;
        }
    }
    echo $this->render('//sds_ris_persona/index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'view' => isset($view) ? true : false,
        'oficial' => $model->oficial
    ]);
} else {
    echo '<div class="text-danger">Debe guardar los datos de la pestaña <b>"Encuestador / Domicilio"</b> antes de agregar personas.</div>';
}

if (isset($view)) {
    $this->registerJs(
        "$(document).ready(function() {
            $('#btn_agregar_persona').hide();
        });"
    );
}