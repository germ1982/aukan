<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_seg_usuario_status".
 *
 * @property int $idseg_usuario_status
 * @property int $idusuario Usuario que carga
 * @property int $idusuario_borra Usuario que borra
 * @property int $idusuario_carga Usuario que carga
 * @property int $idestado Estado
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 */

class Mds_seg_usuario_status extends \yii\db\ActiveRecord
{

    const ESTADO_BLOQUEADO = 6547; 
    const ESTADO_DESBLOQUEADO = 6548; 
    const ESTADO_ERROR_PASSWORD = 6546;
    const ESTADO_ERROR_CAPTCHA = 6562;
    const ESTADO_LOGIN_CORRECTO = 6551;
    const ESTADO_CAMBIO_CLAVE = 6552; 
    const ITEM_SEG_USUARIO_STATUS = 265;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_seg_usuario_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idestado', 'idusuario', 'created_at',], 'required'],
            [['idseg_usuario_status', 'idusuario', 'idusuario_borra'], 'integer'],
            [['created_at'], 'safe'],
            [['created_at', 'updated_at', 'deleted_at'], 'string'],
            [['idestado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idestado' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idseg_usuario_status' => '#',
            'idusuario' => 'Usuario',
            'idestado' => 'Estado',
            'idusuario_carga' => 'Usuario de carga',
            'idusuario_borra' => 'Usuario borra',
            'created_at' => 'Fecha de carga',
            'updated_at' => 'Fecha de actualización',
            'deleted_at' => 'Activo',
        ];
    }

    /**
     * Gets query for [[idestado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idestado']);
    }


    /**
     * Gets query for [[idusuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[idusuario_carga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }

    /**
     * Gets query for [[idusuario_borra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
    }

    /**
     * Gets query for [[created_at]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaCarga()
    {
        $date = date_create($this->created_at);
        $fecha = date_format($date, 'd/m/Y');
        $hora = date_format($date, 'H:i');
        return "$fecha a las $hora" . "hs";
    }

    public static function getStatusFiltro()
    {
        return Mds_seg_usuario_status::find()
            ->select("configuracion.idconfiguracion, configuracion.descripcion as descripcion")
            ->from("mds_seg_usuario_status as seg_usuario_status")
            ->innerJoin('sds_com_configuracion as configuracion', 'configuracion.idconfiguracion = seg_usuario_status.idestado')
            ->where("seg_usuario_status.deleted_at IS NULL 
                AND configuracion.activo = 1")
            ->orderBy(['descripcion' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getUsuarioFiltro($typeUser)
    {
        //typeUser = Puede ser 'idusuario' o 'idusuario_carga'
        
        return Mds_seg_usuario_status::find()
            ->select(["usuario.idusuario as idusuario, CONCAT(UPPER(usuario.apellido),', ', UPPER(usuario.nombre)) as nombre_usuario"])
            ->from("mds_seg_usuario_status as seg_usuario_status")
            ->innerJoin("mds_seg_usuario as usuario", "usuario.idusuario = seg_usuario_status.$typeUser")
            ->where("seg_usuario_status.deleted_at IS NULL")
            ->orderBy(['nombre_usuario' => SORT_ASC])
            ->asArray()
            ->all();
    }
}
