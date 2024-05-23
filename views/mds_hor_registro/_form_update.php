<?php
use johnitvn\ajaxcrud\CrudAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
CrudAsset::register($this);
$form = ActiveForm::begin();?>
<div class="row">
    <div class="col-md-6">
        <?php echo $form->field($model, 'fecha')->widget(DatePicker::class, [
            'name' => 'check_issue_date',
            'type' => DatePicker::TYPE_INLINE,
            'language' => 'es',
            'layout' => '{picker}{input}{remove}',
            'options' => [
                'id' => 'fecha_registro',
                'class' => 'form-control',
                'readonly' => true
            ],
            'pluginOptions' => [
                'format' => 'dd/mm/yyyy',
                'todayHighlight' => true,
            ]
        ])->label('Fecha'); ?>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        $empleados,
                        'idcontacto',
                        function ($model) {
                            return $model->legajo . " - " . $model->apellido . ", " . $model->nombre;
                        }
                    ),
                    'options' => [
                        'id' => 'cmb_contacto',
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'placeholder' => 'Seleccione Empleado...'
                    ],
                ])->label("Empleado"); ?>
            </div>
        </div>
        <div class="row" id="presente">
            <div class="col-md-12">
                <?= $form->field($model, 'hora')->widget(TimePicker::class, [
                    'options' => [
                        'class' => 'form-control input-sm',
                        'id' => 'hora'
                    ],
                    'pluginOptions' => [
                        'showSeconds' => false,
                        'showMeridian' => false,
                        'minuteStep' => 15,
                    ]
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'observaciones')->textarea(['rows' => 3, 'id' => 'observaciones']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>