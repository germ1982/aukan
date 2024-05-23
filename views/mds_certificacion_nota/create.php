<?php

use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

$form = ActiveForm::begin(['id' => 'formCertificacionNota', 'action' => ['mds_certificacion_nota/store', 'idcertificacionnota' => $model->isNewRecord ? null : $model->idcertificacionnota, 'idcertificacion' => $certificacion->idcertificacion], 'options' => ['enctype' => 'multipart/form-data', 'form-certificacion-nota']]);
$toolbarOptions = [
    [
        [
            'size' => [
                'small',
                false,
                'large',
                'huge',
            ],
        ],
    ],
    [
        'bold',
        'italic',
        'underline',
        'strike',
    ],
    [
        ['color' => []],
        ['background' => []],
    ],
    [
        ['header' => 1],
        ['header' => 2],
    ],
    [
        ['list' => 'ordered'],
        ['list' => 'bullet'],
        ['indent' => '-1'],
        ['indent' => '+1'],
    ],
    [
        ['align' => []],
    ],
    [
        'clean',
    ],
];

?>


<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <?php
        $fecha = $certificacion->created_at;
        if ($model && $model->fecha != null) {
            $fecha = $model->fecha;
        }
        $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $fecha)));
        echo $form->field($model, 'fecha')->widget(DatePicker::class, [
            'name' => 'check_issue_date',
            'language' => 'es',
            'readonly' => false,
            'layout' => '{picker}{input}{remove}',
            'options' => [
                'id' => 'fecha',
                'class' => 'form-control input-md',
                'disabled' => false,
                'autocomplete' => 'off'
            ],
            'pluginOptions' => [
                'value' => null,
                'format' => 'dd/mm/yyyy',
                'todayHighlight' => true,
                'autoclose' => true,
            ]
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'numero')->input('number') ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'anio')->input('number') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'destinatario_nombre')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'destinatario_direccion')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'referencia')->widget(\bizley\quill\Quill::class, [
            'allowResize' => true,
            'options' => [
                'style' => 'height: 50px;',
                'id' => 'referencia',
            ],
            'toolbarOptions' => $toolbarOptions
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'nota')->widget(\bizley\quill\Quill::class, [
            'allowResize' => true,
            'options' => [
                'style' => 'height: 200px;',
                'id' => 'nota',
            ],
            'toolbarOptions' => $toolbarOptions
        ]) ?>
    </div>
</div>

<?php ActiveForm::end();
