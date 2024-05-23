<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_seg_permiso".
 *
 * @property int $idpermiso
 * @property string $descripcion
 * @property int $idrol
 * @property int $iditem
 * @property int $alta
 * @property int $baja
 * @property int $modifica
 * @property int $ver
 *
 * @property MdsSegItem $iditem0
 * @property MdsSegRol $idrol0
 */
class Mds_seg_permiso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_seg_permiso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'idrol', 'iditem', 'alta', 'baja', 'modifica', 'ver'], 'required'],
            [['idrol', 'iditem', 'alta', 'baja', 'modifica', 'ver'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
            [['iditem'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_item::className(), 'targetAttribute' => ['iditem' => 'iditem']],
            [['idrol'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_rol::className(), 'targetAttribute' => ['idrol' => 'idrol']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpermiso' => 'Idpermiso',
            'descripcion' => 'Descripción',
            'idrol' => 'Rol',
            'iditem' => 'Item Seguridad',
            'alta' => 'Alta',
            'baja' => 'Baja',
            'modifica' => 'Editar',
            'ver' => 'Ver',
        ];
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

    /**
     * Gets query for [[Idrol0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdrol0()
    {
        return $this->hasOne(Mds_seg_rol::className(), ['idrol' => 'idrol']);
    }

    public static function getPermisosByIdUsuario($idUsuario)
    {
        return Mds_seg_permiso::findBySql("select p.* from mds_seg_permiso p, mds_seg_usuario_rol r 
                                            where p.idrol=r.idrol and r.idusuario=$idUsuario");
    }

    public static function getPermisosRisneuByIdUsuario($idUsuario)
    {
        return Mds_seg_permiso::findBySql("select p.* from mds_seg_permiso p, mds_seg_usuario_rol r 
                                            where p.idrol=r.idrol and 
                                            r.idusuario=$idUsuario and
                                            p.iditem in (" . Mds_seg_item::MODULO_RIS_ENCUESTADOR . ")");
    }

    public static function getAllPermissions($iditem, $idusuario)
    {
        return Mds_seg_permiso::findBySql("SELECT * 
                                            FROM mds_seg_permiso 
                                            WHERE idrol IN (SELECT idrol FROM mds_seg_usuario_rol WHERE idusuario=$idusuario) 
                                            AND iditem = $iditem")->all();
    }

    public static function verificarPermiso($arrayidItemPermiso, $verifyPermiso)
    {
        /* Si verifyPermiso es 'all' verifica que el usuario posea todos los permisos del arrayidItemPermiso
        Si verifyPermiso es 'one' verifica que el usuario posea por lo menos uno de los permisos del arrayidItemPermiso */
        $idUsuario = Yii::$app->user->identity->idusuario;
        $hasPermission = false;
        $idItemPermisosString = "";

        if (count($arrayidItemPermiso)) {
            foreach ($arrayidItemPermiso as $idItem) {
                $idItemPermisosString .= $idItemPermisosString ? ", $idItem" : "$idItem";
            }
    
            $permission = Mds_seg_permiso::findBySql(
                "SELECT DISTINCT(iditem) 
                FROM mds_seg_permiso 
                WHERE idrol IN (SELECT idrol FROM mds_seg_usuario_rol WHERE idusuario=$idUsuario)
                AND iditem IN ($idItemPermisosString)"
            )->all();
    
            if ($verifyPermiso === 'all' && count($arrayidItemPermiso) === count($permission)) {
                $hasPermission = true;
            } else if ($verifyPermiso === 'one' && !empty($permission)) {
                $hasPermission = true;
            }
        }


        return $hasPermission;
    }
}
