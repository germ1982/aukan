<?php

use app\models\Configuracion;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use app\models\Usuarios;
use app\models\Empleado;
use yii\helpers\Html;

/** @var yii\web\View $this */

// NOTA: este archivo se incluye desde tarjeta_base.php (mismo scope que home.php y las demás tarjetas),
// por eso las variables propias llevan prefijo "pc" para no pisar las de otras tarjetas.

$userId = Yii::$app->user->id;

/** @var Usuarios|null $usuarioLogueado */
$usuarioLogueado = $userId ? Usuarios::findOne($userId) : null;

/** @var Empleado|null $model */
$model = null;
/** @var Persona|null $model_persona */
$model_persona = null;

if ($usuarioLogueado && $usuarioLogueado->idpersona) {
    $model_persona = Persona::findOne($usuarioLogueado->idpersona);
    if ($model_persona) {
        $model = Empleado::find()->where(['idpersona' => $model_persona->idpersona])->one();
    }
}

// Descripción de un registro de Configuracion sin romper si el id no existe
$pcDescConfig = function ($id) {
    if (!$id) {
        return 'N/D';
    }
    /** @var Configuracion|null $c */
    $c = Configuracion::findOne($id);
    return $c ? $c->descripcion : 'N/D';
};

// Lee un atributo de un modelo solo si existe (evita excepciones por nombres de columna)
$pcAttr = function ($m, $campo) {
    return ($m && $m->hasAttribute($campo) && $m->$campo !== null && $m->$campo !== '') ? $m->$campo : null;
};

$dispositivo = null;
$contratacion = 'N/D';
$categoria = 'N/D';
$funcion = 'N/D';
$ingreso_real = 'N/D';
$pcAnios = null;

if ($model) {
    $dispositivo = OrganismoDispositivo::get_dispositivo($model->iddispositivo);
    $contratacion = $pcDescConfig($model->contratacion);
    $categoria = $pcDescConfig($model->categoria);
    $funcion = $pcDescConfig($model->funcion);
    $ingreso_real = ($model->ingreso_real == '0000-00-00' || !$model->ingreso_real)
        ? 'N/D'
        : date_format(date_create($model->ingreso_real), 'd/m/Y');

    // La fecha de ingreso ya no se muestra, solo se usa para calcular los años de antigüedad
    if ($ingreso_real !== 'N/D') {
        $pcAnios = date_create($model->ingreso_real)->diff(new DateTime('today'))->y;
    }
}

$fotoUrl = ($model && $model->foto) ? Yii::getAlias('@web') . '/img/empleados-fotos/' . $model->foto : '';

// ── Datos para mostrar ──
if (is_object($dispositivo)) {
    $pcSector = $pcAttr($dispositivo, 'descripcion') ?: ($pcAttr($dispositivo, 'nombre') ?: 'N/D');
} else {
    $pcSector = $dispositivo ?: 'N/D';
}

$pcApellido = $pcAttr($model_persona, 'apellido');
$pcNombre   = $pcAttr($model_persona, 'nombre');
$pcNombreCompleto = trim(($pcApellido ? $pcApellido . ', ' : '') . $pcNombre) ?: 'Sin nombre';
$pcLegajo = $pcAttr($model, 'legajo') ?: ($model_persona ? '#' . $model_persona->idpersona : 'N/D');
$pcAntiguedad = $pcAnios !== null ? (string)$pcAnios : 'N/D';

// Fecha de nacimiento (Persona). Se prueba con los nombres de columna más comunes.
$pcNacimiento = 'N/D';
foreach (['fecha_nacimiento', 'fechanacimiento', 'fecha_nac', 'fechanac', 'nacimiento'] as $pcCampo) {
    $pcValor = $pcAttr($model_persona, $pcCampo);
    if ($pcValor && $pcValor != '0000-00-00') {
        $pcFecha = date_create($pcValor);
        if ($pcFecha) {
            $pcNacimiento = date_format($pcFecha, 'd/m/Y');
        }
        break;
    }
}

// Columnas de texto que van sobre la foto (el sector va aparte, en una sola línea)
$pcIzquierda = [
    'Función'      => $funcion,
    'Contratación' => $contratacion,
];
$pcDerecha = [
    'Categoría' => $categoria,
];
?>
<style>
/* Alto disponible dentro de .data-card (300px) descontando padding y título.
   Si queda cortado abajo, bajá este valor; si sobra espacio, subilo. */
