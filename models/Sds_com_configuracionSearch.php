<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_com_configuracion;

/**
 * Sds_com_configuracionSearch represents the model behind the search form about `app\models\Sds_com_configuracion`.
 */
class Sds_com_configuracionSearch extends Sds_com_configuracion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idconfiguracion', 'idconfiguraciontipo'], 'integer'],
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
        $query = Sds_com_configuracion::find();

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
            'idconfiguracion' => $this->idconfiguracion,
            'idconfiguraciontipo' => $this->idconfiguraciontipo,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
