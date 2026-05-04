<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDecreto */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="decreto-modal">

    <div class="decreto-header">
        <div>
            <h3>📑 Nuevo Decreto</h3>
          <p class="decreto-subtitle">Complete la información del decreto</p>
        </div>
    </div>

    <?php $form = ActiveForm::begin(['options' => ['class' => 'decreto-form']]); ?>

    <div class="form-section">
        <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true, 'placeholder' => 'Ej: Decreto 2026-001']) ?>
    </div>

    <div class="form-grid">
        <?= $form->field($model, 'periodo_inicio')->input('date') ?>
        <?= $form->field($model, 'periodo_final')->input('date') ?>
    </div>

    <div class="form-grid">
        <?= $form->field($model, 'periodo_prorroga')->input('date') ?>

        <div class="switch-box">
            <?= $form->field($model, 'activo')->checkbox([
                'label' => 'Activo',
                'class' => 'toggle-switch'
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<style>
/* HEADER */
.decreto-header {
    display: flex;
    margin-bottom: 20px;
}

.decreto-header h3 {
    margin: 0;
    font-weight: 700;
    font-size: 22px;
}

/* SUBTÍTULO MÁS GRANDE */
.decreto-subtitle {
    font-size: 16px;
    color: #777;
    margin-top: 4px;
}

/* CONTENEDOR */
.decreto-modal {
    background: #fff;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* GRID */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* INPUTS */
.decreto-form input {
    border-radius: 10px;
    border: 1px solid #ddd;
    padding: 10px;
    transition: 0.2s;
}

.decreto-form input:focus {
    border-color: #5cb85c;
    box-shadow: 0 0 0 3px rgba(92,184,92,0.15);
    outline: none;
}

/* SWITCH */
.switch-box {
    display: flex;
    align-items: center;
}

.toggle-switch {
    transform: scale(1.2);
    accent-color: #5cb85c;
}

/* FOOTER */
.decreto-footer {
    margin-top: 25px;
    display: flex;
    justify-content: space-between;
}

/* BOTONES */
.btn-save {
    background: #5cb85c;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 10px;
    cursor: pointer;
}

.btn-save:hover {
    background: #4cae4c;
}

.btn-cancel {
    background: #f1f1f1;
    padding: 10px 18px;
    border-radius: 10px;
    text-decoration: none;
    color: #333;
}
</style>
