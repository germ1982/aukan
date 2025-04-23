<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $documento;
    public $imageFile;
    public $perfil;
    public $password_actual;
    public $password_nueva;
    public $password_nueva_confirmacion;
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
            [['email', 'avatar', 'status', 'password', 'activo', 'idpersona', 'documento'], 'required'],
            [['perfil'], 'safe'],
            [['status', 'activo', 'idpersona'], 'integer'],
            [['email', 'avatar', 'password'], 'string', 'max' => 100],
            [['imageFile'], 'file', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            ['password_actual', 'required', 'on' => 'cambiar_clave'],
            ['password_nueva', 'required', 'on' => 'cambiar_clave'],
            ['password_nueva_confirmacion', 'required', 'on' => 'cambiar_clave'],
            ['password_nueva_confirmacion', 'compare', 'compareAttribute' => 'password_nueva', 'message' => 'La confirmación no coincide', 'on' => 'cambiar_clave'],


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

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Este método no se utiliza en la autenticación básica de Yii2
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        // Este método no se utiliza en la autenticación básica de Yii2
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        // Este método no se utiliza en la autenticación básica de Yii2
        return null;
    }

    public function getPersona()
    {
        return $this->hasOne(Persona::className(), ['idpersona' => 'idpersona']);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['cambiar_clave'] = ['password_actual', 'password_nueva', 'password_nueva_confirmacion'];
        return $scenarios;
    }
}
