<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organismo_dispositivo".
 *
 * @property int $iddispositivo
 * @property string $descripcion
 * @property int $idorganismo
 * @property int $es_oficial
 * @property int $es_organismo
 * @property int $activo
 * @property string $alias
 * @property int $idcapaitem
 * @property string|null $telefono
 *
 * @property Organismo $idorganismo0
 */
class OrganismoDispositivo extends \yii\db\ActiveRecord
{
    public $organismo;

    public static function tableName()
    {
        return 'organismo_dispositivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'idorganismo', 'alias','idoficina'], 'required'],
            [['idorganismo', 'es_oficial', 'es_organismo', 'activo', 'idcapaitem','idoficina'], 'integer'],
            [['descripcion', 'alias', 'telefono'], 'string', 'max' => 100],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['organismo'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddispositivo' => 'ID',
            'descripcion' => 'Descripcion',
            'idorganismo' => 'Organismo',
            'es_oficial' => 'Es Oficial',
            'es_organismo' => 'Es Organismo',
            'activo' => 'Activo',
            'alias' => 'Alias',
            'idcapaitem' => 'Capa',
            'telefono' => 'Telefono',
            'idoficina' => 'Oficina',
        ];
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Organismo::className(), ['idorganismo' => 'idorganismo']);
    }


    public static function get_dispositivos($modulo='')
    {
        //modificar para agregar oficina
        $filtro = $modulo ? " and d.iddispositivo in (SELECT iddispositivo from $modulo)" :'';
        $sql = "SELECT d.iddispositivo, concat(o.abreviatura,' - ', d.descripcion) as descripcion 
        FROM organismo o 
        join organismo_dispositivo d on o.idorganismo = d.idorganismo
        where o.activo = 1 and d.activo = 1 $filtro
        order by o.abreviatura, d.descripcion";
        $array = OrganismoDispositivo::findBySql($sql)->all();
        return $array;
    }

    public static function get_dispositivos_con_decreto($activo=false)
    {
        //modificar para agregar oficina
        $filtro = $activo ? "where od.activo = 1" :'';
        $sql = "SELECT d.iddispositivo, CONCAT('DECRETO: ',od.descripcion , ' (', DATE_FORMAT(od.periodo_inicio, '%d/%m/%Y'),') - ',e.descripcion_fija,' - ', eo.descripcion, ' - ',o.abreviatura,' - ', d.descripcion) as descripcion 
        FROM organismo o 
        join organismo_dispositivo d on o.idorganismo = d.idorganismo
        join organismo_org_dec ood on ood.idorganismo = o.idorganismo
        join organismo_decreto od on od.iddecreto = ood.iddecreto
        join edificio_oficina eo on eo.idoficina = d.idoficina
        join edificio e on e.idedificio = eo.idedificio
        $filtro
        order by e.descripcion_fija, eo.descripcion, od.descripcion, o.abreviatura, d.descripcion";
        $array = OrganismoDispositivo::findBySql($sql)->all();
        return $array;
    }

    public static function get_dispositivo($id)
    {
        $sql = "SELECT d.iddispositivo, concat(o.abreviatura,' - ', d.descripcion) as descripcion 
        FROM organismo o 
        join organismo_dispositivo d on o.idorganismo = d.idorganismo
        where o.activo = 1 and d.activo = 1 and d.iddispositivo = $id
        order by o.abreviatura, d.descripcion";
        $dato = OrganismoDispositivo::findBySql($sql)->one();
        return $dato;
    }
        public static function get_dispositivo_pro($id)
    {
        $sql = "SELECT d.iddispositivo, concat(e.descripcion_fija,' - ', eo.descripcion, ' - ', o.abreviatura, ' - ', d.descripcion) as descripcion 
        FROM organismo o 
        join organismo_dispositivo d on o.idorganismo = d.idorganismo
        join edificio_oficina eo on eo.idoficina = d.idoficina
        join edificio e on e.idedificio = eo.idedificio
        where d.iddispositivo = $id";
        $dato = OrganismoDispositivo::findBySql($sql)->one();
        return $dato;
    }
}
