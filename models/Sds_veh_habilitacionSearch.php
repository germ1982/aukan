<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_veh_habilitacion;

/**
 * Mds_veh_vehiculoSearch represents the model behind the search form about `app\models\Sds_veh_habilitacion`.
 */
class Sds_veh_habilitacionSearch extends Sds_veh_habilitacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idhabilitacion', 'tipo', 'idvehiculo'], 'integer'],
            [['detalle', 'vencimiento', 'adjunto', 'tipo_descripcion'], 'safe'],
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
        $query = Sds_veh_habilitacion::find();

        $dataProvider = new ActiveDataProvider([
            'sort' => [
                'attributes' => ['tipo_descripcion', 'detalle', 'vencimiento'],
                'defaultOrder' => ['vencimiento' => SORT_ASC]
            ],
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->select('h.*, c.descripcion AS tipo_descripcion')
            ->from('sds_veh_habilitacion h')
            ->join('left join', 'sds_com_configuracion c', 'h.tipo=c.idconfiguracion');

        $query->andFilterWhere([
            'idhabilitacion' => $this->idhabilitacion,
            'vencimiento' => $this->vencimiento,
            'tipo' => $this->tipo_descripcion,
            'idvehiculo' => $this->idvehiculo,
        ]);

        $query->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andFilterWhere(['like', 'adjunto', $this->adjunto]);

        return $dataProvider;
    }
}
