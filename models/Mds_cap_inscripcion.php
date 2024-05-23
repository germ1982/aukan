<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_cap_inscripcion".
 *
 * @property int $idinscripcion
 * @property int $idpersonacap
 * @property int $idcapinstancia
 * @property int $termino
 * @property string|null $fecha_inscripcion
 *
 * @property MdsCapInstancia $idcapinstancia0
 * @property MdsCapPersona $idpersonacap0
 */
class Mds_cap_inscripcion extends \yii\db\ActiveRecord
{
   
    public $titulo_dato_adicional;
    public $dni;
    public $idlocalidad;
    public $persona;
    public $la_persona;
    public $titulo_curso;
    public $mail;
    public $telefono;
    public $fecha_desde;
    public $fecha_hasta;
    public $nombre_apell;
    public $el_dni;
    public $dni_search;
    public $dni_aux;
    //Creadas para mostrar capacitaciones en Home:
    public $capacitacion;
    public $idcapacitacion;
    public $instancia;
    
    
    const ESTADO_INSCRIPTO = 0;
    const ESTADO_ENCURSO = 1;
    const ESTADO_APROBADO = 2;
    const ESTADO_DESAPROBADO = 3;
    const ESTADO_ABANDONADO = 4;
    const ESTADO_ENESPERA=5;
    const ESTADO_PARTICIPO=6;
    const ESTADO_NO_CORRESPONDE=7;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cap_inscripcion';
    }

    /**
     * {@inheritdoc}
     */
    

    public function rules()
    {
        return [
            [[ 'idcapinstancia', 'fecha_inscripcion', 'termino'], 'required'],
            [['estado_cert','idpersonacap', 'idcapinstancia', 'termino'], 'integer'],
            [['la_persona','fecha_inscripcion', 'titulo_dato_adicional', 'persona','dni','idlocalidad', 'mail', 'telefono','fecha_desde', 'fecha_hasta', 'capacitacion', 'idcapacitacion', 'instancia'], 'safe'],
            [['codigo_qr','path_cert','titulo_curso','dato_adicional'], 'string'],
            [['idpersonacap'], 'unique', 'targetAttribute' => ['idpersonacap', 'idcapinstancia'],'message'=>'La persona seleccionada ya está inscripta en la instancia.'],
            [['idcapinstancia'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_cap_instancia::className(), 'targetAttribute' => ['idcapinstancia' => 'idinstancia']],
            [['idpersonacap'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_cap_persona::className(), 'targetAttribute' => ['idpersonacap' => 'idpersonacap']],                    
        ];
    }
   
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idinscripcion' => 'Nro.',
            'idpersonacap' => 'Persona',
            'idcapinstancia' => 'Instancia',
            'termino' => 'Estado',
            'fecha_inscripcion' => 'Fecha Inscripción',             
            
        ];
    }

    /**
     * Gets query for [[Idcapinstancia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcapinstancia0()
    {
        return $this->hasOne(Mds_cap_instancia::className(), ['idinstancia' => 'idcapinstancia']);
    }

    /**
     * Gets query for [[Idpersonacap0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersonacap0()
    {
        return $this->hasOne(Mds_cap_persona::className(), ['idpersonacap' => 'idpersonacap']);
    }
}
