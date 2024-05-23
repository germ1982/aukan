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


    public static function getArbolMenu(){
        $menu= Menu::find()->where("activo = 1")->all();
        $menuHtml=  '<li>
                        <a href="index.php?r=site%2Findex">
                            <i class="fas fa-home" aria-hidden="true"></i>
                            <span>Página Principal</span>
                        </a>
                    </li>';
        foreach($menu as $rama){
                $menuHtml=$menuHtml."<li class='nav-parent'>
                                <a>
                                    <i class='fas fa-hard-hat' aria-hidden='true'></i>
                                    <span>$rama->title</span>
                                </a>

                            </li>";
        }
        return $menuHtml;
    }
}
