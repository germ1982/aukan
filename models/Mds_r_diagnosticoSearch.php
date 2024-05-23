<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_r_diagnostico;

/**
 * Mds_r_diagnosticoSearch represents the model behind the search form about `app\models\Mds_r_diagnostico`.
 */
class Mds_r_diagnosticoSearch extends Mds_r_diagnostico
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iddiagnostico', 'valor', 'idvardimension', 'iddispositivo', 'idejido'], 'integer'],            
            [['fecha'], 'safe'],
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
        $query = Mds_r_diagnostico::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);        
        if (!$this->validate()) { //print_r("NOVALIDA");print_r($this->errors);
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andWhere("activo=1");
        $query->andFilterWhere([
            'iddiagnostico' => $this->iddiagnostico,
            'valor' => $this->valor,
            'idvardimension' => $this->idvardimension,
            'fecha' => $this->fecha,
            'iddispositivo' => $this->iddispositivo,
            'idejido' => $this->idejido,
            'valor_dimension' => $this->valor_dimension,
        ]);               
        return $dataProvider;
    }
}
