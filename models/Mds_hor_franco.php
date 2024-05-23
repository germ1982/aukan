<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_franco".
 *
 * @property int $idfranco
 * @property int $idcontacto
 * @property string $fecha
 * @property string|null $descripcion
 *
 * @property MdsOrgContacto $idcontacto0
 */
class Mds_hor_franco extends \yii\db\ActiveRecord
{
    //Agrego idcontacto del contacto que voy a usar para clonar los francos.
    public $idcontacto_clonar;
    //Mes y anio de los francos que voy a clonar.
    public $mes_clonar;
    public $anio_clonar;
    //Para la carga por periodos
    public $desde;
    public $hasta;
    public $contactos;
    public $dias_laborales;
    public $dias_franco;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_hor_franco';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcontacto', 'fecha', 'tipo'], 'required'],
            [['idcontacto', 'idcontacto_clonar', 'mes_clonar', 'anio_clonar', 'tipo'], 'integer'],
            [['fecha', 'desde', 'hasta', 'contactos', 'dias_laborales','dias_franco'], 'safe'],
            [['idcontacto', 'fecha'], 'unique', 'targetAttribute' => ['idcontacto', 'fecha']],
            [['descripcion'], 'string', 'max' => 100],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['idcontacto' => 'idcontacto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idfranco' => 'Idfranco',
            'idcontacto' => 'Contacto',
            'fecha' => 'Fecha',
            'descripcion' => 'Descripcion',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'contactos' => 'Contactos',
            'tipo' => 'Tipo Franco'
        ];
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
}
