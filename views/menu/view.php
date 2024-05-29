<?php


use yii\widgets\DetailView;
use app\models\Menu;


$this->title = $model->title;

?>



<div class="menu-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'icon_yii',
            'link_yii',
            [
                  'attribute' => 'padre',
                  'label' => 'Nodo Padre',
                  'value' => function ($model) 
                                  {
                                          if($model->padre==0) return 'Raiz';

                                          $padre = Menu::findOne($model->padre);
                                          return $padre->title;
                                  },
              ],
              [
                  'attribute' => 'activo',
                  'value' => function ($model) {
                      return $model->activo == 1 ? 'Si' : 'No';
                  },
              ],
            'orden',
        ],

    ]) ?>

</div>
