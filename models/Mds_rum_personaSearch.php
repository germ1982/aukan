<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_rum_persona;

/**
 * Mds_rum_personaSearch represents the model behind the search form about `app\models\Mds_rum_persona`.
 */
class Mds_rum_personaSearch extends Mds_rum_persona
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hijos', 'tienecuil', 'precuil', 'postcuil', 'iddomicilio',  'idestadocivil', 'iddocadicional', 'id_com_persona'], 'integer'],
            [['id','email',  'idestado', 'fechamodificacion', 'horamodificacion', 'foto',  'Labels', 'Trabajos', 'EstSup', 'ingreso', 'estado'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Mds_rum_persona::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['id','fechaalta','persona','documento','edad'],
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);
        $dataProvider->setSort([
            'attributes' => [
               
               
                'persona' => [
                    'asc' => ['sds_com_persona.nombre' => SORT_ASC,'sds_com_persona.apellido' => SORT_ASC],
                    'desc' => ['sds_com_persona.nombre' => SORT_DESC,'sds_com_persona.apellido' => SORT_DESC],                    
                    'label' => 'Persona'
                ],
                'documento' => [
                    'asc' => ['sds_com_persona.documento' => SORT_ASC],
                    'desc' => ['sds_com_persona.documento' => SORT_DESC],                    
                    'label' => 'DNI',
                   
                ],
                'edad' => [
                    'asc' => ['sds_com_persona.fecha_nacimiento' => SORT_ASC],
                    'desc' => ['sds_com_persona.fecha_nacimiento' => SORT_DESC],                    
                    'label' => 'Edad'

                ],
                'fechaalta' => [
                    'asc' => ['fechaalta' => SORT_ASC],
                    'desc' => ['fechaalta' => SORT_DESC],
                    'label' => 'Fecha Alta',                    
                ],
                'id' => [
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],                                        
                ],
            ],'defaultOrder' => ['id' => SORT_DESC]
            
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
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fechaalta >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fechaalta <= '$fecha_hasta_aux'";
        }
        $query 
        ->innerJoin('sds_com_persona', 'mds_rum_persona.id_com_persona=sds_com_persona.idpersona')            
        ->all();
        $query->andFilterWhere([
            'id' => $this->id,
 
            'hijos' => $this->hijos,
            'tienecuil' => $this->tienecuil,
            'precuil' => $this->precuil,
            'postcuil' => $this->postcuil,
            'iddomicilio' => $this->iddomicilio,
            'sds_com_persona.idpersona' => $this->persona,
            'sds_com_persona.idpersona' => $this->documento,
            
            'iddocadicional' => $this->iddocadicional,
            
            'fechamodificacion' => $this->fechamodificacion,
            'horamodificacion' => $this->horamodificacion,
                                    
            'id_com_persona' => $this->id_com_persona,
        ]);

        $query
            
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telfijo', $this->telfijo])
            ->andFilterWhere(['like', 'telcel', $this->telcel])
            ->andFilterWhere(['like', 'idestado', $this->idestado])
            
            ->andFilterWhere(['like', 'foto', $this->foto])
            
            ->andFilterWhere(['like', 'Labels', $this->Labels])
           
            ->andFilterWhere(['like', 'Trabajos', $this->Trabajos])
            
           
            ->andFilterWhere(['like', 'ingreso', $this->ingreso])
            ->andFilterWhere(['like', 'estado', $this->estado]);
            $query 
        
        ->andWhere($sql_desde)
        ->andWhere($sql_hasta)              
        ;

        return $dataProvider;
    }
}
