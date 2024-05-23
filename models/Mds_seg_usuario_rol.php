<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_seg_usuario_rol".
 *
 * @property int $idusuariorol
 * @property int $idusuario
 * @property int $idrol
 *
 * @property MdsSegRol $idrol0
 * @property MdsSegUsuario $idusuario0
 */
class Mds_seg_usuario_rol extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_seg_usuario_rol';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuariorol', 'idusuario', 'idrol'], 'integer'],
            [['idusuario', 'idrol'], 'required'],
            [['idusuario', 'idrol'], 'unique', 'targetAttribute' => ['idusuario', 'idrol']],
            [['idrol'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_rol::className(), 'targetAttribute' => ['idrol' => 'idrol']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idusuariorol' => 'Idusuariorol',
            'idusuario' => 'Idusuario',
            'idrol' => 'Idrol',
        ];
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

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }

    public static function getRolesByUsuario()
    {
        $response = [];
        if (Yii::$app->user && Yii::$app->user->identity) {
            $response = Mds_seg_usuario_rol::find()->where(['idusuario' => Yii::$app->user->identity->idusuario])->all();
        }
        return $response;
    }
    public static function hasRol($idRol)
    {
        $roles = self::getRolesByUsuario();
        $existe = false;
        $columna = array_column($roles, 'idrol');
        if (in_array($idRol, $columna)) {
            $existe = true;
        }

        return $existe;
    }
    public static function usersWithRol($idRol)
    {
        $connection = Yii::$app->getDb();
        $response = [];
        $response = $connection->createCommand(
            "SELECT user.idusuario,UPPER(CONCAT(apellido,', ',nombre)) as apellido_nombre
            FROM mds_seg_usuario_rol AS userRol
            INNER JOIN mds_seg_usuario AS user ON user.idusuario = userRol.idusuario AND userRol.idrol = {$idRol} AND user.activo = 1 
            ORDER BY user.apellido,user.nombre;")
            ->queryAll();
        return $response;
    }
}
