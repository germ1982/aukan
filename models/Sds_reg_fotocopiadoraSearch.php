<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_reg_fotocopiadora;

/**
 * Sds_reg_fotocopiadoraSearch represents the model behind the search form about `app\models\Sds_reg_fotocopiadora`.
 */
class Sds_reg_fotocopiadoraSearch extends Sds_reg_fotocopiadora
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idfotocopiadora', 'idproveedor', 'idorganismo', 'idequipo', 'copias'], 'integer'],
            [['expediente_fisico', 'expediente_gde', 'safipro', 'lugar', 'vencimiento', 'observaciones'], 'safe'],
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
        $query = Sds_reg_fotocopiadora::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idfotocopiadora' => $this->idfotocopiadora,
            'idproveedor' => $this->idproveedor,
            'idorganismo' => $this->idorganismo,
            'idequipo' => $this->idequipo,
            'vencimiento' => $this->vencimiento,
            'copias' => $this->copias,
        ]);

        $query->andFilterWhere(['like', 'expediente_fisico', $this->expediente_fisico])
            ->andFilterWhere(['like', 'expediente_gde', $this->expediente_gde])
            ->andFilterWhere(['like', 'safipro', $this->safipro])
            ->andFilterWhere(['like', 'lugar', $this->lugar])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
