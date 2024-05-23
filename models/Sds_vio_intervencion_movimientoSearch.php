<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Sds_vio_intervencion_movimientoSearch represents the model behind the search form about `app\models\Sds_vio_intervencion_movimiento`.
 */
class Sds_vio_intervencion_movimientoSearch extends Sds_vio_intervencion_movimiento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idintervencion', 'idusuario', 'tipo_movimiento'], 'integer'],
            [['detalle', 'profesionales_intervinientes',], 'string'],
            [['fecha', 'fecha_alta'], 'safe'],
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
        $query = Sds_vio_intervencion_movimiento::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha', 'deleted_at'],
                'defaultOrder' => ['fecha' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if(! $hasRolAdminGeneral){
            $query->andWhere(['sds_vio_intervencion_movimiento.deleted_at' => null]);
        }

        $query->andFilterWhere([
            'idintervencion' => $this->idintervencion,
            'tipo_movimiento' => $this->tipo_movimiento,
            'idusuario' => $this->idusuario,
            'fecha' => $this->fecha,
        ]);
        return $dataProvider;
    }
}
