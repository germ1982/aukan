<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_seg_rol".
 *
 * @property int $idrol
 * @property string $descripcion
 *
 * @property MdsSegPermiso[] $mdsSegPermisos
 * @property MdsSegUsuarioRol[] $mdsSegUsuarioRols
 * @property MdsSegUsuario[] $idusuarios
 */
class Mds_seg_rol extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_seg_rol';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 100],
            [['idusuario_carga', 'idusuario_borra', 'idusuario_modifica'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_modifica'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario_modifica' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario_borra' => 'idusuario']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idrol' => 'Idrol',
            'descripcion' => 'Descripción',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Activo'
        ];
    }

    /**
     * Gets query for [[MdsSegPermisos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsSegPermisos()
    {
        return $this->hasMany(Mds_seg_permiso::className(), ['idrol' => 'idrol']);
    }

    /**
     * Gets query for [[MdsSegUsuarioRols]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsSegUsuarioRols()
    {
        return $this->hasMany(Mds_seg_usuario_rol::className(), ['idrol' => 'idrol']);
    }

    /**
     * Gets query for [[Idusuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuarios()
    {
        return $this->hasMany(Mds_seg_usuario::className(), ['idusuario' => 'idusuario'])->viaTable('mds_seg_usuario_rol', ['idrol' => 'idrol']);
    }

    public function getUsuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario_borra']);
    }

    public static function getUsuariosActivosByRol($idrol)
    {
        $usuarios = Mds_seg_rol::find()
            ->select(['usuario.idusuario', 'usuario.apellido', 'usuario.nombre', 'usuario.user', 'dispositivo.descripcion as dispositivo', 'organismo.descripcion as organismo'])
            ->innerJoin('mds_seg_usuario_rol', 'mds_seg_usuario_rol.idrol = mds_seg_rol.idrol')
            ->innerJoin('mds_seg_usuario usuario', 'mds_seg_usuario_rol.idusuario = usuario.idusuario')
            ->innerJoin('mds_org_contacto contacto', 'usuario.idcontacto = contacto.idcontacto')
            ->innerJoin('mds_org_dispositivo dispositivo', 'contacto.iddispositivo = dispositivo.iddispositivo')
            ->innerJoin('mds_org_organismo organismo', 'dispositivo.idorganismo = organismo.idorganismo')
            ->where(['mds_seg_usuario_rol.idrol' => $idrol, 'usuario.activo' => 1])
            ->orderBy(['usuario.apellido' => SORT_ASC])
            ->asArray()
            ->all();

        return $usuarios ? $usuarios : null;
    }
}
