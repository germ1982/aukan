<?php

namespace app\models;

use Yii;

class Organismo extends \yii\db\ActiveRecord
{

    public $iddecreto; // Atributo temporal para manejar el ID del decreto en el formulario
    public $origen_alta; // Atributo temporal para saber si venimos del árbol o no
    /* 
    $origen_alta:
    0 =  desde grilla,
    1 =  desde boton iniciar arbol
    2 = desde boton agregar rama sobre un nodo del arbol
    */

    public static function tableName()
    {
        return 'organismo';
    }


    public function rules()
    {
        return [
            [['descripcion', 'nivel', 'abreviatura'], 'required'],
            [['padre', 'nivel', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 200],
            [['abreviatura'], 'string', 'max' => 100],
            [['padre'], 'exist', 'skipOnError' => true, 'targetClass' => Organismo::className(), 'targetAttribute' => ['padre' => 'idorganismo']],
            [['iddecreto', 'origen_alta'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idorganismo' => 'Id',
            'descripcion' => 'Descripcion',
            'padre' => 'Padre',
            'nivel' => 'Nivel',
            'activo' => 'Activo',
            'abreviatura' => 'Abreviatura',
            'iddecreto' => 'Iddecreto',
            'origen_alta' => 'Origen Alta',
        ];
    }

    /**
     * Gets query for [[Padre0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPadre0()
    {
        return $this->hasOne(Organismo::className(), ['idorganismo' => 'padre']);
    }

    /**
     * Gets query for [[Organismos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismos()
    {
        return $this->hasMany(Organismo::className(), ['padre' => 'idorganismo']);
    }

    /**
     * Gets query for [[OrganismoDispositivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismoDispositivos()
    {
        return $this->hasMany(OrganismoDispositivo::className(), ['idorganismo' => 'idorganismo']);
    }
    public static function get_organismos()
    {

        $sql = "SELECT o.idorganismo, o.descripcion
        FROM organismo o 
        
        where o.activo = 1 
        order by o.descripcion";
        $array = Organismo::findBySql($sql)->all();
        return $array;
    }
    public static function get_organismo($id)
    {

        $sql = "SELECT o.idorganismo, o.descripcion
        FROM organismo o 
        
        where o.activo = 1 and o.idorganismo=$id
        order by o.descripcion";
        $dato = Organismo::findBySql($sql)->one();
        return $dato;
    }
}
