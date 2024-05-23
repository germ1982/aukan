<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_tes_adjunto */
/* @var $form yii\widgets\ActiveForm */
    function get_mes($mes)
        {
            switch ($mes)
            {
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
    function get_tipo($tipo)
    {
        switch ($tipo)
            {
                case "1":
                    $tipo = "Desempleo";
                    break;
                case "2":
                    $tipo = "Familia";
                    break;
                case "3":
                    $tipo = "SST";
                break;      
            }
        return $tipo;
    }
    function get_pago($pago)
        {
            switch ($pago)
                {
                    case "1":
                        $pago = "Acreditación";
                        break;
                    case "2":
                        $pago = "Cheque";
                        break;    
                }
            return $pago;
        }

$tipos = array('',1=>'Desempleo',2=>'Familia',3=>'SST');
$meses = array('',1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio', 8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
$pagos = array('',1=>'Acreditacion',2=>'Cheque');
?>

<div class="sds-tes-adjunto-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <!-- ---------------------------------------------------------------------------------------------------------------------------------- -->
            <?php
                $fecha_desde = date_format(date_create(), 'd-m-Y');
                if ($model->carga != null) 
                    {
                        $model->carga = date('d/m/Y', strtotime(str_replace('/', '-', $model->carga)));
                    }
                else
                {
                    $model->carga = date('d/m/Y', strtotime(str_replace('/', '-', $fecha_desde)));
                }
            ?>
            <?= $form->field($model, 'carga')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'carga',
                    'class' => 'form-control input-md',
                    'disabled' => false
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'startDate' => $fecha_desde,
                    'endDate' => date('d-m-Y'),
                    'todayHighlight' => true,
                    'autoclose' => true,
                    ]
                    ]); 
                ?>
            <!-- ---------------------------------------------------------------------------------------------------------------------------------- -->
            <?php
                if ($model->periodo_mes != null) 
                    {
                        $model->periodo_mes = get_mes($model->periodo_mes);
                    }
                echo $form->field($model, 'periodo_mes')->dropDownList($meses);
            ?>

            <!-- ---------------------------------------------------------------------------------------------------------------------------------- -->

            <?= $form->field($model, 'periodo_anio')->textInput() ?>
            <!-- ---------------------------------------------------------------------------------------------------------------------------------- -->

            <?php
                if ($model->tipo != null) 
                    {
                        $model->tipo = get_tipo($model->tipo);
                    }

            ?>
            <?= $form->field($model, 'tipo')->dropDownList($tipos); ?> 
            <!-- ---------------------------------------------------------------------------------------------------------------------------------- -->

            <?php
                if ($model->pago != null) 
                    {
                        $model->pago = get_pago($model->pago);
                    }
 
            ?>
            <?= $form->field($model, 'pago')->dropDownList($pagos); ?> 
            <!-- ---------------------------------------------------------------------------------------------------------------------------------- -->

  
        </div>
        <div class="col-md-6">
        <?php
                            if ($model->path == null) {
                                echo $form->field($model, 'temp_archivo_adjunto', ['enableClientValidation' => true, 'enableAjaxValidation' => true])
                                    ->widget(FileInput::classname(), [
                                        'options' => ['accept' => '.xls,.xlsx,.csv'],
                                        'language' => 'es',
                                        'pluginOptions' => [
                                            'allowedFileExtensions' => ['xls', 'xlsx', 'csv'],
                                            'showCaption' => false,
                                            'showRemove' => true,
                                            'showUpload' => false,
                                            'showClose' => false,
                                            'mainClass' => 'input-group-sm',
                                            'uploadUrl' => Url::to(['/mds_com_intervencion/update']),
                                            'maxFileSize' => 100000,
                                            'previewFileType' => 'file',
                                            'initialCaption' => $model->path,
                                            'fileActionSettings' => [
                                                'showRemove' => true,
                                                'showUpload' => false,
                                            ]
                                        ],
                                    ]);
                            } else {
                                echo $form->field($model, 'temp_archivo_adjunto', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                    ->widget(FileInput::classname(), [
                                        'options' => ['accept' => '.xls,.xlsx,.csv'],
                                        'language' => 'es',
                                        'pluginOptions' => [
                                            'allowedFileExtensions' => ['xls', 'xlsx', 'csv'],
                                            'showCaption' => true,
                                            'showRemove' => true,
                                            'showUpload' => false,
                                            'showClose' => false,
                                            'mainClass' => 'input-group-sm',
                                            'uploadUrl' => Url::to(['/mds_org_informe/update']),
                                            'maxFileSize' => 100000,
                                            'previewFileType' => 'file',
                                            'initialPreview' => [
                                                Html::img($model->path, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                            ],
                                            'overwriteInitial' => true,
                                            'autoReplace' => true,
                                            'initialCaption' => $model->path,
                                            'fileActionSettings' => [
                                                'showRemove' => true,
                                                'showUpload' => false,
                                            ]
                                        ],
                                        'pluginEvents' => [
                                            "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                            "filereset" => "function() {  }",
                                        ]
                                    ]);
                            }
                            ?>
        </div>
    </div>
            <?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
