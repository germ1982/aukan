<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_cel_factura;

/**
 * Sds_cel_facturaSearch represents the model behind the search form about `app\models\Sds_cel_factura`.
 */
class Sds_cel_facturaSearch extends Sds_cel_factura
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idfactura', 'periodo_mes', 'periodo_anio', 'cuenta'], 'integer'],
            [['fecha_carga', 'observaciones', 'fdesde', 'fhasta'], 'safe'],
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
        $query = Sds_cel_factura::find();

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
            $fecha_desde_aux = date_format(date_create(str_replace('/','-',$this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha_carga,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/','-',$this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_carga,'$fecha_hasta_aux')<=0 ";
        }

        $query->andFilterWhere([
            'idfactura' => $this->idfactura,
            'periodo_mes' => $this->periodo_mes,
            'periodo_anio' => $this->periodo_anio,
            'fecha_carga' => $this->fecha_carga,
            'cuenta' => $this->cuenta,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones])
        ->andWhere($sql_desde)
        ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
