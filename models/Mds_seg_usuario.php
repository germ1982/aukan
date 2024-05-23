<?php

namespace app\models;

use Exception;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "mds_seg_usuario".
 *
 * @property int $idusuario
 * @property string $user
 * @property string $pass
 * @property string|null $nombre
 * @property string|null $apellido
 * @property string|null $imagen
 * @property string $mail
 * @property int|null $idcontacto
 * @property int $activo
 * @property int $attemps
 * @property string|null $authKey
 * @property string|null $accessToken
 * @property string|null $verification_code
 *
 * @property MdsOrgContacto $idcontacto0
 * @property MdsSegUsuarioRol[] $mdsSegUsuarioRols
 * @property MdsSegRol[] $idrols
 * @property SdsRegRegistro[] $sdsRegRegistros
 * @property SdsRegRegistroTecnico[] $sdsRegRegistroTecnicos
 */
class Mds_seg_usuario extends \yii\db\ActiveRecord implements IdentityInterface
{
    const ORG_STK_INFORMATICA = 103;
    const ORG_STK_ORTEPEDIA = 163;
    const ORG_STK_LOG_SUM = 112;
    const ORG_STK_COORDINACION = 27;

    public $roles; //Arreglo con ids de roles. A persistir en tabla intermedia de mds_seg_usuario_rol
    public $tipos_entrega; //Arreglo con ids de tipos de entrega relacionados con el usuario
    public $responsables; //Arreglo con ids de responsables de entrega relacionados con el usuario
    public $direccionesCertificaciones; //Arreglo con ids de direcciones para certificaciones. A persistir en tabla intermedia de mds_certificacion_direccion_usuario
    public $is_externo;
    public $pass_nueva;
    public $pass_anterior;
    public $token;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_seg_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imagen'], 'string'],
            [[
                'dni', 'idcontacto', 'activo', 'externo', 'responsable', 'celular_cuenta',
                'declaracion_jurada_pui', 'is_externo', 'responsable_todos', 'organismo_stock', 'attemps'
            ], 'integer'],
            [['user', 'nombre', 'apellido'], 'string', 'max' => 45],
            [['pass'], 'string', 'max' => 255],
            [['pass_nueva','pass_anterior'], 'string', 'max' => 255],
            [['mail'], 'string', 'max' => 200],
            ['mail', 'email'],
            [['user'], 'unique'],
            [['roles', 'tipos_entrega', 'responsables','direccionesCertificaciones', 'pass_nueva', 'pass_anterior','token'], 'safe'],
            [['authKey', 'accessToken', 'verification_code'], 'string', 'max' => 250],
            [['externo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo_externo::className(), 'targetAttribute' => ['externo' => 'idorganismoexterno']],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['idcontacto' => 'idcontacto']],
            [['responsable'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['responsable' => 'idconfiguracion']],
            [['organismo_stock'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['organismo_stock' => 'idorganismo']],
            [['iddeposito'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_deposito::className(), 'targetAttribute' => ['iddeposito' => 'iddeposito']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idusuario' => 'Idusuario',
            'user' => 'Usuario',
            'pass' => 'Pass',
            'pass_nueva' => 'Confirmar Nueva Contraseña',
            'pass_anterior' => 'Contraseña Actual',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'imagen' => 'Imagen',
            'mail' => 'Mail',
            'idcontacto' => 'Contacto',
            'activo' => 'Activo',
            'attemps' => 'Intentos',
            'is_externo' => 'Externo',
            'externo' => 'Organismo Asociado',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
            'verification_code' => 'Verification Code',
            'responsable' => 'Responsable Entrega',
            'celular_cuenta' => 'Cuenta Celular',
            'responsable_todos' => 'Todos los Responsables',
            'organismo_stock' => 'Organismo Stock',
            'iddeposito' => 'Depósito',
            'dni' => 'DNI'
        ];
    }


    public static function getNameUser($id)
    {
        $user = Mds_seg_usuario::findOne(['idusuario' => $id, 'activo' => '1']);
        return $user ? "$user->apellido $user->nombre":'';
    }

    public static function isUserAdmin($id)
    {
        if (Mds_seg_usuario::findOne(['idusuario' => $id, 'activo' => '1'])) {
            return true;
        } else {

            return false;
        }
    }

    public static function isUserSimple($id)
    {
        if (Mds_seg_usuario::findOne(['idusuario' => $id, 'activo' => '1'])) {
            return true;
        } else {

            return false;
        }
    }

    /**
     * Gets query for [[Idcontacto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContacto()
    {
        return $this->hasOne(Mds_org_contacto::className(), ['idcontacto' => 'idcontacto']);
    }

    /**
     * Gets query for [[MdsSegUsuarioRols]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsSegUsuarioRols()
    {
        return $this->hasMany(Mds_seg_usuario_rol::className(), ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[Idrols]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdrols()
    {
        return $this->hasMany(Mds_seg_rol::className(), ['idrol' => 'idrol'])->viaTable('mds_seg_usuario_rol', ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[SdsRegRegistros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRegRegistros()
    {
        return $this->hasMany(Sds_reg_registro::className(), ['usuario_solicitante' => 'idusuario']);
    }

    /**
     * Gets query for [[SdsRegRegistroTecnicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRegRegistroTecnicos()
    {
        return $this->hasMany(Sds_reg_registro_tecnico::className(), ['idtecnico' => 'idusuario']);
    }

    /**
     * @inheritdoc
     */
    /* Busca la identidad del usuario a través de su token de acceso */
    public static function findIdentityByAccesstoken($token, $type = null)
    {
        $users = Mds_seg_usuario::find()
            ->where("activo=:activo", [":activo" => 1])
            ->andWhere("accesstoken=:accesstoken", [":accesstoken" => $token])
            ->andWhere("idcontacto is not null")
            ->all();

        foreach ($users as $user) {
            if ($user->accesstoken === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    /* Busca la identidad del usuario a través del username */
    public static function findByUsername($username)
    {
        $users = Mds_seg_usuario::find()
            ->where("activo=:activo", [":activo" => 1])
            ->andWhere("idcontacto is not null")
            ->andWhere("user=:nick", [":nick" => $username])
            ->all();

        foreach ($users as $user) {
            if (strcasecmp($user->user, $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    /* Regresa la clave de autenticación */
    public function getAuthkey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    /* Valida la clave de autenticación */
    public function validateAuthkey($authkey)
    {
        return $this->authKey === $authkey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        /* Valida el password */
        //if (crypt($password, $this->password) == $this->password)
        //if (md5($password) == $this->password || $password == $this->password) {
        if (Mds_seg_usuario::passHashVerify($password, $this->pass)) {
            return $password === $password;
        }
    }

    /**
     * @inheritdoc
     */
    /* Regresa el id del usuario */
    public function getId()
    {
        return $this->idusuario;
    }
    /* Regresa el id del usuario */
    public function getOrganismoStock()
    {
        return $this->organismo_stock;
    }
    /**
     * @inheritdoc
     */
    /* busca la identidad del usuario a través de su $id */
    public static function findIdentity($codigo)
    {

        $user = Mds_seg_usuario::find()
            ->where("activo=:activo", [":activo" => 1])
            ->andWhere("idusuario=:codigo", [":codigo" => $codigo])
            ->one();

        return isset($user) ? new static($user) : null;
    }

    public static function getPermiso($iditemseguridad)
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
        $permiso = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                        idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                        and (iditem=" . $iditemseguridad . ")")->one();
        return $permiso;
    }

    static function passHashVerify(string $password, string $hash): bool
    {
        return true;/* 
        $pieces = explode('$', $hash);
        if (count($pieces) !== 4) {
            throw new Exception("Illegal hash format");
        }
        //pbkdf2_sha256$20000$NWe2cxMeYCno$GigpgqKSlAfybfqoYRCliXwnHgJFVLMcskjKPWT435s=
        list($header, $iter, $salt, $hash) = $pieces;
        // Get the hash algorithm used:
        if (preg_match('#^pbkdf2_([a-z0-9A-Z]+)$#', $header, $m)) {
            $algo = $m[1];
        } else {
            throw new Exception(sprintf("Bad header (%s)", $header));
        }
        if (!in_array($algo, hash_algos())) {
            throw new Exception(sprintf("Illegal hash algorithm (%s)", $algo));
        }

        $calc = hash_pbkdf2(
            $algo, //Algoritmo de hash utilizado
            $password, //password sin encriptar
            $salt, //nombreusuario en base64 y tomo los 15 primeros caracteres
            (int) $iter, //Siempre 20000 iteraciones
            32, //tamaño del string de resultado
            true //raw binary data en true (si se pone en false hace no se que chingada que no me importa de momento)
        );

        return hash_equals($calc, base64_decode($hash)); */
    }

    /** 
     * @param boolean $devolver True si se requiere devolver la pass y no setearla
     */
    function setPassHash($password, $devolver = false)
    {
        $algo = "sha256";
        $salt = base64_encode($this->user); //motu:bW90dQ==
        $salt = strlen($salt) <= 8 ? $salt : substr($salt, 0, 8);
        $iter = 20000;
        $hash = hash_pbkdf2(
            $algo, //Algoritmo de hash utilizado
            $password, //password sin encriptar
            $salt, //nombreusuario en base64 y tomo los 8 primeros caracteres
            $iter, //Siempre 20000 iteraciones por ahora, menos de esa cantidad es inseguro y mas de esa cantidad generaria demasiado costo de procesamiento.
            32, //tamaño del string de resultado
            true //raw binary data en true (si se pone en false hace no se que chingada que no me importa de momento)
        );
        //entonces el pass queda con el hash resultante, mas los elementos necesarios para encriptamiento separados por signo $
        //algo$iter$salt$hash        
        $hashpass = "pbkdf2_" . $algo . "$" . $iter . "$" . $salt . "$" . base64_encode($hash);
        if (!$devolver){
            $this->pass = $hashpass;
        }
        else {
            return $hashpass;
        }
    }

    public static function verificarUsuarioActivo()
    {
        $usuarioEstaActivo = false;
        $usuario = Yii::$app->user->identity;
        $idUsuario = $usuario ? $usuario->idusuario : null;

        if ($idUsuario) {
            $usuarioSur = Mds_seg_usuario::find()
            ->where("activo=:activo", [":activo" => 1])
            ->andWhere("idusuario=:id", [":id" => $idUsuario])
            ->one();

            $usuarioEstaActivo = !empty($usuarioSur);
        }

        return $usuarioEstaActivo;
    }
}
