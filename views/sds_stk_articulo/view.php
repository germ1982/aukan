<?php

use yii\widgets\DetailView;
use app\models\Sds_com_configuracion;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_articulo */
?>

<div class="sds-stk-articulo-view">

<div class="row">
    <div class="col-md-6">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'idarticulo',
                    'label' => 'Id',
                ],
                'descripcion',
                [
                    'attribute' => 'activo',
                    //'label' => 'Sistema Operativo',
                    'value' => function ($model) 
                                    {
                                        if($model->activo)
                                        {
                                            return 'Si';
                                        }
                                        return "No";
                                    },
                ],
                [
                    'attribute' => 'rubro',
                    'value' => function ($model) 
                                    {
                                        $id = $model->rubro;
                                        if ($id != null) {
                                            $item = Sds_com_configuracion::findOne($id);
                                            return $item->descripcion;
                                        }
                                        return "";
                                    },
                ],
                [
                    'attribute' => 'unidad_medida',
                    //'label' => 'Sistema Operativo',
                    'value' => function ($model) 
                                    {
                                        $id = $model->unidad_medida;
                                        if ($id != null) {
                                            $item = Sds_com_configuracion::findOne($id);
                                            return $item->descripcion;
                                        }
                                        return "";
                                    },
                ],

                [
                    'attribute' => 'ingresado',
                    'label' => 'Stock Ingresado',
                    'value'=> function ($model)
                            {
                                $stock_ingresado = $this->context->actionGet_stock_ingresado($model->idarticulo);
                                return $stock_ingresado;
                            }
                ],
                [
                    'attribute' => 'entregado',
                    'label' => 'Stock Entregado',
                    'value'=> function ($model)
                        {
                            $stock_entregado = $this->context->actionGet_stock_entregado($model->idarticulo);
                            return $stock_entregado;
                        }
                ],
                [
                    'attribute' => 'disponible',
                    'label' => 'Stock Disponible',
                    'value'=> function ($model)
                        {
                            $stock_disponible = $this->context->actionGet_stock_disponible($model->idarticulo);
                            return $stock_disponible;
                        }
                ],
                [
                    'attribute' => 'stock_minimo',
                    'value'=> function ($model)
                        {
                            if($model->stock_minimo)
                                {return $model->stock_minimo;}
                            return '';
                        }
                ],
                [
                    'attribute' => 'devolucion',
                    //'label' => 'Sistema Operativo',
                    'value' => function ($model) 
                                    {
                                        if($model->devolucion)
                                        {
                                            return 'Si';
                                        }
                                        return "No";
                                    },
                ],


            ],
        ]) ?>
    </div>
    <div class="col-md-6">
    <div class="row"><?= $this->context->actionGet_grilla_disponible_en_depositos($model->idarticulo);?></div>    
    <div class="row"><?= Html::img($model->imagen,['class' => 'file-preview-image', 'style' => 'height:200px'])?></div>    
    

    
    </div>
</div>


 
  

</div>
