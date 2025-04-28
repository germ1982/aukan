<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
?>


<!-- Formulario de cambio de contraseña -->
<div id="modal_cambio_contraseña" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_cambio_contraseña_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="modal_cambio_contraseña_label">Cambiar Contraseña</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= \yii\helpers\Url::to(['site/cambiar-password']) ?>">
                    <div class="form-group">
                        <label for="password_actual">Contraseña Actual</label>
                        <input type="password" class="form-control" id="password_actual" name="password_actual">
                        <input type="checkbox" id="mostrar_actual"> Mostrar contraseña
                    </div>
                    <div class="form-group">
                        <label for="nueva_password">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="nueva_password" name="nueva_password">
                        <input type="checkbox" id="mostrar_nueva"> Mostrar contraseña
                    </div>
                    <div class="form-group">
                        <label for="repetir_password">Repetir Contraseña</label>
                        <input type="password" class="form-control" id="repetir_password" name="repetir_password">
                        <input type="checkbox" id="mostrar_repetir"> Mostrar contraseña
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'id' => 'modal_cambio_contraseña',
    'header' => '<h4>Cambiar Contraseña</h4>',
    'size' => Modal::SIZE_SMALL,
]);
Modal::end();
?>

<script>
// Script para mostrar y ocultar contraseñas
$('#mostrar_actual').change(function() {
    $('#password_actual').attr('type', this.checked ? 'text' : 'password');
});

$('#mostrar_nueva').change(function() {
    $('#nueva_password').attr('type', this.checked ? 'text' : 'password');
});

$('#mostrar_repetir').change(function() {
    $('#repetir_password').attr('type', this.checked ? 'text' : 'password');
});
</script>
