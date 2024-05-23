<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_dispositivo;

/**
 * Mds_org_dispositivoSearch represents the model behind the search form about `app\models\Mds_org_dispositivo`.
 */
class Mds_org_dispositivoSearch extends Mds_org_dispositivo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iddispositivo', 'idorganismo', 'idcapaitem'], 'integer'],
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
        $query = Mds_org_dispositivo::find();

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
            'iddispositivo' => $this->iddispositivo,
            'idorganismo' => $this->idorganismo,
            'idcapaitem' => $this->idcapaitem,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
