<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_certificacion_estado;

/**
 * Mds_certificacion_estadoSearch represents the model behind the search form of `app\models\Mds_certificacion_estado`.
 */
class Mds_certificacion_estadoSearch extends Mds_certificacion_estado
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificacionestado'], 'integer'],
            [[
                '
                fecha_inicio',
                'fecha_fin',
                'created_at',
                'updated_at',
                'deleted_at',
                'idestado',
                'iddireccion',
                'idusuario',
                'idbeneficiario',
                'idcertificacion'
            ], 'safe'],
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
        $query = Mds_certificacion_estado::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'mds_certificacion_estado.idcertificacionestado',
                    'idusuario',
                    'idestado',
                    'iddireccion',
                    'fecha_inicio',
                    'fecha_fin',
                    'idbeneficiario',
                    'idcertificacion'
                ],
                'defaultOrder' => ['mds_certificacion_estado.idcertificacionestado' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith('certificacion');

        // grid filtering conditions
        $query->andFilterWhere([
            'idcertificacionestado' => $this->idcertificacionestado,
        ]);

        if (isset($params['Mds_certificacion_estadoSearch']) && $params['Mds_certificacion_estadoSearch']['fecha_inicio']) {
            $fecha_inicio = $params['Mds_certificacion_estadoSearch']['fecha_inicio'];
            $fecha_inicio = armarDateParaMySql($fecha_inicio);
            $fecha_inicio = date_create($fecha_inicio);
            $fecha_inicio = date_format($fecha_inicio, 'Y-m-d');
            $this->fecha_inicio = $fecha_inicio;
        }

        if (isset($params['Mds_certificacion_estadoSearch']) && $params['Mds_certificacion_estadoSearch']['fecha_fin']) {
            $fecha_fin = $params['Mds_certificacion_estadoSearch']['fecha_fin'];
            $fecha_fin = armarDateParaMySql($fecha_fin);
            $fecha_fin = date_create($fecha_fin);
            $fecha_fin->modify('+1 day'); // Para que cuente hasta 00:00:00 del otro dia 
            $fecha_fin = date_format($fecha_fin, 'Y-m-d');
            $this->fecha_fin = $fecha_fin;
        }
        $this->fecha_inicio ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_inicio))) :  null;
        $this->fecha_fin ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_fin))) :  null;


        $query
            ->andFilterWhere(['>=', 'fecha_inicio', $this->fecha_inicio])
            ->andFilterWhere(['<=', 'fecha_fin', $this->fecha_fin]);
        $query->joinWith('direccion');

        if ($this->iddireccion) {
            $query->andFilterWhere(['in', 'mds_certificacion_direccion.iddireccion', $this->iddireccion]);
        };
        if ($this->idestado) {
            $query->andFilterWhere(['in', 'mds_certificacion_estado.idestado', $this->idestado]);
        };
        if ($this->idusuario) {
            $query->andFilterWhere(['in', 'idusuario', $this->idusuario]);
        };
        if ($this->idcertificacion) {
            $query->andFilterWhere(['in', 'mds_certificacion_estado.idcertificacion', $this->idcertificacion]);
        };
        if ($this->idbeneficiario) {
            $query->andFilterWhere(['in', 'mds_certificacion.idbeneficiario', $this->idbeneficiario]);
        };
        $query->andWhere(['mds_certificacion_estado.deleted_at' => null]);

        if (isset($params['Mds_certificacion_estadoSearch']['fecha_inicio']) && $params['Mds_certificacion_estadoSearch']['fecha_inicio']) {
            $this->fecha_inicio = $params['Mds_certificacion_estadoSearch']['fecha_inicio'];
        }

        if (isset($params['Mds_certificacion_estadoSearch']['fecha_fin']) && $params['Mds_certificacion_estadoSearch']['fecha_fin']) {
            $this->fecha_fin = $params['Mds_certificacion_estadoSearch']['fecha_fin'];
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
