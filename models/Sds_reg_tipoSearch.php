<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_reg_tipo;

/**
 * Sds_reg_tipoSearch represents the model behind the search form about `app\models\Sds_reg_tipo`.
 */
class Sds_reg_tipoSearch extends Sds_reg_tipo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idtipo'], 'integer'],
            [['descripcion', 'activo'], 'safe'],
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
        $query = Sds_reg_tipo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //$query->innerJoin('sds_reg_tipo', 'sds_reg_tipo.idtipo=sds_reg_tipo.idtipo');

        $query->andFilterWhere([
            'idtipo' => $this->idtipo,
            'entidad' => $this->entidad
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
