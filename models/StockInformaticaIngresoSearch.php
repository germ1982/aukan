<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StockInformaticaIngreso;

/**
 * StockInformaticaIngresoSearch represents the model behind the search form about `app\models\StockInformaticaIngreso`.
 */
class StockInformaticaIngresoSearch extends StockInformaticaIngreso
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idingreso', 'idorigen',  'idusuario_carga'], 'integer'],
            [['fecha', 'origen_referencia', 'observacion', 'fdesde', 'fhasta','idempleado_recepcion'], 'safe'],
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
        $query = StockInformaticaIngreso::find();

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
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha,'$fecha_hasta_aux')<=0 ";
        }

        $query->leftJoin('empleado e', 'stock_informatica_ingreso.idempleado_recepcion = e.idempleado');
        $query->leftJoin('personas p', 'e.idpersona = p.idpersona');

        $query->andFilterWhere([
            'idingreso' => $this->idingreso,
            'fecha' => $this->fecha,
            'idorigen' => $this->idorigen,
            //'idempleado_recepcion' => $this->idempleado_recepcion,
            'idusuario_carga' => $this->idusuario_carga,
        ]);

        $query->andFilterWhere(['like', 'origen_referencia', $this->origen_referencia])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta)
            ->andFilterWhere(['like', 'p.nombre', $this->idempleado_recepcion])
            ->orFilterWhere(['like', 'p.apellido', $this->idempleado_recepcion]);

        return $dataProvider;
    }
}
