<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user = false;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
    
            if ($user) {
                // Utiliza el componente de seguridad de Yii para validar la contraseña
                if (Yii::$app->security->validatePassword($this->password, $user->password)) {
                    // Si la contraseña es correcta, restablece los intentos fallidos a 0
                    $user->save();
                } else {
                    // Si la contraseña es incorrecta, añade un error
                    $this->addError($attribute, "Contraseña Incorrecta");
                }
            } else {
                // Si el usuario no existe, añade un error
                $this->addError($attribute, "Email Incorrecto");
            }
        }
    }
    
    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            echo "<script>console.log('Validación exitosa');</script>";
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        echo "<script>console.log('Validación fallida');</script>";
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuarios::find()->where(['email' => $this->username])->one();
            echo "<script>console.log('Búsqueda de usuario: " . json_encode($this->_user) . "');</script>";
        }

        return $this->_user;
    }
}
