<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_bdc_equipo;

/**
 * Sds_bdc_equipoSearch represents the model behind the search form about `app\models\Sds_bdc_equipo`.
 */
class Sds_bdc_equipoSearch extends Sds_bdc_equipo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idequipo', 'tipo', 'marca', 'responsable', 'usuario', 'procesador', 'memoria', 'disco', 'sistema_operativo', 'conectividad', 'idorganismo'], 'integer'],
            [['modelo', 'matricula', 'ip', 'observaciones', 'estado'], 'safe'],
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
        $query = Sds_bdc_equipo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination->pageSize = 30;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->addSelect(['*', 'IFNULL((SELECT tipo FROM (
            SELECT mov.idequipo, (SELECT tipo FROM sds_bdc_movimiento WHERE idmovimiento=max(mov.idmovimiento)) tipo FROM
                (SELECT me.idmovimiento, me.idequipo, m.fecha_hora FROM sds_bdc_movimiento m
                JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
            GROUP BY mov.idequipo ) eq WHERE idequipo=sds_bdc_equipo.idequipo), 2435) as estado']);

        $query->andFilterWhere([
            'idequipo' => $this->idequipo,
            'tipo' => $this->tipo,
            'marca' => $this->marca,
            'responsable' => $this->responsable,
            'usuario' => $this->usuario,
            'procesador' => $this->procesador,
            'memoria' => $this->memoria,
            'disco' => $this->disco,
            'sistema_operativo' => $this->sistema_operativo,
            'conectividad' => $this->conectividad,
            'idorganismo' => $this->idorganismo,
        ]);

        $query->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'matricula', $this->matricula])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);
        
        if($this->estado!=null){
            $query->having('estado = '.$this->estado);
        }

        return $dataProvider;
    }
}
