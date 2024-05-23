<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_com_menu".
 *
 * @property int $idmenu
 * @property string $descripcion
 * @property int|null $padre
 * @property string|null $ruta para armar href. Nula si es menu desplegable. Ej: index.php?r=mds_ans_negativa
 * @property int|null $iditem Si el item de seguridad es nulo, valido con los de los hijos.
 * @property int $orden De 0 a n por nivel.
 * @property string|null $icono Ej: fas fa-university (font awesome icon)
 *
 * @property Sds_com_menu $padre0
 * @property Sds_com_menu[] $sdsComMenus
 * @property MdsSegItem $iditem0
 */
class Sds_com_menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_com_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'orden'], 'required'],
            [['padre', 'iditem', 'orden'], 'integer'],
            [['ruta'], 'string'],
            [['descripcion'], 'string', 'max' => 35],
            [['icono'], 'string', 'max' => 45],
            [['padre'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_menu::className(), 'targetAttribute' => ['padre' => 'idmenu']],
            [['iditem'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_item::className(), 'targetAttribute' => ['iditem' => 'iditem']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmenu' => 'Idmenu',
            'descripcion' => 'Descripcion',
            'padre' => 'Padre',
            'ruta' => 'Ruta',
            'iditem' => 'Seg_item',
            'orden' => 'Orden',
            'icono' => 'Icono',
        ];
    }

    /**
     * Gets query for [[Padre0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPadre0()
    {
        return $this->hasOne(Sds_com_menu::className(), ['idmenu' => 'padre']);
    }

    /**
     * Gets query for [[SdsComMenus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsComMenus()
    {
        return $this->hasMany(Sds_com_menu::className(), ['padre' => 'idmenu']);
    }

    /**
     * Gets query for [[Iditem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIditem0()
    {
        return $this->hasOne(Mds_seg_item::className(), ['iditem' => 'iditem']);
    }

    public static function getMenuHijos($idmenu)
    {
        return Sds_com_menu::findBySql("SELECT *
        FROM sds_com_menu
        where padre = $idmenu or ($idmenu=-1 and padre is null)
        order by orden")->all();
    }

    public static function getArbolMenu($menupadre, $items)
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idpadre = $menupadre == null ? -1 : $menupadre->idmenu;
        $menuhijos = Sds_com_menu::getMenuHijos($idpadre);
        $tiene_hijos = !empty($menuhijos);
        $html_hijos = "";
        if ($tiene_hijos) {
            foreach ($menuhijos as $menu) {
                $cargar_hijos = false;
                //si el item de seguridad es null, busco que tenga permiso el hijo para mostrarse
                if ($menu->iditem == null) {
                    $cargar_hijos = true;
                } else {
                    //si el item de seguridad no es null, chequeo que el usuario tenga el que corresponde a ese menu.
                    foreach ($items as $item) {
                        if ($menu->iditem == $item->iditem) {
                            $cargar_hijos = true;
                        }
                    }
                }
                if ($cargar_hijos) {
                    $html_hijos = $html_hijos . Sds_com_menu::getArbolMenu($menu, $items);
                }
            }
            if ($html_hijos != "") {
                if ($idpadre != -1) {
                    $html_hijos =  '<ul class="nav nav-children">' . $html_hijos;
                }
                $html_hijos =  $html_hijos . '</ul>';
            }
        }
        $html_arbol = "";
        if ($idpadre == -1) {
            $html_arbol = '<li>
                                <a href="index.php?r=site%2Findex">
                                    <i class="fas fa-home" aria-hidden="true"></i>
                                    <span>Página Principal</span>
                                </a>
                            </li>';
        } else {
            $cargar_menu = false;
            $subpermiso_informatica = false;
            foreach ($items as $item) {
                if (42 == $item->iditem) {
                    $subpermiso_informatica = true;
                }
            }
            if ($idcontacto != null || (($menupadre->idmenu != 35 && $menupadre->idmenu != 60) && $idcontacto == null)
                 || ($subpermiso_informatica && $menupadre->idmenu == 35)) {
                //si el item de seguridad es null, busco que tengan permiso los hijos para mostrarse
                if ($menupadre->iditem == null && ($html_hijos != "" || !$tiene_hijos)) {
                    $cargar_menu = true;
                } else {
                    //si el item de seguridad no es null, chequeo que el usuario tenga el que corresponde a ese menu.
                    foreach ($items as $item) {
                        if ($menupadre->iditem == $item->iditem) {
                            $cargar_menu = true;
                        }
                    }
                }
            }
            if ($cargar_menu) {
                $html_arbol = '<li' . ($tiene_hijos ? ' class="nav-parent"' : '') . '>
                        <a ' . ($menupadre->ruta != null ? 'href="' . $menupadre->ruta . '" onclick="$(\'#loading\').show();" ' : '') . '>
                            <i class="' . $menupadre->icono . '" aria-hidden="true"></i>
                            <span>' . $menupadre->descripcion . '</span>
                        </a>';
            }
        }
        if ($html_arbol != "" && $html_hijos != "") {
            $html_arbol =  $html_arbol . $html_hijos . "</li>";
        }
        return $html_arbol;
    }
}
