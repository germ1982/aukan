<?php

use yii\helpers\Html;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_persona;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_registro_autosolicitud */

?>


<div class="sds-reg-registro-autosolicitud-create">
    <?php 
        $user  = Yii::$app->user->identity;
        $model_usuario = Mds_seg_usuario::findOne($user->idusuario);
        $model_contacto = Mds_org_contacto::findOne($model_usuario->idcontacto);
        $model_persona = Sds_com_persona::findOne($model_contacto->idpersona);
        if($model_contacto->activo==0)
            {
                echo "Contacto id $model_contacto->idcontacto no activo <br>El contacto $model_persona->apellido, $model_persona->nombre no esta activo<br>Solo los contactos activos pueden solicitar asistencia";
            }
        else
            {
                echo $this->render('_form', ['model' => $model,]);
            }
    ?>
</div>



        
