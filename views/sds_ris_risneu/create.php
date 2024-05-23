<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_ris_risneu */

?>
<div class="sds-ris-risneu-create">
    <?= $this->render('_form', [
        'model' => $model,
        'origen' => $origen,
        'idllamada' => $idllamada,
        'encuestadores' => $encuestadores,
        'realizadoPor' => $realizadoPor,
        'localidades' => $localidades,
        'barrios' => $barrios,
        'areas' => $areas,
        'calles' => $calles,
        'callesInterseccion' => $callesInterseccion,
        'provincias' => $provincias,
        'tipos_alimentacion' => $tipos_alimentacion,
        'risneu_alims' => $risneu_alims,
        'selectViviendaUso' => $selectViviendaUso,
        'selectViviendaUbicacion' => $selectViviendaUbicacion,
        'selectViviendaPropiedad' => $selectViviendaPropiedad,
        'selectViviendaTipo' => $selectViviendaTipo,
        'selectViviendaPiso' => $selectViviendaPiso,
        'selectViviendaObtieneAgua' => $selectViviendaObtieneAgua,
        'selectViviendaAgua' => $selectViviendaAgua,
        'selectViviendaBano' => $selectViviendaBano,
        'selectViviendaDesague' => $selectViviendaDesague,
        'selectViviendaIluminacion' => $selectViviendaIluminacion,
        'selectViviendaMedidor' => $selectViviendaMedidor,
        'selectViviendaCalefaccion' => $selectViviendaCalefaccion,
        'selectViviendaCocina' => $selectViviendaCocina,
        'selectViviendaTecho' => $selectViviendaTecho,
        'selectViviendaParedes' => $selectViviendaParedes,
        'existeJefe' => $existeJefe,
        'jefeNombreCompleto' => $jefeNombreCompleto
    ]) ?>
</div>