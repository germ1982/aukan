<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_gis_capa */
?>
<div class="sds-gis-capa-view">
    <?php $form = ActiveForm::begin(); 

    ?>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">    
                <?= $form->field($model, 'descripcion')->textarea(['rows' => 6,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
            </div>
        </div>  
        <div class="row">
            <div class="col-md-4">    
                <?php
                    if ($model->activo==1) {echo '*** La capa esta activa';}
                    else
                    if ($model->activo==0) {echo '*** La capa no esta activa';}
                ?>                
            </div>
        </div><br>
        <div class="row">
            <div class='col-md-12' >                                                                    
                        <?php
                        if ($model->capa_icono == null) {
                            echo ' No hay un icono guardado';
                        }
                        else
                        {
                            echo '
                            <figcaption class="text-left">Capa Icono</figcaption>
                                <img  src="';                                
                                echo Url::base() . '/'.$model->capa_icono ;
                                echo  '">
                                
                            ';

                        }
                            ?>                        
            </div>       
        </div> 
    </div>
   
</div>
