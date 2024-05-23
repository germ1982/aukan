<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_inv_persona;

/**
 * Mds_inv_personaSearch represents the model behind the search form about `app\models\Mds_inv_persona`.
 */
class Mds_inv_personaSearch extends Mds_inv_persona
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           
            [['domicilio','dni','persona'], 'safe'],
            [['idpersona' ], 'integer'],
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
        $query = Mds_inv_persona::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                              
            ]
        ]);

        $this->load($params);
        $query->addSelect([
            'mds_inv_persona.*', 'sds_com_persona.documento as dni', 'concat(sds_com_persona.apellido,\', \',sds_com_persona.nombre) persona',
            //'COUNT(mds_inv_entrega.identrega) as cantentregas'
        ]);
        $query->from('mds_inv_persona');
        $query->join('join', 'sds_com_persona', 'mds_inv_persona.idpersona = sds_com_persona.idpersona');        
        //$query->join('join', 'mds_inv_entrega', 'mds_inv_persona.idpersona = mds_inv_entrega.idpersona');  
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idpersona' => $this->idpersona,
            'grupo_familiar' => $this->grupo_familiar,            
        ]);

        $query->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'domicilio', $this->domicilio]);
            $query->orderBy(['idpersona' => SORT_DESC])            
            //->groupBy(['mds_inv_entrega.idpersona'])
            ;
        $query
            ->having("dni like '%" . $this->dni . "%' and LOWER(persona) like '%" . strtolower($this->persona) . "%'");
            
        return $dataProvider;
    }
}
