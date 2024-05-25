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
            [['title', 'link'], 'string', 'max' => 30],
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
            'title' => 'Title',
            'type' => 'Type',
            'icon' => 'Icon',
            'link' => 'Link',
            'padre' => 'Padre',
            'activo' => 'Activo',
            'orden' => 'Orden',
        ];
    }

    public static function getArbolMenu()
    {
            $menu = Menu::find()->where(['activo'=>1, 'padre'=>0])->orderBy('orden')->all();
            $menu_txt = "";
            foreach ($menu as $m) {
                  $menu_txt = $menu_txt.  '<li class="nav-parent">
                        <a href="' . $m->link . '" onclick="" >
                            <i class="' . $m->icono . '" aria-hidden="true"></i>
                            <span>' . $m->title . '</span>
                        </a>';
            }
            return $menu_txt;
    }

}
