<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_registro".
 *
 * @property int $idregistro
 * @property string $fecha_hora
 * @property int $idorganismo
 * @property int $usuario_solicitante
 * @property string $problema
 * @property int|null $usuario_derivacion
 * @property int $registro_abierto 0: Finalizado, 1: Pendiente
 * @property int $incidencia_relacionada
 * @property int $idtipo 7 es el tipo sin derivar
 * @property string|null $fecha_ingreso
 * @property int|null $usuario_ingreso
 * @property string|null $fecha_solucion
 * @property string|null $equipo_detalle
 * @property string|null $ip
 * @property int $iddispositivo
 *
 * @property SdsRegMovimiento[] $sdsRegMovimientos
 * @property MdsOrgDispositivo $iddispositivo0
 * @property SdsRegTipo $idtipo0
 * @property MdsOrgOrganismo $idorganismo0
 * @property MdsOrgContacto $usuarioSolicitante
 */
class Sds_reg_registro extends \yii\db\ActiveRecord
{
    const ENT_INFORMATICA = 2072;
    const ENT_MANTENIMIENTO = 2073;
    const ENT_RUMBO = 2335;

    public $fdesde;
    public $fhasta;
    public $idpersona;
    public $tecnicos; //array de tecnicos
    public $hora;
    public $idcapaitem;
    public $temp_archivo_adjunto_recepcion;
    public $borrar_adjunto_recepcion;
    public $temp_archivo_adjunto_entrega;
    public $borrar_adjunto_entrega;
    public $solucion;
    public $tecnicos_solucion;
    public $id_usuario_derivador;
    public $entidad; //Variable auxiliar para filtrar por entidad (informatica, mantenimiento, etc.)
    public $mis_edificios;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_registro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'idorganismo', 'usuario_solicitante', 'problema','hora','iddispositivo','idtipo'], 'required'],
            [['fecha_hora', 'fecha_ingreso', 'fecha_solucion', 'fdesde', 'fhasta','tecnicos','hora','idcapaitem','borrar_adjunto_recepcion','borrar_adjunto_entrega','solucion', 'tecnicos_solucion','id_usuario_derivador', 'mis_edificios'], 'safe'],
            [['idorganismo', 'usuario_solicitante', 'usuario_derivacion', 'registro_abierto', 'incidencia_relacionada', 'idtipo', 'usuario_ingreso', 'iddispositivo','idpersona','idcapaitem','id_usuario_derivador'], 'integer'],
            [['problema', 'equipo_detalle','adjunto_recepcion','adjunto_entrega','solucion'], 'string'],
            [['ip'], 'string', 'max' => 15],
            [['temp_archivo_adjunto_recepcion','temp_archivo_adjunto_entrega'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],
            [['iddispositivo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_dispositivo::class, 'targetAttribute' => ['iddispositivo' => 'iddispositivo']],
            [['idtipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_reg_tipo::class, 'targetAttribute' => ['idtipo' => 'idtipo']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['usuario_solicitante'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['usuario_solicitante' => 'idcontacto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistro' => 'Idregistro',
            'fecha_hora' => 'Fecha Hora',
            'idorganismo' => 'Idorganismo',
            'usuario_solicitante' => 'Usuario Solicitante',
            'problema' => 'Problema',
            'usuario_derivacion' => 'Usuario Derivacion',
            'registro_abierto' => 'Registro Abierto',
            'incidencia_relacionada' => 'Incidencia Relacionada',
            'idtipo' => 'Idtipo',
            'fecha_ingreso' => 'Fecha Ingreso',
            'usuario_ingreso' => 'Usuario Ingreso',
            'fecha_solucion' => 'Fecha Solucion',
            'equipo_detalle' => 'Equipo Detalle',
            'ip' => 'Ip',
            'iddispositivo' => 'Iddispositivo',
            'mis_edificios' => 'Edificio'
            
        ];
    }

    /**
     * Gets query for [[SdsRegMovimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRegMovimientos()
    {
        return $this->hasMany(Sds_reg_movimiento::class, ['idregistro' => 'idregistro']);
    }

    /**
     * Gets query for [[Iddispositivo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddispositivo0()
    {
        return $this->hasOne(Mds_org_dispositivo::class, ['iddispositivo' => 'iddispositivo']);
    }

    /**
     * Gets query for [[Idtipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtipo0()
    {
        return $this->hasOne(Sds_reg_tipo::class, ['idtipo' => 'idtipo']);
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::class, ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[UsuarioSolicitante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioSolicitante()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'usuario_solicitante']);
    }

    
    public static function getExtension($file)
    {
        $array = explode(".", $file);
        $extension = end($array);
        $extImagenes = array('jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp');
        if (in_array($extension, $extImagenes)) {
            return 'image';
        } else {
            return $extension;
        }
    }
}
