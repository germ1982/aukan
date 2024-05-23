<?php

use app\models\Mds_org_novedad;
use app\models\Mds_org_novedadSearch;
use yii\bootstrap\Collapse;

$model = new Mds_org_novedad();
$searchModel_nov = new Mds_org_novedadSearch();
$searchModel_nov->estado = 2;
// Fechas para que filtre las novedades de la última semana.
$date_now = date('Y-m-d H:i:s');
$date_past = strtotime('-7 day', strtotime($date_now));
$searchModel_nov->fdesde = date('Y-m-d H:i:s', $date_past);
$searchModel_nov->fhasta = $date_now;

$dataProvider_nov = $searchModel_nov->search(Yii::$app->request->queryParams);
$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
?>

<?php if ($dataProvider_nov->getCount() > 0) : ?>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel-featured panel-featured-primary">
            <header class="panel-heading bg-default">
                <h2 class="panel-title">Novedades</h2>
            </header>
            <div class="panel-body">
                <?php foreach ($dataProvider_nov->getModels() as $novedad) : ?>
                    <div class="alert alert-info">
                        <strong><?php echo $novedad->titulo ?></strong> <br>
                        <p><?php echo $novedad->descripcion ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <br>
<?php endif; ?>