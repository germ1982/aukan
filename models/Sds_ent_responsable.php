<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ent_responsable".
 *
 * @property int $idresponsable
 * @property string $mail
 * @property string $telefono
 * @property string $dni_frente
 * @property string $dni_dorso
 *
 * @property SdsComConfiguracion $idresponsable0
 */
class Sds_ent_responsable extends \yii\db\ActiveRecord
{
    public $responsable;
    public $idresponsable_reemp;
    public $deudor;
    public $archivo_dni_frente;
    public $archivo_dni_dorso;
    public $fecha_deuda;
    public $ultima_adeuda;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ent_responsable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dni','responsable'], 'required'],
            [['dni_frente', 'dni_dorso', 'ultima_adeuda'], 'string'],
            [['deudor', 'dni', 'idorganismoexterno', 'idresponsable_reemp'], 'integer'],
            [['dni_frente', 'dni_dorso','mail', 'telefono'],'safe'],
            [['archivo_dni_frente'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            [['archivo_dni_dorso'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            [['mail', 'telefono'], 'string', 'max' => 100],
            [['responsable'], 'string', 'max' => 255],
            [['idresponsable'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idresponsable' => 'idconfiguracion']],
            [['idorganismoexterno'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo_externo::className(), 'targetAttribute' => ['idorganismoexterno' => 'idorganismoexterno']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idresponsable' => 'ID',
            'mail' => 'Mail',
            'telefono' => 'Teléfono',
            'dni' => 'DNI',
            'dni_frente' => 'DNI Frente',
            'dni_dorso' => 'DNI Dorso',
            'deudor' => 'Estado',
            'idorganismoexterno' => 'Organismo Externo',
            'ultima_adeuda' => 'Última Adeudada'
        ];
    }
}
