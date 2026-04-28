<?php
// Al principio de index_arbol.php
johnitvn\ajaxcrud\CrudAsset::register($this);

use app\models\OrganismoOrgDec;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDecreto */

$title = 'Organigrama de Decreto N° ' . $model->iddecreto;
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
        <?php endif; ?>
    </div>

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
    <div class="col-md-12 col-lg-12 col-xl-12">


        <?php echo $this->render('_form_arbol', [
            'model' => $model,
            'iddecreto' => $model->iddecreto, // Pasamos el ID del decreto explícitamente
        ]); ?>

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