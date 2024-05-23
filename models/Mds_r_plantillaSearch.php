<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_r_plantilla;

/**
 * Mds_r_plantillaSearch represents the model behind the search form about `app\models\Mds_r_plantilla`.
 */
class Mds_r_plantillaSearch extends Mds_r_plantilla
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idplantilla', 'variable_diagnostico', 'idtipoplantilla', 'dimension', 'origen'], 'integer'],
            [['fechahoracreate', 'variable_diagnostico', 'idtipoplantilla', 'dimension', 'origen'], 'safe'],
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
        $query = Mds_r_plantilla::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['variable_diagnostico', 'idtipoplantilla', 'dimension', 'origen'],
               
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [                              
                'idtipoplantilla' => [
                    'asc' => ['idtipoplantilla' => SORT_ASC],
                    'desc' => ['idtipoplantilla' => SORT_DESC],                    
                    'label' => 'Tipo plantilla'
                ],
                'variable_diagnostico' => [
                    'asc' => ['variable_diagnostico' => SORT_ASC],
                    'desc' => ['variable_diagnostico' => SORT_DESC],                    
                    'label' => 'Variable diagnostico'
                ],
                'dimension' => [
                    'asc' => ['dimension' => SORT_ASC],
                    'desc' => ['dimension' => SORT_DESC],                    
                    'label' => 'Dimension'
                ],
                'origen' => [
                    'asc' => ['origen' => SORT_ASC],
                    'desc' => ['origen' => SORT_DESC],                    
                    'label' => 'Origen'
                ]
            ]
            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andWhere("activo=1");
        $query->andFilterWhere([
            'idplantilla' => $this->idplantilla,
            'variable_diagnostico' => $this->variable_diagnostico,
            'idtipoplantilla' => $this->idtipoplantilla,
            'dimension' => $this->dimension,
            'origen' => $this->origen,
            'fechahoracreate' => $this->fechahoracreate,
        ]);

     $query
        ->andFilterWhere(['like', 'idtipoplantilla', $this->idtipoplantilla])
        ->andFilterWhere(['like', 'variable_diagnostico', $this->variable_diagnostico])
        ->andFilterWhere(['like', 'dimension', $this->dimension])
        ->andFilterWhere(['like', 'origen', $this->origen]);
     return $dataProvider;
    }
}
