<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_bdc_movimiento;

/**
 * Sds_bdc_movimientoSearch represents the model behind the search form about `app\models\Sds_bdc_movimiento`.
 */
class Sds_bdc_movimientoSearch extends Sds_bdc_movimiento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmovimiento', 'idusuario', 'solicitante', 'tipo', 'responsable_anterior', 'responsable_nuevo', 'usuario_anterior', 'usuario_nuevo', 'ip_anterior', 'ip_nueva'], 'integer'],
            [['fecha_hora', 'observaciones', 'fdesde', 'fhasta'], 'safe'],
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
        $query = Sds_bdc_movimiento::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['idmovimiento', 'idusuario', 'solicitante', 'tipo', 'responsable_anterior', 'responsable_nuevo', 'usuario_anterior', 'usuario_nuevo', 'ip_anterior', 'ip_nueva', 'fecha_hora', 'observaciones'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
            ]
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
            $sql_desde = "DATEDIFF(fecha_hora,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_hora,'$fecha_hasta_aux')<=0 ";
        }

        $query->andFilterWhere([
            'idmovimiento' => $this->idmovimiento,
            'fecha_hora' => $this->fecha_hora,
            'idusuario' => $this->idusuario,
            'solicitante' => $this->solicitante,
            'tipo' => $this->tipo,
            'responsable_anterior' => $this->responsable_anterior,
            'responsable_nuevo' => $this->responsable_nuevo,
            'usuario_anterior' => $this->usuario_anterior,
            'usuario_nuevo' => $this->usuario_nuevo,
            'ip_anterior' => $this->ip_anterior,
            'ip_nueva' => $this->ip_nueva,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones])
        ->andWhere($sql_desde)
        ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
