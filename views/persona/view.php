<?php

use app\models\Configuracion;
use app\models\Localidades;
use app\models\Persona;
use app\models\Provincias;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Persona */

$this->title = $model->apellido . ', ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Personas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);

$iniciales = strtoupper(substr($model->apellido, 0, 1) . substr($model->nombre, 0, 1));

$localidadTexto = 'Desconocida';
if (!empty($model->idlocalidad)) {
    $localidad = Localidades::findOne($model->idlocalidad);
    if ($localidad) {
        $provincia = Provincias::findOne($localidad->idprovincia);
        $localidadTexto = $localidad->descripcion . ($provincia ? ' — ' . $provincia->descripcion : '');
    }
}

$docTipo = Configuracion::findOne($model->documento_tipo)->descripcion ?? 'DNI';
$nacionalidad = Configuracion::findOne($model->nacionalidad)->descripcion ?? 'Desconocida';
$genero = Configuracion::findOne($model->genero)->descripcion ?? 'Desconocido';

$padreTexto = 'No especificado';
if (!empty($model->padre)) {
    $p = Persona::findOne($model->padre);
    if ($p) $padreTexto = 'DNI ' . $p->documento . ' — ' . $p->apellido . ' ' . $p->nombre;
}

$madreTexto = 'No especificado';
if (!empty($model->madre)) {
    $m = Persona::findOne($model->madre);
    if ($m) $madreTexto = 'DNI ' . $m->documento . ' — ' . $m->apellido . ' ' . $m->nombre;
}

$convivienteTexto = 'No especificado';
if (!empty($model->conviviente)) {
    $c = Persona::findOne($model->conviviente);
    if ($c) $convivienteTexto = 'DNI ' . $c->documento . ' — ' . $c->apellido . ' ' . $c->nombre;
}

$condiciones = ['or'];
if (!empty($model->padre)) $condiciones[] = ['padre' => $model->padre];
if (!empty($model->madre)) $condiciones[] = ['madre' => $model->madre];

$hermanos = [];
if (count($condiciones) > 1) {
    $hermanos = Persona::find()
        ->where($condiciones)
        ->andWhere(['!=', 'idpersona', $model->idpersona])
        ->orderBy('fecha_nacimiento ASC')
        ->all();
}

$hijos = Persona::find()
    ->where(['or', ['padre' => $model->idpersona], ['madre' => $model->idpersona]])
    ->orderBy('fecha_nacimiento ASC')
    ->all();
?>

