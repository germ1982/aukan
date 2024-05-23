<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_odontologia;
use Yii;

/**
 * Mds_odontologiaSearch represents the model behind the search form of `app\models\Mds_odontologia`.
 */
class Mds_odontologiaSearch extends Mds_odontologia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'idodontologia', 'idpersona', 'telefono', 'vacuna_covid19', 'vacunas_obligatorias', 'cant_dientes', 'cant_caries', 'cant_dientes_temporales', 'cant_caries_temporales', 'cant_obturados', 'cant_perdidos', 'cant_obturados_temporales', 'cant_perdidos_temporales', 'iddispositivo',
                'idescolaridad', 'idtipointervencion', 'idtipovisita', 'idusuario_carga', 'idusuario_modifica',
                'observaciones', 'enfermedad_periodontal', 'enfermedad_base', 'created_at', 'updated_at', 'deleted_at', 'fecha_atencion'
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
        $query = Mds_odontologia::find();
        $usuarioAuth = Yii::$app->user->identity;

        $hasRolAdminGeneral = Mds_odontologia::tieneRol(Mds_odontologia::ID_ROL_ADMIN_GENERAL);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort' => ['defaultOrder' => ['idodontologia' => SORT_DESC]],
            'sort' => [
                'attributes' => ['idodontologia', 'fechaalta', 'idtipointervencion', 'iddispositivo', 'idescolaridad', 'idpersona', 'edad'],
                'defaultOrder' => ['idodontologia' => SORT_DESC]
            ]
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'idpersona' => [
                    'asc' => ['sds_com_persona.nombre' => SORT_ASC, 'sds_com_persona.apellido' => SORT_ASC],
                    'desc' => ['sds_com_persona.nombre' => SORT_DESC, 'sds_com_persona.apellido' => SORT_DESC],
                    'label' => 'Persona'
                ],
                'idtipointervencion' => [
                    'asc' => ['idtipointervencion' => SORT_ASC],
                    'desc' => ['idtipointervencion' => SORT_DESC],
                    'label' => 'Intervención'
                ],
                'iddispositivo' => [
                    'asc' => ['iddispositivo' => SORT_ASC],
                    'desc' => ['iddispositivo' => SORT_DESC],
                    'label' => 'Dispositivo'
                ],
                'idescolaridad' => [
                    'asc' => ['idescolaridad' => SORT_ASC],
                    'desc' => ['idescolaridad' => SORT_DESC],
                    'label' => 'Escolaridad'
                ],
                'edad' => [
                    'asc' => ['sds_com_persona.fecha_nacimiento' => SORT_ASC],
                    'desc' => ['sds_com_persona.fecha_nacimiento' => SORT_DESC],
                    'label' => 'Edad'
                ],
                'fecha_atencion' => [
                    'asc' => ['fecha_atencion' => SORT_ASC],
                    'desc' => ['fecha_atencion' => SORT_DESC],
                    'label' => 'Fecha Atención',
                ],
                'idodontologia' => [
                    'asc' => ['idodontologia' => SORT_ASC],
                    'desc' => ['idodontologia' => SORT_DESC],
                ],
            ], 'defaultOrder' => ['idodontologia' => SORT_DESC]

        ]);

        $this->load($params);

        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['deleted_at' => null]);
            }
        } else {
            $query->andWhere(['deleted_at' => null]);
        }

        if (isset($params['Mds_odontologiaSearch']) && $params['Mds_odontologiaSearch']['fecha_atencion']) {
            $fecha_atencion = $params['Mds_odontologiaSearch']['fecha_atencion'];
            $fecha_atencion = armarDateParaMySql($fecha_atencion);
            $fecha_atencion = date_create($fecha_atencion);
            $fecha_atencion = date_format($fecha_atencion, 'Y-m-d');
            $this->fecha_atencion = $fecha_atencion;
        }
        $this->fecha_atencion ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_atencion))) :  null;
        $query->andFilterWhere(['<=', 'fecha_atencion', $this->fecha_atencion]);

        $query->joinWith('persona');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->idpersona) {
            $query->andWhere([
                'or',
                ['like', 'sds_com_persona.documento', $this->idpersona],
                ['like', 'sds_com_persona.nombre', $this->idpersona],
                ['like', 'sds_com_persona.apellido', $this->idpersona]
            ]);
        };
        // grid filtering conditions
        $query->andFilterWhere([
            'idodontologia' => $this->idodontologia,
            'idtipovisita' => $this->idtipovisita,
        ])
            ->andFilterWhere(['in', 'iddispositivo', $this->iddispositivo])
            ->andFilterWhere(['in', 'idescolaridad', $this->idescolaridad])
            ->andFilterWhere(['in', 'idtipointervencion', $this->idtipointervencion]);

        if (isset($params['Mds_odontologiaSearch']) && $params['Mds_odontologiaSearch']['fecha_atencion']) {
            $this->fecha_atencion = $params['Mds_odontologiaSearch']['fecha_atencion'];
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
