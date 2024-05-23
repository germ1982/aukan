<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_interno".
 *
 * @property int $idinterno
 * @property int $idcapaitem
 * @property int $iddispositivo
 * @property int|null $idcontacto
 * @property int $recepcion
 * @property int $grupo
 * @property string $responsable
 *
 * @property Sds_gis_capa_item $idcapaitem0
 * @property Mds_org_contacto $idcontacto0
 * @property Mds_org_dispositivo $iddispositivo0
 */
class Sds_reg_interno extends \yii\db\ActiveRecord
{
    public $organismo;
    public $edificio;
    public $dispositivo;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_interno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idinterno', 'idcapaitem', 'iddispositivo', 'grupo', 'responsable', 'organismo'], 'required'],
            [['idinterno', 'idcapaitem', 'iddispositivo', 'idcontacto', 'recepcion', 'grupo'], 'integer'],
            [['edificio', 'dispositivo'], 'safe'],
            [['responsable'], 'string', 'max' => 100],
            [['idinterno'], 'unique'],
            [['idcapaitem'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa_item::class, 'targetAttribute' => ['idcapaitem' => 'idcapaitem']],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['idcontacto' => 'idcontacto']],
            [['iddispositivo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_dispositivo::class, 'targetAttribute' => ['iddispositivo' => 'iddispositivo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idinterno' => 'Interno',
            'idcapaitem' => 'idcapaitem',
            'iddispositivo' => 'Dispositivo',
            'idcontacto' => 'Contacto',
            'recepcion' => 'Recepcion',
            'grupo' => 'Grupo',
            'responsable' => 'Nombre/Referente',
            'organismo' => 'Organismo',
            'edificio' => 'Edificio',
            'dispositivo' => 'Dispositivo'
        ];
    }

    /**
     * Gets query for [[Idcapaitem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcapaitem0()
    {
        return $this->hasOne(Sds_gis_capa_item::class, ['idcapaitem' => 'idcapaitem']);
    }

    /**
     * Gets query for [[Idcontacto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcontacto0()
    {
        return $this->hasOne(MdsOrgContacto::class, ['idcontacto' => 'idcontacto']);
    }

    /**
     * Gets query for [[Iddispositivo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddispositivo0()
    {
        return $this->hasOne(MdsOrgDispositivo::class, ['iddispositivo' => 'iddispositivo']);
    }
}
