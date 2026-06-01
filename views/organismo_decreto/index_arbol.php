<?php
// Al principio de index_arbol.php
johnitvn\ajaxcrud\CrudAsset::register($this);

$this->registerCssFile(
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
);

$this->registerJsFile(
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

use app\models\OrganismoOrgDec;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
/** @var mixed $vista */
/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDecreto */

$title = 'Decreto: ' . $model->descripcion . ' - ' . ($model->activo ? ' Vigente' : 'Finalizado');
$this->title = $title;

?>

<header class="page-header" style="display: flex; align-items: center; justify-content: space-between;">

    <div class="header-left" style="display: flex; align-items: center;">
        <ol class="breadcrumbs" style="position: static; margin: 0 15px 0 15px; padding: 0;">
            <li>
                <a href="<?= Url::to(['index']) ?>">
                    <i class="neon fa fa-arrow-circle-left" style="font-size: 25px;" title="Volver a Decretos"></i>
                </a>
            </li>
        </ol>
        <h2 style="margin: 0; border: none; padding: 0;">
            <?= Html::encode($title) ?>
        </h2>

        <?php
        // Consultamos si ya existe el inicio de la estructura para este decreto
        $yaIniciado = \app\models\OrganismoOrgDec::find()->where(['iddecreto' => $model->iddecreto])->exists();

        if (!$yaIniciado): ?>
            <ol class="breadcrumbs" style="position: static; margin: 0 15px 0 15px; padding: 0;">
                <li>
                    <?= Html::a(
                        '<i class="fa fa-plus" style="font-size: 12px;"></i> INICIAR',
                        ['organismo/create', 'origen_alta' => 1, 'iddecreto' => $model->iddecreto], // Pasamos el ID del decreto
                        [
                            'class' => 'btn btn-primary btn-xs neon', // 'btn-xs' para que no sea gigante, 'neon' para tu estilo
                            'role' => 'modal-remote', // ESTO DISPARA EL MODAL
                            'title' => 'Iniciar Árbol',
                            'style' => 'padding: 2px 10px; font-weight: bold; border-radius: 4px; display: inline-flex; align-items: center; gap: 5px; color: white; text-decoration: none;'
                        ]
                    ) ?>
                </li>
            </ol>
        <?php else: ?>
            <div class="btn-group" role="group" aria-label="Selector de Vista" style="margin-left: 20px;">
                <a href="<?= Url::current(['vista' => 'identado']) ?>"
                    class="btn  <?= ($vista == 'identado') ? 'active btn-led-verde' : '' ?>"
                    style="margin-right: 5px; border-radius: 25px; font-size: 20px;"
                    title="Vista Vertical (Lista)">
                    <i class="fa fa-list-ul neon"></i>
                </a>

                <!-- <a href="<?php // Url::current(['vista' => 'lateral']) 
                                ?>"
                    class="btn  <?php // ($vista == 'lateral') ? 'active btn-led-verde' : '' 
                                ?>"
                    style="margin-right: 5px; border-radius: 25px;font-size: 20px;"
                    title="Vista Lateral (Derecha)">
                    <i class="fa fa-indent neon"></i>
                </a> -->

                <a href="<?= Url::current(['vista' => 'organigrama']) ?>"
                    class="btn <?= ($vista == 'organigrama' || !$vista) ? 'active btn-led-verde' : '' ?>"
                    style="border-radius: 25px;font-size: 20px;"
                    title="Vista Organigrama (Árbol)">
                    <i class="fa fa-sitemap neon"></i>
                </a>

                <?= Html::a(
                '<i class="fa fa-building "></i>',
                ['edificio/create'],
                ['role' => 'modal-remote', 
                'class' => 'btn ',
                'style'=>"border-radius: 25px;font-size: 20px;",
                'title'=>"Crear Edificio"]
            ). 
            Html::a(
                '<i class="fa fa-users "></i>',
                ['empleado/index'],
                ['target' => '_blank', 
                'class' => 'btn ',
                'style'=>"border-radius: 25px;font-size: 20px;",
                'title'=>"Empleados"]
            )
            ?>

            </div>
        <?php endif; ?>
    </div>

    <style>
        /* --- Estilo Base para el Botón LED --- */
        .btn-led-verde {
            background-color: transparent;
            /* Fondo transparente por defecto */

            color: #00e676;
            /* Verde un poco más fuerte para el texto */

            transition: all 0.4s ease;
            /* Transición suave para el hover y active */
        }

        /* --- Hover (cuando pasás el mouse) --- */
        .btn-led-verde:hover {
            color: #00e676;
            /* Verde un poco más fuerte para el texto */
            background-color: rgba(0, 255, 0, 0.05);
            /* Un tinte verde de fondo casi imperceptible */
            border-color: rgba(0, 255, 0, 0.5);
            /* Borde un poco más nítido */
        }

        /* --- ESTILO ACTIVO (EL EFECTO NEÓN/LED) --- */
        /* Esta clase la pondremos dinámicamente con PHP */
        .btn-led-verde.led-active {
            color: white !important;
            /* Texto blanco para que resalte */
            border-color: #00e676;
            /* Borde verde brillante */
            background-color: rgba(0, 230, 118, 0.7);
            /* Fondo verde traslúcido */

            /* EL SECRETO DEL NEÓN: box-shadow */
            /* Usamos múltiples sombras para crear la difusión del gas argón */
            box-shadow:
                0 0 5px rgba(0, 255, 0, 0.8),
                /* Brillo interno tenue */
                0 0 10px rgba(0, 255, 0, 0.6),
                /* Halo intermedio */
                0 0 20px rgba(0, 255, 0, 0.4);
            /* Halo externo suave y difuso */
        }
    </style>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="<?= Url::to(['/']) ?>">
                    <i class="neon fa fa-home"></i>
                </a>
            </li>
            <li><span><?= Html::encode($title) ?></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>

</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12 mt-3">
        <?= $this->render('jerarquias', ['ubi' => 'i']); ?>
    </div>
</div>


<div class="row ">
    <div class="col-md-12 col-lg-12 col-xl-12 " style="padding: 64px 50px;">
        <?php
        /**
         * Definimos la vista por defecto si $vista llega nulo o vacío.
         * En este caso, 'organigrama'.
         */
        $vistaSeleccionada = !empty($vista) ? $vista : 'identado';

        // Construimos el nombre del archivo: arbol_identado, arbol_lateral o arbol_organigrama
        $archivoARenderizar = 'arbol_' . $vistaSeleccionada;

        echo $this->render($archivoARenderizar, [
            'model' => $model,
            'iddecreto' => $model->iddecreto,
        ]);
        ?>
    </div>
</div>

<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })"
);
?>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>