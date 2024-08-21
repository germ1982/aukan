<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario_perfil_permiso".
 *
 * @property int $idpermiso
 * @property int $idperfil
 * @property int $idtipopermiso
 * @property int|null $idacceso
 * @property string $descripcion
 */
class UsuarioPerfilPermiso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_perfil_permiso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idperfil', 'idtipopermiso', 'descripcion'], 'required'],
            [['idperfil', 'idtipopermiso', 'idacceso'], 'integer'],
            [['descripcion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpermiso' => 'Idpermiso',
            'idperfil' => 'Idperfil',
            'idtipopermiso' => 'Idtipopermiso',
            'idacceso' => 'Idacceso',
            'descripcion' => 'Descripcion',
        ];
    }
}
