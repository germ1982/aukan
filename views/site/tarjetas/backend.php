<?php
use app\models\Backend;
use yii\helpers\Html;

// Consultamos los registros de la base de datos de forma segura
try {
    $registrosBackend = Backend::find()->where(['estado' => 1])->all();
} catch (\Exception $e) {
    $registrosBackend = [];
}
?>

<style>
    .tarjeta-backend-mini {
        max-height: 400px;
        overflow-y: auto;
        padding: 8px;
    }
    .item-backend-mini {
        background: rgba(0, 20, 40, 0.7);
        border: 1px solid rgba(0, 191, 255, 0.5);
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 12px;
        color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
    .item-backend-mini h5 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: bold;
    }
    /* Estilo para que el título sea el enlace principal */
    .link-titulo-endpoint {
        color: #00bfff !important;
        text-decoration: none;
        display: block;
        transition: all 0.2s ease-in-out;
    }
    .link-titulo-endpoint:hover {
        color: #00ffcc !important;
        text-shadow: 0 0 6px rgba(0, 255, 204, 0.6);
        text-decoration: underline;
    }
</style>

<div class="tarjeta-backend-mini">
    <?php if (!empty($registrosBackend)): ?>
        <?php foreach ($registrosBackend as $item): ?>
            <div class="item-backend-mini">
                <!-- Únicamente el Título es el link, sin texto de HTTP abajo -->
                <h5>
                    <?= Html::a(
                        Html::encode($item->nombre) . ' <i class="fas fa-external-link-alt" style="font-size:0.8rem; margin-left:4px;"></i>',
                        $item->endpoint,
                        [
                            'target' => '_blank',
                            'rel' => 'noopener noreferrer',
                            'class' => 'link-titulo-endpoint',
                            'title' => 'Abrir: ' . $item->endpoint,
                        ]
                    ) ?>
                </h5>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: #a2c8ff; font-size: 0.95rem; text-align: center;">No hay registros cargados.</p>
    <?php endif; ?>
</div>