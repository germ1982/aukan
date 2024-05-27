<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property string $icon
 * @property string $link
 * @property int $padre
 * @property int $activo
 * @property int $orden
 */
class Menu extends \yii\db\ActiveRecord
{
      /**
       * {@inheritdoc}
       */
      public static function tableName()
      {
            return 'menu';
      }

      /**
       * {@inheritdoc}
       */
      public function rules()
      {
            return [
                  [['title', 'type', 'icon', 'link', 'orden'], 'required'],
                  [['padre', 'activo', 'orden'], 'integer'],
                  [['title', 'link','link_yii'], 'string', 'max' => 30],
                  [['link_yii'], 'string', 'max' => 100],
                  [['icon_yii'], 'string', 'max' => 20],
                  [['type'], 'string', 'max' => 10],
                  [['icon'], 'string', 'max' => 60],
            ];
      }

      /**
       * {@inheritdoc}
       */
      public function attributeLabels()
      {
            return [
                  'id' => 'ID',
                  'title' => 'Titulo',
                  'type' => 'Tipo',
                  'icon' => 'Icono',
                  'link' => 'Link',
                  'padre' => 'Padre',
                  'activo' => 'Activo',
                  'orden' => 'Orden',
                  'link_yii' => 'Link Para Yii2',
                  'icon_yii' => 'Icono para Yii2',
            ];
      }

      public static function getArbolMenu()
      {
            $menu = Menu::find()->where(['activo' => 1, 'padre' => 0])->orderBy('orden')->all();
            $menu_txt = '';
            foreach ($menu as $m) {
                  $menu_txt = $menu_txt .  '<li class='.Menu::va_despliegue($m->id).'>
                        <a'.Menu::es_home($m->id).'>
                            <i class="neon ' . $m->icon_yii . '" aria-hidden="true"></i>
                            <span>' . $m->title . '</span>
                        </a>'. Menu::getHijos($m->id).'<li>';
            }
            return $menu_txt;
      }

      public static function va_despliegue($padre){
            $menu = Menu::find()->where(['activo' => 1, 'padre' => $padre])->all();
            return $menu ? '"nav-parent"' : '""';
      }

      public static function es_home($id){
            return $id==1 ? ' href="index.php?r=site%2Findex"' : '';
      }


      public static function getHijos($padre)
      {
            $menu = Menu::find()->where(['activo' => 1, 'padre' => $padre])->orderBy('orden')->all();
            $menu_txt = "";
            if($menu){
                  $menu_txt = $menu_txt . '<ul class="nav nav-children">';
                  foreach ($menu as $m) {
                        $menu_txt = $menu_txt .  '<li '.Menu::va_despliegue($m->id).'>
                              <a href="index.php?r=' . $m->link_yii . '">
                                  <i class="' . $m->icon . '" aria-hidden="true"></i>
                                  <span>' . $m->title . '</span>
                                  </a>'. Menu::getHijos($m->id).'<li>';
                  }
                  $menu_txt = $menu_txt . '</ul>';
            }

            return $menu_txt;
      }
}
