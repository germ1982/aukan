<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_veh_vehiculo;

/**
 * Sds_veh_vehiculoSearch represents the model behind the search form about `app\models\Sds_veh_vehiculo`.
 */
class Sds_veh_vehiculoSearch extends Sds_veh_vehiculo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idvehiculo', 'estado', 'modelo', 'tipo', 'anio', 'idorganismo'], 'integer'],
            [['dominio', 'alquilado', 'detalle','marca','estado_descripcion','tipo_descripcion','modelo_descripcion'], 'safe'],
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
        $query = Sds_veh_vehiculo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['dominio', 'alquilado', 'detalle','marca','estado_descripcion','tipo_descripcion','modelo_descripcion','anio'],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->addSelect('(SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=estado) estado_descripcion');
        $query->addSelect('(SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=tipo) tipo_descripcion');
        $query->addSelect('vehiculo.*');
        $query->addSelect('(SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=vm.idmarca) marca');
        $query->addSelect('(SELECT descripcion FROM sds_veh_modelo WHERE idmodelo=vehiculo.modelo) modelo_descripcion');
        $query->from('sds_veh_vehiculo vehiculo');
        $query->join('inner join', 'sds_veh_modelo vm', 'modelo=vm.idmodelo');

        $query->andFilterWhere([
            'idvehiculo' => $this->idvehiculo,
            'estado' => $this->estado_descripcion,
            'modelo' => $this->modelo_descripcion,
            'tipo' => $this->tipo_descripcion,
            'anio' => $this->anio,
            'idorganismo' => $this->idorganismo,
            'vm.idmarca' => $this->marca,
        ]);

        $query->andFilterWhere(['like', 'dominio', $this->dominio])
            ->andFilterWhere(['like', 'alquilado', $this->alquilado])
            ->andFilterWhere(['like', 'detalle', $this->detalle]);

        return $dataProvider;
    }
}
