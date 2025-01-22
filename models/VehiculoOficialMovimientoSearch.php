<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VehiculoOficialMovimiento;

/**
 * VehiculoOficialMovimientoSearch represents the model behind the search form about `app\models\VehiculoOficialMovimiento`.
 */
class VehiculoOficialMovimientoSearch extends VehiculoOficialMovimiento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmovimiento', 'idvehiculo', 'chofer', 'kilometraje'], 'integer'],
            [['lugar_salida', 'lugar_destino', 'finalidad_viaje', 'fecha', 'hora', 'fdesde', 'fhasta'], 'safe'],//se agregan estas dos variables para que funcione el filtro por fechas
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
        $query = VehiculoOficialMovimiento::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //se agrega este codigo para que filtre bien en la consulta las fechas desde y hasta
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha,'$fecha_hasta_aux')<=0 ";
        }
        //----------------------------------------------------------------------------------

        $query->andFilterWhere([
            'idmovimiento' => $this->idmovimiento,
            'idvehiculo' => $this->idvehiculo,
            'chofer' => $this->chofer,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'kilometraje' => $this->kilometraje,
        ]);

        $query->andFilterWhere(['like', 'lugar_salida', $this->lugar_salida])
            ->andFilterWhere(['like', 'lugar_destino', $this->lugar_destino])
            ->andFilterWhere(['like', 'finalidad_viaje', $this->finalidad_viaje])
            ->andWhere($sql_desde)//se agrega para el filtrado por fechas
            ->andWhere($sql_hasta)//se agrega para el filtrado por fechas
            ;

        return $dataProvider;
    }
}
