<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Persona;

/**
 * PersonaSearch represents the model behind the search form of `app\models\Persona`.
 */
class PersonaSearch extends Persona
{
    public $nombre_apellido; // Atributo virtual
    public $direccion_completa;
    public function rules()
    {
        return [
            [['idpersona', 'documento', 'documento_tipo', 'nacionalidad', 'genero', 'padre', 'conviviente', 'idlocalidad'], 'integer'],
            [['fecha_nacimiento', 'nombre', 'apellido', 'domicilio', 'domicilio_calle', 'domicilio_numero','nombre_apellido', 'fdesde', 'fhasta','direccion_completa'], 'safe'],
        ];
    }
 
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Persona::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'nombre_apellido' => SORT_ASC,
                    //'nombre' => SORT_ASC,  // Orden predeterminado por apellido y nombre
                ],
                'attributes' => [
                  'idpersona',
                  'documento',
                    'nombre_apellido' => [
                        'asc' => ['personas.apellido' => SORT_ASC, 'personas.nombre' => SORT_ASC],
                        'desc' => ['personas.apellido' => SORT_DESC, 'personas.nombre' => SORT_DESC],
                    ],
                    'fecha_nacimiento',
                    'nacionalidad',
                    'genero',
                    'activo',
                    'direccion_completa', // Otras columnas para ordenar
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha_nacimiento,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_nacimiento,'$fecha_hasta_aux')<=0 ";
        }

        $subsql = "select descripcion from configuracion where id_configuracion = personas.documento_tipo";

        $query->addSelect([
            'personas.*', // Selecciona todos los campos de la tabla persona
            "CONCAT(personas.apellido, ' ', personas.nombre) AS nombre_apellido",
            "CONCAT_WS(' ', 
                COALESCE(v.provincia, ''), 
                COALESCE(l.localidad, ''), 
                COALESCE(personas.domicilio_calle, ''), 
                COALESCE(personas.domicilio_numero, ''), 
                COALESCE(personas.domicilio, '')
            ) AS direccion_completa"
        ]);
        
        $query->leftJoin('localidades l', 'personas.idlocalidad = l.id')
            ->leftJoin('provincias v', 'l.id_provincia = v.id');
        

        // grid filtering conditions
        $query->andFilterWhere([
            'idpersona' => $this->idpersona,
            'documento' => $this->documento,
            'documento_tipo' => $this->documento_tipo,
            'nacionalidad' => $this->nacionalidad,
            'genero' => $this->genero,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'padre' => $this->padre,
            'conviviente' => $this->conviviente,
            'idlocalidad' => $this->idlocalidad,
        ]);

        $query->andFilterWhere(['like', 'domicilio', $this->domicilio])
            ->andFilterWhere(['like', 'domicilio_calle', $this->domicilio_calle])
            ->andFilterWhere(['like', 'domicilio_numero', $this->domicilio_numero])
            ->andFilterWhere(['like', "CONCAT(personas.apellido, ' ', personas.nombre)", $this->nombre_apellido])
            ->andFilterWhere(['like', "CONCAT_WS(' ', 
                                                    COALESCE(v.provincia, ''), 
                                                    COALESCE(l.localidad, ''), 
                                                    COALESCE(personas.domicilio_calle, ''), 
                                                    COALESCE(personas.domicilio_numero, ''), 
                                                    COALESCE(personas.domicilio, '')
                                                )", $this->direccion_completa])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);
        return $dataProvider;
    }
}
