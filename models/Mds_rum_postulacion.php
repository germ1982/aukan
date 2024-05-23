<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_postulacion".
 *
 * @property int $id
 * @property int $id_persona
 * @property int $id_oferta
 * @property string $fecha_post
 * @property int $estado
 * @property string $hora_post
 */
class Mds_rum_postulacion extends \yii\db\ActiveRecord
{  
    public $documento;
    public $titulo_oferta;
    public $titulo;
    public $persona;
    public $nombre;
    public $apellido;

    public $fdesde;
    public $fhasta;
    public $id_delcv;
    public $datocompleto;
    public $nombre_oferta;
    
    /**
     * {@inheritdoc}
     */
    /* ActiveRelation */

    public static function tableName()
    {
        return 'mds_rum_postulacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_persona', 'id_oferta'], 'required'],
            [['id_persona', 'id_oferta'], 'integer'],
            [['fdesde', 'fhasta','fecha_post', 'hora_post','documento','estado','id_oferta','titulo','titulo_oferta','persona','nombre','apellido','id_persona'], 'safe'],            
                        
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'persona' => 'Persona',
            'id_oferta' => 'Oferta Laboral',
            'fecha_post' => 'Fecha Postulacion',
            'hora_post' => 'Hora Postulacion',
            'estado' => 'Estado',
            'nombre'=>'Nombre',
            'apellido'=>'Apellido',
            'titulo_oferta'=>'Oferta Laboral',
            'titulo'=>'Titulo',
            'datocompleto'=>'Persona Postulante',
        ];
    }

 

}
