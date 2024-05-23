<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_stk_articulo;

    $div_devolucion = $model->isNewRecord ? 'display:none' : '';

    //Si existe fecha_hora, seteo los atributos fecha y hora para usar con los widgets
if ($model->fecha_hora_entrega != null) {
    $fecha = $model->fecha_hora_entrega;
    $model->fecha_hora_entrega = date('d/m/Y',strtotime(str_replace('/', '-', $fecha)));
    $model->hora_entrega = date('H:i', strtotime($fecha));
} else {
    $model->hora_entrega = date('H:i');
    $model->fecha_hora_entrega = date('d/m/Y');
}

if ($model->fecha_hora_devolucion != null) {
    $fecha = $model->fecha_hora_devolucion;
    $model->fecha_hora_devolucion = date('d/m/Y',strtotime(str_replace('/', '-', $fecha)));
    $model->hora_devolucion = date('H:i', strtotime($fecha));
} else {
    $model->hora_devolucion = date('H:i');
    $model->fecha_hora_devolucion = date('d/m/Y');
}

$herramientas = Sds_stk_articulo::find()->where("devolucion=1")->orderBy(["descripcion" => SORT_ASC])->all();
$contactos = Mds_org_contacto::getContactos();
//$estados = Sds_com_configuracion::find()->where("idconfiguraciontipo=".Sds_com_configuracion_tipo::TIPO_DEVOLUCION)->orderBy(["descripcion" => SORT_ASC])->all();
$estados = Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_DEVOLUCION);

$user = Yii::$app->user->identity;
$usuario = Mds_seg_usuario::findOne($user->idusuario);
//$contacto = Mds_org_contacto::findOne($usuario->idusuario);
if($model->isNewRecord)
    {   
        $model->responsable_entrega = $usuario->idusuario;
        $model->idorganismo =  $usuario->organismo_stock;
    }
else
    {
        $model->responsable_devolucion = $usuario->idusuario;
        $model->hora_devolucion = date('H:i');
        $model->fecha_hora_devolucion = date('d/m/Y');
    }
$responsable_entrega_name = Mds_seg_usuario::getNameUser($model->responsable_entrega);
$responsable_devoluciona_name = $model->responsable_devolucion ? Mds_seg_usuario::getNameUser($model->responsable_devolucion) : '';



?>

<div class="sds-stk-devolucion-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'form-devolucion', // Cambia 'mi-formulario-id' a tu ID deseado
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'fecha_hora_entrega')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'hora_entrega')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'idorganismo')->hiddenInput()->label("") ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'responsable_entrega')->hiddenInput()->label("") ?>
            <?= $form->field($model, 'responsable_devolucion')->hiddenInput()->label("") ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= SiteController::actionGet_input_select2($form,$model,'idarticulo','input_idarticulo',$herramientas,'idarticulo','descripcion','Herramienta','Herramienta')?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= Html::label('Responsable De Entrega', 'label_responsable_entrega', ['id' => 'label_responsable_entrega']) ?>
            <?= Html::textInput('input_responsable_entrega',$responsable_entrega_name, ['id' => 'input_responsable_entrega', 'readonly' => true, 'class' => 'form-control input-md']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= SiteController::actionGet_input_select2($form,$model,'destinatario','input_destinatario',$contactos,'idcontacto','apellido','destinatario','destinatario')?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones_entrega')->textarea(['rows' => 2]) ?>
        </div>
    </div>
    
    <div id="div_devolucion" style=<?= $div_devolucion ?>>
        <div class="row">
            <div class="col-md-6">
                <?= SiteController::actionGet_input_fecha($form,$model,'fecha_hora_devolucion','input_fecha_hora_devolucion', 'Fecha de Devolucion')?>
            </div>
            <div class="col-md-6">
                <?= SiteController::actionGet_input_hora($form,$model,'hora_devolucion','input_hora_devolucion', 'Hora de Devolucion')?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= Html::label('Responsable De Devolucion', 'label_responsable_devolucion', ['id' => 'label_responsable_devolucion']) ?>
                <?= Html::textInput('input_responsable_devolucion',$responsable_devoluciona_name, ['id' => 'input_responsable_devolucion', 'readonly' => true, 'class' => 'form-control input-md']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= SiteController::actionGet_input_select2($form,$model,'estado','input_estado',$estados,'idconfiguracion','descripcion','Estado','Estado')?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'observaciones_devolucion')->textarea(['rows' => 2]) ?>
            </div>
        </div>
    </div>


      
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>


<?php
$script = <<<JS



JS;
$this->registerJs($script);
?>