.pc-escena {
    --pc-alto: 240px;
    --pc-cian: #5fe3ff;
    --pc-cian-suave: #9bf0ff;
    --pc-texto: #e6fbff;
    --pc-dim: rgba(205, 242, 255, .85);
    --pc-vidrio-fuerte: rgba(120, 220, 255, .26);
    --pc-borde: rgba(160, 236, 255, .5);
    width: 100%;
    height: var(--pc-alto);
    font-family: 'Segoe UI', system-ui, sans-serif;
    color: var(--pc-texto);
}
.pc-escena *, .pc-escena *::before, .pc-escena *::after { box-sizing: border-box; }

.pc-tarjeta {
    position: relative;
    width: 100%; height: 100%;
    display: flex; flex-direction: column; gap: 7px;
}

/* ── escenario: foto grande con el texto encima, sin globos ── */
.pc-escenario {
    position: relative; flex: 1; min-height: 0; overflow: hidden;
    border-radius: 13px;
    border: 1px solid rgba(160, 236, 255, .35);
    background: linear-gradient(180deg, rgba(40, 140, 205, .35), rgba(8, 50, 88, .55));
    box-shadow: inset 0 0 20px rgba(100, 220, 255, .22);
}
.pc-escena .pc-foto { position: absolute; inset: 0; margin: 0; padding: 0;
    -webkit-mask-image: linear-gradient(90deg, transparent 0, #000 12%, #000 88%, transparent 100%);
            mask-image: linear-gradient(90deg, transparent 0, #000 12%, #000 88%, transparent 100%);
}
.pc-escena .pc-foto img, .pc-escena .pc-foto svg {
    position: absolute; inset: 0; width: 100%; height: 100%; max-height: none; max-width: none; margin: 0;
    object-fit: cover; object-position: center 18%;
}
.pc-escena .pc-foto img { filter: grayscale(1) sepia(1) hue-rotate(155deg) saturate(3.2) brightness(.98) contrast(1.05); }

.pc-nombre {
    position: absolute; left: 0; right: 0; bottom: 0; padding: 18px 8px 5px; text-align: center;
    background: linear-gradient(0deg, rgba(3, 20, 34, .88), transparent);
    font-size: 11px; font-weight: 600; line-height: 1.2; text-shadow: 0 0 8px rgba(0, 20, 40, .9);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* texto suelto sobre la foto (sin fondo ni borde) */
.pc-item {
    text-shadow: 0 0 3px rgba(0, 12, 28, .95), 0 1px 3px rgba(0, 12, 28, .95), 0 0 9px rgba(0, 12, 28, .8);
}
.pc-item small { display: flex; align-items: center; gap: 3px; font-size: 8px; line-height: 1.2; color: var(--pc-dim); }
.pc-item small::before { content: ""; width: 4px; height: 4px; border-radius: 50%; border: 1px solid var(--pc-cian-suave); flex: none; }
.pc-item b {
    display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3; overflow: hidden;
    margin-top: 1px; font-size: 10px; font-weight: 600; line-height: 1.2; overflow-wrap: anywhere; color: #fff;
}

/* Bloque de datos sobre la foto: el sector arriba (puede seguir en un 2.º renglón)
   y las columnas debajo, que bajan solas según cuánto ocupe el sector. */
.pc-datos { position: absolute; top: 7px; left: 8px; right: 8px; display: flex; flex-direction: column; gap: 8px; }
.pc-fila { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; }
.pc-col { display: flex; flex-direction: column; gap: 8px; }
.pc-col.izq { width: 42%; }
.pc-col.der { width: 36%; }

/* ── fila inferior ── */
.pc-pie { flex: none; display: grid; grid-template-columns: 1fr 1.15fr 1fr; gap: 5px; }
.pc-pie div {
    padding: 5px 3px 4px; border-radius: 10px; text-align: center;
    background: var(--pc-vidrio-fuerte); border: 1px solid var(--pc-borde);
    font-size: 11px; font-weight: 500; line-height: 1.15; text-shadow: 0 0 8px rgba(120, 230, 255, .7);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.pc-pie small { display: block; margin-top: 1px; font-size: 8px; font-weight: 400; color: var(--pc-dim); text-shadow: none; }

.pc-vacio { margin: auto 0; text-align: center; font-size: 12px; line-height: 1.4; color: var(--pc-dim); padding: 0 10px; }
</style>

<div class="pc-escena">
    <article class="pc-tarjeta" aria-label="Tarjeta personal de <?= Html::encode($pcNombreCompleto) ?>">

        <?php if (!$model): ?>
            <p class="pc-vacio">No se encontró una ficha de empleado asociada a tu usuario.</p>
        <?php else: ?>

        <section class="pc-escenario">
            <!-- Foto grande, centrada, con los bordes difuminados -->
            <figure class="pc-foto">
                <?php if ($fotoUrl): ?>
                    <img src="<?= Html::encode($fotoUrl) ?>" alt="Foto de <?= Html::encode($pcNombreCompleto) ?>">
                <?php else: ?>
                    <svg viewBox="0 0 300 420" preserveAspectRatio="xMidYMax slice" role="img" aria-label="Silueta sin foto">
                        <defs>
                            <linearGradient id="pc-piel" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#c3f2ff"/><stop offset="1" stop-color="#6cc6e8"/></linearGradient>
                            <linearGradient id="pc-pelo" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#8bd9f5"/><stop offset="1" stop-color="#3a95c4"/></linearGradient>
                            <linearGradient id="pc-ropa" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#9fe2f8" stop-opacity=".95"/><stop offset="1" stop-color="#2f86b8" stop-opacity=".95"/></linearGradient>
                        </defs>
                        <path d="M70 130 C64 56 112 30 152 30 C196 30 232 62 224 134 C224 168 218 188 212 204 L88 204 C76 186 70 160 70 130Z" fill="url(#pc-pelo)"/>
                        <path d="M0 420 C6 340 52 298 112 284 L192 284 C250 298 296 340 300 420Z" fill="url(#pc-ropa)"/>
                        <path d="M126 224 L176 224 L182 288 C164 302 138 302 120 288Z" fill="url(#pc-piel)"/>
                        <path d="M118 286 L150 344 L102 326Z M184 286 L152 344 L200 326Z" fill="#d6f6ff" opacity=".8"/>
                        <ellipse cx="151" cy="140" rx="58" ry="72" fill="url(#pc-piel)"/>
                        <path d="M92 134 C94 80 128 62 158 64 C196 66 212 94 210 134 C192 106 168 96 144 98 C122 100 104 112 92 134Z" fill="url(#pc-pelo)"/>
                        <g fill="#0f4466" opacity=".85"><ellipse cx="130" cy="146" rx="7" ry="3.6"/><ellipse cx="176" cy="146" rx="7" ry="3.6"/></g>
                        <g stroke="#2c7aa5" stroke-width="2.4" stroke-linecap="round" fill="none" opacity=".8">
                            <path d="M118 134 Q130 128 142 132"/><path d="M165 132 Q177 128 189 134"/>
                            <path d="M152 150 L149 174 Q152 178 156 174"/><path d="M135 192 Q152 200 169 192"/>
                        </g>
                    </svg>
                <?php endif; ?>
            </figure>

            <!-- Datos sobre la foto (title = texto completo al pasar el mouse) -->
            <div class="pc-datos">
                <div class="pc-item" title="<?= Html::encode('Sector: ' . $pcSector) ?>">
                    <small>Sector</small><b><?= Html::encode($pcSector) ?></b>
                </div>

                <div class="pc-fila">
                    <div class="pc-col izq">
                        <?php foreach ($pcIzquierda as $label => $valor): ?>
                            <div class="pc-item" title="<?= Html::encode($label . ': ' . $valor) ?>"><small><?= Html::encode($label) ?></small><b><?= Html::encode($valor) ?></b></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="pc-col der">
                        <?php foreach ($pcDerecha as $label => $valor): ?>
                            <div class="pc-item" title="<?= Html::encode($label . ': ' . $valor) ?>"><small><?= Html::encode($label) ?></small><b><?= Html::encode($valor) ?></b></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="pc-nombre" title="<?= Html::encode($pcNombreCompleto) ?>"><?= Html::encode($pcNombreCompleto) ?></div>
        </section>

        <footer class="pc-pie">
            <div><?= Html::encode($pcLegajo) ?><small>Legajo</small></div>
            <div><?= Html::encode($pcNacimiento) ?><small>Nacimiento</small></div>
            <div><?= Html::encode($pcAntiguedad) ?><small>Antigüedad</small></div>
        </footer>

        <?php endif; ?>
    </article>
</div>