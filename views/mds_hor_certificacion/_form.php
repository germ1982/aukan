<?php
use app\models\Mds_org_contacto;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\TimePicker;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_certificacion */
/* @var $form yii\widgets\ActiveForm */
$meses = array('',1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio', 8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
function get_mes($mes){
    switch ($mes){
        case "1":
            $mes = "Enero";
            break;
        case "2":
            $mes =  "Febrero";
            break;
        case "3":
            $mes =  "Marzo";
            break;
        case "4":
            $mes =  "Abril";
            break;
        case "5":
            $mes = "Mayo";
            break;
        case "6":
            $mes =  "Junio";
            break;
        case "7":
            $mes =  "Julio";
            break;
        case "8":
            $mes =  "Agosto";
            break;
        case "9":
            $mes = "Septiembre";
            break;
        case "10":
            $mes =  "Octubre";
            break;
        case "11":
            $mes =  "Noviembre";
            break;
        case "12":
            $mes =  "Diciembre";
            break;
    }
    return $mes;
}   

 $this->title = 'Crear Certificación';

if(!Yii::$app->request->isAjax):?>
    <header class="page-header">
        <h2><?= $this->title ?></h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <?php if (!Yii::$app->request->isAjax):?>
                    <li>
                        <a href="index.php?r=mds_hor_certificacion"><span>Certificaciones</span></a>
                    </li>
                <?php endif; ?>
                <li><span><u><?= $this->title ?></u></span></li>
            </ol>

            <div class="sidebar-right-toggle"></div>
        </div>
    </header>
<?php endif;
//Alerts Success y Error:
if (Yii::$app->session->hasFlash('save')) : ?>
    <div class="alert alert-success alert-dismissable flash" id="flash-ok" style="margin-bottom: 0">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
        <b><?= Yii::$app->session->getFlash('save') ?></b>
    </div>
<?php endif;
if(Yii::$app->session->hasFlash('warning')) : ?>
    <div class="alert alert-warning alert-dismissable flash" id="flash-fail" style="margin-bottom: 0">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fas fa-exclamation-triangle"></i> ¡Algunas certificaciones no pudieron ser guardadas!</h4>
        <b><?= Yii::$app->session->getFlash('warning') ?></b>
    </div>
<?php endif;
if(Yii::$app->session->hasFlash('fail')) : ?>
    <div class="alert alert-danger alert-dismissable flash" id="flash-fail" style="margin-bottom: 0">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fas fa-times"></i> ¡UPS!</h4>
        <b><?= Yii::$app->session->getFlash('fail') ?></b>
    </div>
<?php endif;?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body" style="padding-top: 0;">
                <div class="mds-hor-certificacion-form" style="padding:1px 8px;">
                    <?php $form = ActiveForm::begin(['id' => 'form-certificacion']); ?>
                    <?php if(!$model->isNewRecord): ?>
                        <div class="row" style="padding-bottom: 2px;">
                            <span class="glyphicon glyphicon-info-sign text-info"></span> 
                            <span class="text-info text-sm">Esta acción borrará los registros horarios asociados a la certificación
                            y creará nuevos registros actualizados.</span>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6">
                                <?= $form->field($model, 'certificado')->widget(Select2::class, [
                                    'id' => 'cmb_contacto_certificado',
                                    'data' => $contactos,
                                    'options' => [
                                        'placeholder' => 'Seleccionar Contacto ...', 
                                        'id' => 'cmb_contacto_certificado'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                                ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'certificante')->widget(Select2::class, [
                                        'id' => 'cmb_contacto_certificante',
                                        'data' => $contactos,
                                        'options' => ['placeholder' => 'Seleccionar Contacto ...', 'id' => 'cmb_contacto_certificante'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                                    ?>
                        </div>
                    </div>
                                
                    <div class="row">
                        <div class="col-md-5" style="border:1px solid #dedede; border-radius:5px; padding:5px; margin-left:25px;">
                            <?php
                                if(!$model->isNewRecord){
                                    $model->periodo_mes=str_pad($model->periodo_mes,2,"0", STR_PAD_LEFT).'/'.$model->periodo_anio;
                                }
                                echo $form->field($model, 'periodo_mes')->widget(DatePicker::class, [
                                    'name' => 'check_issue_date',
                                    'type' => DatePicker::TYPE_INLINE,
                                    'language' => 'es',
                                    'readonly' => false,
                                    'layout' => '{picker}{input}{remove}',
                                    'options' => [
                                        'id' => 'fecha_registro',
                                        'class' => 'form-control input-md',
                                        'disabled' => false,
                                    ],
                                    'pluginOptions' => [
                                        'value' => null,
                                        'defaultDate' => null,
                                        'format' => 'mm/yyyy',
                                        'startDate' => '-1y',
                                        'endDate' => '+1y',
                                        'minViewMode' => DatePicker::TYPE_INPUT,     
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                        'multidate' => $model->isNewRecord ? true:false,
                                    ],
                                ])->label('Periodo Mes'); ?>
                        </div>
                        <div class="col-md-5 ol-md-offset-1" style="border:1px solid #dedede; border-radius:5px; padding: 10px; margin-left: 9%">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                        if ($model->desde==null){
                                            $model->desde = '08:00';
                                        }
                                        echo $form->field($model, 'desde')->widget(TimePicker::class, [
                                            'options' => [
                                                'id' => 'hora_desde',
                                                'disabled' => false,
                                            ],
                                            'pluginOptions' => [
                                                'showSeconds' => false,
                                                'showMeridian' => false,
                                                'minuteStep' => 1,
                                                'secondStep' => 1,
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php     
                                        if ($model->hasta==null){
                                            $model->hasta = '15:00';
                                        }
                                        echo $form->field($model, 'hasta')->widget(TimePicker::class, [
                                            'options' => [
                                                'id' => 'hora_hasta',
                                                'disabled' => false,
                                            ],
                                            'pluginOptions' => [
                                                'showSeconds' => false,
                                                'showMeridian' => false,
                                                'minuteStep' => 1,
                                                'secondStep' => 1,
                                            ]
                                        ]);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'detalle')->textarea(['rows' => 9]) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($model->isNewRecord): ?>
                        <div class="col-md-6" style="margin-top:10px;">
                            <?= $form->field($model, 'reset_form')->checkbox() ?>
                        </div>
                    <?php endif; ?>
                        
                	<?php if (!Yii::$app->request->isAjax) { ?>
                        <br>
                        <div class="form-group">
                            <?= Html::submitButton('Guardar', ['class' => 'col-md-6 col-md-offset-3 btn btn-success', 'id'=>'submit-btn'])?>
                        </div>
                    <?php } ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>