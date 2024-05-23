<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_bdc_equipo".
 *
 * @property int $idequipo
 * @property int $tipo idconfiguraciontipo=99
 * @property int $marca idconfiguraciontipo=100
 * @property string|null $modelo
 * @property string|null $matricula
 * @property int $responsable
 * @property int|null $usuario
 * @property string|null $ip
 * @property int|null $procesador idconfiguraciontipo=64
 * @property int|null $memoria idconfiguraciontipo=65
 * @property int|null $disco idconfiguraciontipo=66
 * @property int|null $sistema_operativo idconfiguraciontipo=63
 * @property int|null $conectividad idconfiguraciontipo=67
 * @property string|null $observaciones
 * @property int $idorganismo
 *
 * @property Sds_com_configuracion $conectividad0
 * @property Sds_com_configuracion $disco0
 * @property Sds_com_configuracion $marca0
 * @property Sds_com_configuracion $disco1
 * @property Mds_org_organismo $idorganismo0
 * @property Sds_com_configuracion $procesador0
 * @property Mds_org_contacto $responsable0
 * @property Sds_com_configuracion $sistemaOperativo
 * @property Sds_com_configuracion $tipo0
 * @property Mds_org_contacto $usuario0
 * @property Sds_reg_ip[] $Sds_reg_ips
 */
class Sds_bdc_equipo extends \yii\db\ActiveRecord
{
    const GABINETE=2442;
    const MONITOR=2443;
    const IMPRESORA=2444;
    const NOTEBOOK=2569;
    const CELULAR=2799;
    const RELOJ_FICHADAS=2742;
    const FOTOCOPIADORA=3725;

    //IDContacto Director de Informatica, para responsable de equipos dados de baja
    const DIRECTOR_INFORMATICA=17;
    
    public $estado;
    public $tipo_descripcion;
    public $marca_descripcion;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_bdc_equipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'marca', 'responsable', 'idorganismo'], 'required'],
            [['tipo', 'marca', 'responsable', 'usuario', 'procesador', 'memoria', 'disco', 'sistema_operativo', 'conectividad', 'idorganismo', 'imei'], 'integer'],
            [['imei'], 'integer', 'max'=>999999999999999999, 'tooBig'=>'Verificar cantidad de digitos'],
            [['observaciones'], 'string'],
            [['estado', 'tipo_descripcion', 'marca_descripcion'], 'safe'],
            [['modelo'], 'string', 'max' => 255],
            [['matricula'], 'string', 'max' => 45],
            [['ip'], 'string', 'max' => 15],
            [['conectividad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['conectividad' => 'idconfiguracion']],
            [['disco'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['disco' => 'idconfiguracion']],
            [['marca'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['marca' => 'idconfiguracion']],
            [['disco'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['disco' => 'idconfiguracion']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['procesador'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['procesador' => 'idconfiguracion']],
            [['responsable'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['responsable' => 'idcontacto']],
            [['sistema_operativo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['sistema_operativo' => 'idconfiguracion']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['usuario' => 'idcontacto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idequipo' => 'Id Equipo',
            'tipo' => 'Tipo',
            'tipo_descripcion' => 'Tipo',
            'marca' => 'Marca',
            'modelo' => 'Modelo',
            'matricula' => 'Matricula',
            'responsable' => 'Responsable',
            'usuario' => 'Usuario',
            'ip' => 'IP',
            'procesador' => 'Procesador',
            'memoria' => 'Memoria',
            'disco' => 'Disco',
            'sistema_operativo' => 'Sistema Operativo',
            'conectividad' => 'Conectividad',
            'observaciones' => 'Observaciones',
            'idorganismo' => 'Sector',
            'imei' => 'IMEI'
        ];
    }



    /**
     * Funciones para simplificar acceso a datos relacionados
     */
    public function getMarca_modelo()
    {
        $marca=Sds_com_configuracion::find()->where(['idconfiguracion' => $this->marca])->one();
        return $marca->descripcion.' - '.$this->modelo;
    }



    /**
     * Gets query for [[Conectividad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConectividad0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'conectividad']);
    }

    /**
     * Gets query for [[Disco0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisco0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'disco']);
    }

    /**
     * Gets query for [[Marca0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarca0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'marca']);
    }

    /**
     * Gets query for [[Disco1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisco1()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'disco']);
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
     * Gets query for [[Procesador0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcesador0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'procesador']);
    }

    /**
     * Gets query for [[Responsable0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsable0()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'responsable']);
    }

    /**
     * Gets query for [[SistemaOperativo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSistemaOperativo()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'sistema_operativo']);
    }

    /**
     * Gets query for [[Tipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo']);
    }

    /**
     * Gets query for [[Usuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario0()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'usuario']);
    }

    /**
     * Gets query for [[Sds_reg_ips]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSds_reg_ips()
    {
        return $this->hasMany(Sds_reg_ip::class, ['idequipo' => 'idequipo']);
    }
}
