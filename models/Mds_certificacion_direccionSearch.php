<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_certificacion_direccion;

/**
 * Mds_certificacion_direccionSearch represents the model behind the search form of `app\models\Mds_certificacion_direccion`.
 */
class Mds_certificacion_direccionSearch extends Mds_certificacion_direccion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificaciondireccion', 'iddireccion', 'idnivelautorizacion', 'idusuario_carga', 'idusuario_borra', 'iddireccion_padre'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'usuario', 'fecha_desde', 'fecha_hasta'], 'safe'],
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
        $dateToday = date('Y-m-d H:i:s');

        $condition =
            "mds_certificacion_direccion.idcertificaciondireccion = mds_certificacion_director.idcertificaciondireccion 
            AND mds_certificacion_director.deleted_at IS NULL
            AND (
                    (
                    mds_certificacion_director.fecha_desde <= '$dateToday'
                    AND mds_certificacion_director.fecha_hasta >= '$dateToday'
                    )
                OR 
                (
                    mds_certificacion_director.fecha_desde <= '$dateToday'
                    AND mds_certificacion_director.fecha_hasta IS NULL
                )
            )
            ";

        $query = Mds_certificacion_direccion::find()
            ->select(['mds_certificacion_direccion.*', 'mds_certificacion_director.idusuario as usuario', 'mds_certificacion_director.fecha_desde', 'mds_certificacion_director.fecha_hasta'])
            ->leftJoin('mds_certificacion_director', $condition)
            ->leftJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario=mds_certificacion_director.idusuario');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idcertificaciondireccion',
                    'iddireccion',
                    'iddireccion_padre',
                    'idnivelautorizacion',
                    'usuario',
                    'fecha_desde',
                    'fecha_hasta',
                    'deleted_at',
                ],
                'defaultOrder' => ['idcertificaciondireccion' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (isset($params['Mds_certificacion_direccionSearch']) && $params['Mds_certificacion_direccionSearch']['fecha_desde']) {
            $fecha_desde = $params['Mds_certificacion_direccionSearch']['fecha_desde'];
            $fecha_desde = armarDateParaMySql($fecha_desde);
            $fecha_desde = date_create($fecha_desde);
            $fecha_desde = date_format($fecha_desde, 'Y-m-d');
            $this->fecha_desde = $fecha_desde;
        }

        if (isset($params['Mds_certificacion_direccionSearch']) && $params['Mds_certificacion_direccionSearch']['fecha_hasta']) {
            $fecha_hasta = $params['Mds_certificacion_direccionSearch']['fecha_hasta'];
            $fecha_hasta = armarDateParaMySql($fecha_hasta);
            $fecha_hasta = date_create($fecha_hasta);
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');
            $this->fecha_hasta = $fecha_hasta;
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mds_certificacion_direccion.idcertificaciondireccion' => $this->idcertificaciondireccion,
            'mds_certificacion_direccion.iddireccion' => $this->iddireccion,
            'iddireccion_padre' => $this->iddireccion_padre,
            'idusuario_carga' => $this->idusuario_carga,
            'idusuario_borra' => $this->idusuario_borra,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'idnivelautorizacion' => $this->idnivelautorizacion
        ])
            ->andFilterWhere(['>=', 'fecha_desde', $this->fecha_desde])
            ->andFilterWhere(['<=', 'fecha_hasta', $this->fecha_hasta]);

        if ($this->usuario) {
            $query->andWhere([
                'or',
                ['like', 'mds_seg_usuario.nombre', $this->usuario],
                ['like', 'mds_seg_usuario.apellido', $this->usuario]
            ]);
        };

        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['mds_certificacion_direccion.deleted_at' => null]]);
        } else {
            $query->andWhere(['mds_certificacion_direccion.deleted_at' => null]);
        }

        $this->fecha_desde ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_desde))) :  null;
        if (isset($params['Mds_certificacion_direccionSearch']) && $params['Mds_certificacion_direccionSearch']['fecha_desde']) {
            $this->fecha_desde = $params['Mds_certificacion_direccionSearch']['fecha_desde'];
        }

        $this->fecha_hasta ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_hasta))) :  null;
        if (isset($params['Mds_certificacion_direccionSearch']) && $params['Mds_certificacion_direccionSearch']['fecha_hasta']) {
            $this->fecha_desde = $params['Mds_certificacion_direccionSearch']['fecha_hasta'];
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
