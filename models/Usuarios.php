<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $email
 * @property string $avatar
 * @property int $status
 * @property string $password
 * @property int $activo
 * @property int $idpersona
 */
class Usuarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'avatar', 'status', 'password', 'activo', 'idpersona'], 'required'],
            [['status', 'activo', 'idpersona'], 'integer'],
            [['email', 'avatar', 'password'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'avatar' => 'Avatar',
            'status' => 'Status',
            'password' => 'Password',
            'activo' => 'Activo',
            'idpersona' => 'Idpersona',
        ];
    }
}
