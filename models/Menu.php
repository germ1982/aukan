<?php

namespace app\models;

use Yii;

/*meter a la base
update menu set link_yii = 'site%2Findex', icon_yii = 'fas fa-home' where id = 1;
update menu set link_yii = '', icon_yii = 'fas fa-laptop' where id = 2;
update menu set link_yii = '', icon_yii = 'fas fa-users' where id = 3;
update menu set link_yii = '', icon_yii = 'fa fa-cog' where id = 4;
update menu set link_yii = 'registro_tecnico', icon_yii = 'fas fa-tools' where id = 5;
update menu set link_yii = 'registro_ips', icon_yii = 'fas fa-network-wired' where id = 6;
update menu set link_yii = 'inventario', icon_yii = 'fas fa-list' where id = 7;
update menu set link_yii = 'persona', icon_yii = 'fas fa-users' where id = 8;
update menu set link_yii = 'fichaje', icon_yii = 'far fa-address-card' where id = 9;
update menu set link_yii = 'empleado', icon_yii = 'fas fa-user-tie' where id = 10;
update menu set link_yii = 'organismo', icon_yii = 'fa fa-institution' where id = 11;
update menu set link_yii = 'usuarios', icon_yii = 'fas fa-user-cog' where id = 12;
update menu set link_yii = 'datos', icon_yii = 'fa fa-database' where id = 13;
update menu set link_yii = 'menu', icon_yii = 'fas fa-list' where id = 14;
update menu set link_yii = 'dispositivo', icon_yii = 'fa fa-institution' where id = 15;

listado fa fa-tasks
mapa fas fa-map-marked-alt'
tarjeta personal far fa-address-card
configuraciones fas fa-cogs
stock fas fa-cubes
contraseñas fas fa-save

 */
class Menu extends \yii\db\ActiveRecord
{
 
      public $icono;
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
                  [['padre', 'activo', 'orden','icono'], 'integer'],
                  [['icono'], 'safe'],
                  [['title', 'link','link_yii'], 'string', 'max' => 40],
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
                  'link_yii' => 'Link',
                  'icon_yii' => 'Icono',
            ];
      }

      public static function getArbolMenu()
      {
            $usuarioPermiso = new UsuarioPerfilPermiso();



            $menu = Menu::find()->where(['activo' => 1, 'padre' => 0])->orderBy('orden')->all();
            $menu_txt = '';
            foreach ($menu as $m) {
                  if($usuarioPermiso->permiso('menu',$m->id,'menu')==true){
                        $menu_txt = $menu_txt .  '<li class='.Menu::va_despliegue($m->id).'>
                              <a'.Menu::es_home($m->id).'>
                              <i class="neon ' . $m->icon_yii . '" aria-hidden="true"></i>
                              <span>' . $m->title . '</span>
                              </a>'. Menu::getHijos($m->id).'<li>';
                  }
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
                                  <i class="neon ' . $m->icon_yii . '" aria-hidden="true"></i>
                                  <span>' . $m->title . '</span>
                                  </a>'. Menu::getHijos($m->id).'<li>';
                  }
                  $menu_txt = $menu_txt . '</ul>';
            }

            return $menu_txt;
      }
}
