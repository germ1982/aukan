<?php

use app\models\Configuracion;
use app\models\OrganismoDispositivo;
use yii\helpers\Html;

/* @var $model app\models\Empleado */

$persona = $model->persona;
$foto = !empty($model->foto) ? $model->foto : 'empleado_0.png';
$rutaFoto = Yii::getAlias('@web') . '/img/empleados-fotos/' . $foto;

// Obtener datos del sector y la función
$dispositivo = OrganismoDispositivo::get_dispositivo_pro($model->iddispositivo);
$sectorNombre = $dispositivo ? $dispositivo->descripcion : 'Sin asignar';

$configFuncion = $model->funcion ? Configuracion::findOne($model->funcion) : null;
$funcionNombre = $configFuncion ? $configFuncion->descripcion : 'Sin asignar';
?>

<style>
    /* Override para forzar el contenedor del modal a Cyberpunk */
    #ajaxCrudModal .modal-content,
    .modal-dialog .modal-content {
        background-color: #0b0f19 !important;
        border: 1px solid rgba(0, 255, 204, 0.4) !important;
        border-radius: 12px !important;
        box-shadow: 0 0 30px rgba(0, 255, 204, 0.25) !important;
        overflow: hidden;
    }

    #ajaxCrudModal .modal-header,
    .modal-dialog .modal-header {
        background: #131a26 !important;
        border-bottom: 1px solid rgba(0, 255, 204, 0.2) !important;
        padding: 12px 20px !important;
    }

    #ajaxCrudModal .modal-title,
    .modal-dialog .modal-title {
        color: #00ffcc !important;
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 0 0 8px rgba(0, 255, 204, 0.5);
    }

    #ajaxCrudModal .modal-header .close,
    .modal-dialog .modal-header .close {
        color: #00ffcc !important;
        opacity: 0.8 !important;
        text-shadow: 0 0 8px #00ffcc;
    }

    #ajaxCrudModal .modal-header .close:hover {
        opacity: 1 !important;
        color: #ff2a2a !important;
    }

    #ajaxCrudModal .modal-body,
    .modal-dialog .modal-body {
        background-color: #0b0f19 !important;
        padding: 15px !important;
    }

    /* Forzar centrado del botón en el footer del Modal */
    #ajaxCrudModal .modal-footer,
    .modal-dialog .modal-footer {
        background-color: #131a26 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
        padding: 10px 20px !important;
        text-align: center !important;
        display: block !important;
    }

    #ajaxCrudModal .modal-footer .btn,
    .modal-dialog .modal-footer .btn {
        float: none !important;
        display: inline-block !important;
    }

    #ajaxCrudModal .modal-footer .btn-default,
    .modal-dialog .modal-footer .btn-default {
        background: rgba(255, 255, 255, 0.05) !important;
        color: #9ca3af !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        transition: all 0.2s ease;
    }

    #ajaxCrudModal .modal-footer .btn-default:hover {
        background: rgba(255, 42, 42, 0.2) !important;
        color: #ff2a2a !important;
        border-color: rgba(255, 42, 42, 0.4) !important;
    }

    /* Estructura Cyberpunk de la tarjeta */
    .cyber-profile-card {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    .cyber-profile-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 15px;
    }

    .cyber-avatar-frame {
        position: relative;
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        border-radius: 50%;
        padding: 3px;
        background: linear-gradient(135deg, #00ffcc, #00bfff);
        box-shadow: 0 0 15px rgba(0, 255, 204, 0.4);
    }

    .cyber-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        background-color: #131a26;
    }

    .cyber-user-info {
        flex: 1;
    }

    .cyber-user-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #00ffcc;
        text-shadow: 0 0 8px rgba(0, 255, 204, 0.4);
        margin: 0 0 6px 0;
    }

    .cyber-user-nacimiento {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.85rem;
        color: #00bfff;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cyber-grid-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .cyber-detail-item {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 10px;
    }

    .cyber-detail-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .cyber-detail-value {
        font-size: 0.9rem;
        font-weight: 500;
        color: #e0f0ff;
        font-family: 'JetBrains Mono', monospace;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Estilo para el link Cyberpunk del Sector */
    .cyber-link {
        color: #00ffcc;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .cyber-link:hover {
        color: #00bfff;
        text-shadow: 0 0 8px rgba(0, 191, 255, 0.6);
        text-decoration: underline;
    }

    /* Forzar que el modal tenga el tamaño adecuado solo en esta vista */
#ajaxCrudModal .modal-dialog {
    width: 80% !important;
    max-width: 1000px !important;
}
</style>

<div class="cyber-profile-card">
    <div class="cyber-profile-header">
        <div class="cyber-avatar-frame">
            <?= Html::img($rutaFoto, [
                'class' => 'cyber-avatar-img',
                'alt' => Html::encode($persona->nombre ?? 'Empleado'),
            ]) ?>
        </div>
        <div class="cyber-user-info">
            <h3 class="cyber-user-name">
                <?= Html::encode(($persona->apellido ?? '') . ' ' . ($persona->nombre ?? '')) ?>
            </h3>
            <div class="cyber-user-nacimiento">
                <span>🎂</span> Nacimiento: <?= !empty($persona->fecha_nacimiento) ? date('d/m/Y', strtotime($persona->fecha_nacimiento)) : 'N/A' ?>
            </div>
        </div>
    </div>

    <div class="cyber-grid-details">
        <div class="cyber-detail-item">
            <div class="cyber-detail-label">Sector</div>
            <div class="cyber-detail-value">
                <?php if ($model->iddispositivo): ?>
                    <?= Html::a(Html::encode($sectorNombre), ['organismo_dispositivo/view_only', 'id' => $model->iddispositivo], [
                        'class' => 'cyber-link',
                        'role' => 'modal-remote',
                        'title' => 'Ver Dispositivo / Sector',
                    ]) ?>
                <?php else: ?>
                    <?= Html::encode($sectorNombre) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="cyber-detail-item">
            <div class="cyber-detail-label">Función</div>
            <div class="cyber-detail-value">
                <?= Html::encode($funcionNombre) ?>
            </div>
        </div>
    </div>
</div>