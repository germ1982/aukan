<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_certificacion_direccion_usuario;

/**
 * Mds_certificacion_direccion_usuarioSearch represents the model behind the search form of `app\models\Mds_certificacion_direccion_usuario`.
 */
class Mds_certificacion_direccion_usuarioSearch extends Mds_certificacion_direccion_usuario
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iddireccionusuario', 'idcertificaciondireccion', 'idusuario'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Mds_certificacion_direccion_usuario::find()
            ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario = mds_certificacion_direccion_usuario.idusuario')
            ->where(['mds_certificacion_direccion_usuario.deleted_at' => null, 'mds_seg_usuario.activo' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'iddireccionusuario',
                    'idcertificaciondireccion',
                    'idusuario',
                    'deleted_at'
                ],
                'defaultOrder' => ['iddireccionusuario' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'iddireccionusuario' => $this->iddireccionusuario,
            'idcertificaciondireccion' => $this->idcertificaciondireccion,
            'mds_certificacion_direccion_usuario.idusuario' => $this->idusuario,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['deleted_at' => null]]);
        } else {
            $query->andWhere(['deleted_at' => null]);
        }

        return $dataProvider;
    }
}

function armarDateParaMySql($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 6, 4);
    $mes  = substr($fecha, 3, 2);
    $dia = substr($fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
