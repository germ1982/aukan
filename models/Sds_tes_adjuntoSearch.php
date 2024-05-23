<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_tes_adjunto;

/**
 * Sds_tes_adjuntoSearch represents the model behind the search form about `app\models\Sds_tes_adjunto`.
 */
class Sds_tes_adjuntoSearch extends Sds_tes_adjunto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idadjunto', 'periodo_mes', 'periodo_anio'], 'integer'],
            [['carga', 'tipo', 'pago', 'fdesde', 'fhasta'], 'safe'],
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
        $query = Sds_tes_adjunto::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            $sql_desde = "carga >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "carga <= '$fecha_hasta_aux'";
        }

        $query->andFilterWhere([
            'idadjunto' => $this->idadjunto,
            'carga' => $this->carga,
            'periodo_mes' => $this->periodo_mes,
            'periodo_anio' => $this->periodo_anio,
        ]);

        $query->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'pago', $this->pago])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
