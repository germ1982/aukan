<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario_asignacion_perfil".
 *
 * @property int $idusuario
 * @property int $idperfil
 * @property int|null $activo
 */
class UsuarioAsignacionPerfil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_asignacion_perfil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'idperfil'], 'required'],
            [['idusuario', 'idperfil', 'activo'], 'integer'],
            [['idusuario', 'idperfil'], 'unique', 'targetAttribute' => ['idusuario', 'idperfil']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idusuario' => 'Idusuario',
            'idperfil' => 'Idperfil',
            'activo' => 'Activo',
        ];
    }
}
