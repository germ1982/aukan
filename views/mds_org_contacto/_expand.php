<?php

use app\models\Sds_com_localidad;
use yii\widgets\DetailView;
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">Datos Contacto:</h2>
            </header>
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-4">
                        <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'telefono',
                                        'value' => function ($model) {
                                            return $model->telefono ? $model->telefono : '';
                                        },
                                    ],
                                    [
                                        'attribute' => 'mail',
                                        'value' => function ($model) {
                                            return $model->mail ? $model->mail : '';
                                        },
                                    ],
                                    
                                ],
                            ]) ?>
                    </div>      
                    <div class="col-md-4">
                            <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'acompaniante',
                                    'value' => function ($model) {
                                        return $model->acompaniante == 1 ? 'Si' : 'No';
                                    },
                                ],
                                [
                                    'attribute' => 'interno',
                                    'value' => function ($model) {
                                        return $model->interno == 1 ? 'Si' : 'No';
                                    },
                                ],
                                
                            ],
                        ]) ?>

                    </div>
                    
                    <div class="col-md-4">
                        <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'rotativo',
                                        'value' => function ($model) {
                                            return $model->rotativo == 1 ? 'Si' : 'No';
                                        },
                                    ],
                                    [
                                        'attribute' => 'idlocalidad',
                                        'value' => function ($model) {
                                            $idlocalidad = $model->idlocalidad;
                                            if($idlocalidad!=null){
                                                $localidad = Sds_com_localidad::findOne($idlocalidad);
                                                return $localidad->idprovincia==58 ? "DP":"FP";
                                            }
                                            return "No";
                                        },
                                        'label' => 'Dom.',
                                    ],
                                    
                                ],
                            ]) ?>
                    </div>
                </div>
                
                
            </div>
            
        </section>
    </div>
</div>