<div class="persona-view">

    <style>
        .pv-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 12px 0;
        }

        .pv-card {
            background: #fff;
            border: 0.5px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .pv-card-title {
            font-size: 12px;
            font-weight: 500;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .pv-card-body {
            padding: 8px 14px;
        }

        .pv-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 5px 0;
            border-bottom: 0.5px solid #f0f0f0;
            gap: 12px;
        }

        .pv-row:last-child {
            border-bottom: none;
        }

        .pv-label {
            font-size: 12px;
            color: #777;
            white-space: nowrap;
        }

        .pv-value {
            font-size: 12px;
            color: #333;
            text-align: right;
        }

        .pv-value.muted {
            color: #aaa;
            font-style: italic;
        }

        .pv-full {
            grid-column: 1 / -1;
        }

        .pv-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            background: #fff;
            border: 0.5px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .pv-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dbeafe;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            font-size: 14px;
            color: #1e40af;
            flex-shrink: 0;
        }

        .pv-name {
            font-size: 15px;
            font-weight: 500;
            color: #222;
            margin: 0;
        }

        .pv-sub {
            font-size: 12px;
            color: #888;
            margin: 0;
        }

        .pv-badge {
            font-size: 10px;
            background: #dcfce7;
            color: #166534;
            padding: 2px 8px;
            border-radius: 10px;
        }

        .title-blue {
            background: #B5D4F4;
            color: #0C447C;
        }

        .title-teal {
            background: #9FE1CB;
            color: #085041;
        }

        .title-amber {
            background: #FAC775;
            color: #633806;
        }

        .vinculos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .vinculo-card {
            border-radius: 8px;
            overflow: hidden;
            border: 0.5px solid #e0e0e0;
        }

        .vinculo-card-title {
            font-size: 11px;
            font-weight: 500;
            padding: 5px 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .vinculo-card-body {
            padding: 7px 10px;
            background: #fff;
        }

        .vinculo-item {
            font-size: 12px;
            color: #333;
            padding: 3px 0;
            border-bottom: 0.5px solid #f0f0f0;
        }

        .vinculo-item:last-child {
            border-bottom: none;
        }

        .vinculo-item.muted {
            color: #aaa;
            font-style: italic;
        }

        .vc-padre .vinculo-card-title {
            background: #B5D4F4;
            color: #0C447C;
        }

        .vc-madre .vinculo-card-title {
            background: #F4C0D1;
            color: #72243E;
        }

        .vc-conviviente .vinculo-card-title {
            background: #9FE1CB;
            color: #085041;
        }

        .vc-hermanos .vinculo-card-title {
            background: #FAC775;
            color: #633806;
        }

        .vc-hijos .vinculo-card-title {
            background: #CECBF6;
            color: #3C3489;
        }
    </style>

    <div class="pv-header">
        <div class="pv-avatar"><?= Html::encode($iniciales) ?></div>
        <div style="flex:1;">
            <p class="pv-name"><?= Html::encode($model->apellido . ', ' . $model->nombre) ?></p>
            <p class="pv-sub">ID <?= $model->idpersona ?> &nbsp;·&nbsp; <?= Html::encode($docTipo) ?>: <?= $model->documento ?></p>
        </div>
        <span class="pv-badge"><?= Html::encode($genero) ?></span>
    </div>

    <div class="pv-grid">

        <div class="pv-card">
            <div class="pv-card-title title-blue">
                <i class="fa fa-user" style="font-size:13px;"></i> Datos personales
            </div>
            <div class="pv-card-body">
                <div class="pv-row"><span class="pv-label">Apellido</span><span class="pv-value"><?= Html::encode($model->apellido) ?></span></div>
                <div class="pv-row"><span class="pv-label">Nombre</span><span class="pv-value"><?= Html::encode($model->nombre) ?></span></div>
                <div class="pv-row"><span class="pv-label">Documento</span><span class="pv-value"><?= Html::encode($docTipo) ?>: <?= $model->documento ?></span></div>
                <div class="pv-row"><span class="pv-label">Nacionalidad</span><span class="pv-value"><?= Html::encode($nacionalidad) ?></span></div>
                <div class="pv-row"><span class="pv-label">Nacimiento</span><span class="pv-value"><?= $model->fecha_nacimiento ? date('d/m/Y', strtotime($model->fecha_nacimiento)) : '<span class="muted">No definido</span>' ?></span></div>
                <div class="pv-row"><span class="pv-label">Género</span><span class="pv-value"><?= Html::encode($genero) ?></span></div>
            </div>
        </div>

        <div class="pv-card">
            <div class="pv-card-title title-teal">
                <i class="fa fa-home" style="font-size:13px;"></i> Domicilio y ubicación
            </div>
            <div class="pv-card-body">
                <div class="pv-row"><span class="pv-label">Localidad</span><span class="pv-value"><?= Html::encode($localidadTexto) ?></span></div>
                <div class="pv-row"><span class="pv-label">Calle</span><span class="pv-value"><?= $model->domicilio_calle ? Html::encode($model->domicilio_calle) : '<span class="muted">No definido</span>' ?></span></div>
                <div class="pv-row"><span class="pv-label">Número</span><span class="pv-value"><?= $model->domicilio_numero ? Html::encode($model->domicilio_numero) : '<span class="muted">No definido</span>' ?></span></div>
                <div class="pv-row"><span class="pv-label">Descripción</span><span class="pv-value"><?= $model->domicilio ? Html::encode($model->domicilio) : '<span class="muted">No definido</span>' ?></span></div>
            </div>
        </div>

        <div class="pv-card pv-full">
            <div class="pv-card-title title-amber">
                <i class="fa fa-link" style="font-size:13px;"></i> Vínculos
            </div>
            <div class="pv-card-body">
                <div class="vinculos-grid">

                    <div class="vinculo-card vc-padre">
                        <div class="vinculo-card-title"><i class="fa fa-male" style="font-size:12px;"></i> Padre</div>
                        <div class="vinculo-card-body">
                            <?php if (empty($model->padre)): ?>
                                <div class="vinculo-item muted">No especificado</div>
                            <?php else: ?>
                                <div class="vinculo-item">
                                    <?= Html::a(
                                        'DNI ' . $p->documento . ' — ' . Html::encode($p->apellido . ' ' . $p->nombre),
                                        ['persona/view', 'id' => $p->idpersona],
                                        ['role' => 'modal-remote', 'style' => 'font-size:12px;']
                                    ) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="vinculo-card vc-madre">
                        <div class="vinculo-card-title"><i class="fa fa-female" style="font-size:12px;"></i> Madre</div>
                        <div class="vinculo-card-body">
                            <?php if (empty($model->madre)): ?>
                                <div class="vinculo-item muted">No especificado</div>
                            <?php else: ?>
                                <div class="vinculo-item">
                                    <?= $m ? Html::a(
                                        'DNI ' . $m->documento . ' — ' . Html::encode($m->apellido . ' ' . $m->nombre),
                                        ['persona/view', 'id' => $m->idpersona],
                                        ['role' => 'modal-remote', 'style' => 'font-size:12px;']
                                    ) : '' ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="vinculo-card vc-conviviente">
                        <div class="vinculo-card-title"><i class="fa fa-heart" style="font-size:12px;"></i> Conviviente</div>
                        <div class="vinculo-card-body">
                            <?php if (empty($model->conviviente)): ?>
                                <div class="vinculo-item muted">No especificado</div>
                            <?php else: ?>
                                <div class="vinculo-item">
                                    <?= $c ? Html::a(
                                        'DNI ' . $c->documento . ' — ' . Html::encode($c->apellido . ' ' . $c->nombre),
                                        ['persona/view', 'id' => $c->idpersona],
                                        ['role' => 'modal-remote', 'style' => 'font-size:12px;']
                                    ) : '' ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="vinculo-card vc-hermanos">
                        <div class="vinculo-card-title"><i class="fa fa-users" style="font-size:12px;"></i> Hermanos</div>
                        <div class="vinculo-card-body">
                            <?php if (empty($hermanos)): ?>
                                <div class="vinculo-item muted">Sin registros</div>
                            <?php else: ?>
                                <?php foreach ($hermanos as $h): ?>
                                    <div class="vinculo-item">
                                    <?= $h ? Html::a(
                                        'DNI ' . $h->documento . ' — ' . Html::encode($h->apellido . ' ' . $h->nombre),
                                        ['persona/view', 'id' => $h->idpersona],
                                        ['role' => 'modal-remote', 'style' => 'font-size:12px;']
                                    ) : '' ?>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="vinculo-card vc-hijos">
                        <div class="vinculo-card-title"><i class="fa fa-child" style="font-size:12px;"></i> Hijos</div>
                        <div class="vinculo-card-body">
                            <?php if (empty($hijos)): ?>
                                <div class="vinculo-item muted">Sin registros</div>
                            <?php else: ?>
                                <?php foreach ($hijos as $h): ?>
                                    <div class="vinculo-item">
                                    <?= $h ? Html::a(
                                        'DNI ' . $h->documento . ' — ' . Html::encode($h->apellido . ' ' . $h->nombre),
                                        ['persona/view', 'id' => $h->idpersona],
                                        ['role' => 'modal-remote', 'style' => 'font-size:12px;']
                                    ) : '' ?>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>



        <div class="pv-card">
            <div class="pv-card-title" style="background: #F0997B; color: #4A1B0C;">
                <i class="fa fa-heartbeat" style="font-size:13px;"></i> Historial médico
            </div>
            <div class="pv-card-body">
                <div class="pv-row"><span class="pv-value muted">Sin registros</span></div>
            </div>
        </div>

        <div class="pv-card">
            <div class="pv-card-title" style="background: #AFA9EC; color: #26215C;">
                <i class="fa fa-graduation-cap" style="font-size:13px;"></i> Formación / Educación
            </div>
            <div class="pv-card-body">
                <div class="pv-row"><span class="pv-value muted">Sin registros</span></div>
            </div>
        </div>
    </div>
</div>