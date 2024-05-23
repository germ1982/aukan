<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_bdc_movimiento_equipo;

/**
 * Sds_bdc_movimiento_equipoSearch represents the model behind the search form about `app\models\Sds_bdc_movimiento_equipo`.
 */
class Sds_bdc_movimiento_equipoSearch extends Sds_bdc_movimiento_equipo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmovimientoequipo', 'idmovimiento', 'idequipo'], 'integer'],
            [['fdesde', 'fhasta', 'fecha_hora'], 'safe'],
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
        $query = Sds_bdc_movimiento_equipo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha_hora']
            ]
        ]);

        $dataProvider->pagination->pageSize = 30;

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
            $sql_desde = "DATEDIFF(fecha_hora,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_hora,'$fecha_hasta_aux')<=0 ";
        }

        $query->select('me.*, m.fecha_hora')
        ->from('sds_bdc_movimiento_equipo me')
        ->innerJoin('sds_bdc_movimiento m', 'm.idmovimiento=me.idmovimiento');

        $query->andFilterWhere([
            'idmovimientoequipo' => $this->idmovimientoequipo,
            'idmovimiento' => $this->idmovimiento,
            'idequipo' => $this->idequipo,
            'fecha_hora' => $this->fecha_hora
        ])
        ->andWhere($sql_desde)
        ->andWhere($sql_hasta)
        ->orderBy(['fecha_hora' => SORT_DESC]);

        return $dataProvider;
    }
}
