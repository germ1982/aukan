<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InformaticaControlInsumosEventos;

class InformaticaControlInsumosEventosSearch extends InformaticaControlInsumosEventos
{
    // Variables virtuales para el rango de fechas
    public $fdesde;
    public $fhasta;

    public function rules()
    {
        return [
            [['identrega', 'idsolicitante', 'idsector_solicitante', 'idresponsable', 'estado'], 'integer'],
            [['fecha', 'hora', 'destino_prestamo', 'descripcion', 'observacion', 'fdesde', 'fhasta'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = InformaticaControlInsumosEventos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['identrega' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Lógica para rango de fechas
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha, '$fecha_desde_aux') >= 0";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha, '$fecha_hasta_aux') <= 0";
        }

        $query->andFilterWhere([
            'identrega' => $this->identrega,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'idsolicitante' => $this->idsolicitante,
            'idsector_solicitante' => $this->idsector_solicitante,
            'idresponsable' => $this->idresponsable,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'destino_prestamo', $this->destino_prestamo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}