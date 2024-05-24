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
    public $recaptchaToken;


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
    /* public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $mensajeError = "Usuario o contraseña incorrecta.";
            $mensajeErrorBlocked = "Usuario bloqueado. Contactarse con su área informática.";

            if ($user) {
                $userModel = Usuarios::find()->where(['idusuario' => $user->idusuario])->one();
                $modelSegUsuarioStatus = new Usuarios_status();
                $modelSegUsuarioStatus->idusuario = $user->idusuario;
                $modelSegUsuarioStatus->created_at = date('Y-m-d H:i:s');
                $modelSegUsuarioStatus->idusuario_carga = $user->idusuario;

                if ($user->validatePassword($this->password)) {
                    // Clave correcta pero esta bloqueada
                    if ($userModel->attemps >= 3) {
                        $modelSegUsuarioStatus->idestado = Usuarios_status::ESTADO_BLOQUEADO;
                        $this->addError($attribute, $mensajeErrorBlocked);
                        $userModel->activo = 0;
                        $userModel->save();
                    } else {
                        // Funciono todo bien
                        $userModel->attemps = 0;
                        $userModel->save();
                    }
                } else {
                    if ($userModel->attemps >= 3) {
                        $this->addError($attribute, $mensajeErrorBlocked);
                        $modelSegUsuarioStatus->idestado = Usuarios_status::ESTADO_BLOQUEADO;
                        $userModel->activo = 0;
                    } else {
                        $modelSegUsuarioStatus->idestado = Usuarios_status::ESTADO_ERROR_PASSWORD;
                        $this->addError($attribute, $mensajeError);
                    }
                    $userModel->attemps = $userModel->attemps + 1;
                    $userModel->save();
                    $modelSegUsuarioStatus->save();
                }
            } else {
                $userInactived = Usuarios::find()
                    ->where("user=:username", [":username" => str_replace(' ', '', $this->username)])
                    ->andWhere("activo=:activo", [":activo" => 0])
                    ->all();
                if ($userInactived) {
                    $mensajeError = $mensajeErrorBlocked;
                }
                $this->addError($attribute, $mensajeError);
            }
        }
    } */

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    /* public function login()
    {
        if ($this->validate()) {
            // Guarda log cuando el usuario accedio correctamente
            $user = $this->getUser();
            $modelSegUsuarioStatus = new Usuarios_status();
            $modelSegUsuarioStatus->idusuario = $user->idusuario;
            $modelSegUsuarioStatus->created_at = date('Y-m-d H:i:s');
            $modelSegUsuarioStatus->idusuario_carga = $user->idusuario;
            $modelSegUsuarioStatus->idestado = Usuarios_status::ESTADO_LOGIN_CORRECTO;
            $modelSegUsuarioStatus->save();
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    } */

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuarios::find()->where(['email' => $this->username])->one();

        }

        return $this->_user;
    }
}
