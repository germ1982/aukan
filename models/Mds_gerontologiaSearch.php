<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_gerontologia;

/**
 * Mds_gerontologiaSearch represents the model behind the search form of `app\models\Mds_gerontologia`.
 */
class Mds_gerontologiaSearch extends Mds_gerontologia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idgerontologia', 'idobrasocial', 'idestadocivil', 'idvivienda', 'idescolaridad', 'fuma', 'suenio_adecuado', 'ejercicio_fisico', 'vacunas_obligatorias', 'idvacunascovid', 'diuresis', 'catarsis', 'antecedentes_hta', 'antecedentes_acv', 'antecedentes_cardiaca', 'antecedentes_diabetes', 'antecedentes_cancer', 'caidas', 'idusuario_carga', 'idusuario_modifica'], 'integer'],
            [['fecha_atencion', 'idpersona', 'domicilio', 'telefono', 'familia', 'lugar_nacimiento', 'residencia', 'vivencias', 'tiempo_libre', 'antecedentes_otras', 'medicacion_actual', 'estudios_complementarios', 'examen_fis_ta', 'examen_fis_sato2', 'examen_fis_fc', 'examen_fis_abdomen', 'examen_fis_aparato_respiratorio', 'examen_fis_miembros_inferiores', 'examen_fis_observaciones', 'problemas_actuales', 'recomendaciones', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = Mds_gerontologia::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idgerontologia' => SORT_DESC]],
        ]);

        $this->load($params);

        $query->joinWith('persona');
        if ($this->idpersona) {
            $query->andWhere([
                'or',
                ['like', 'sds_com_persona.documento', $this->idpersona],
                ['like', 'sds_com_persona.nombre', $this->idpersona],
                ['like', 'sds_com_persona.apellido', $this->idpersona]
            ]);
        };

        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['deleted_at' => null]]);
        } else if ($this->deleted_at === '1') {
            $query->andWhere(['deleted_at' => null]);
        }

        if (isset($params['Mds_gerontologiaSearch']) && $params['Mds_gerontologiaSearch']['fecha_atencion']) {
            $fecha_atencion = $params['Mds_gerontologiaSearch']['fecha_atencion'];
            $fecha_atencion = armarDateParaMySql($fecha_atencion);
            $fecha_atencion = date_create($fecha_atencion);
            $fecha_atencion = date_format($fecha_atencion, 'Y-m-d');
            $this->fecha_atencion = $fecha_atencion;
        }
        $this->fecha_atencion ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_atencion))) :  null;
        $query->andFilterWhere(['<=', 'fecha_atencion', $this->fecha_atencion]);

        // grid filtering conditions
        $query->andFilterWhere([
            'idgerontologia' => $this->idgerontologia,
            'idvivienda' => $this->idvivienda,
        ]);

        $query->andFilterWhere(['like', 'residencia', $this->residencia]);

        if (isset($params['Mds_gerontologiaSearch']) && $params['Mds_gerontologiaSearch']['fecha_atencion']) {
            $this->fecha_atencion = $params['Mds_gerontologiaSearch']['fecha_atencion'];
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
