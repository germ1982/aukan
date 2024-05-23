<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_800_atencion;

/**
 * Sds_800_atencionSearch represents the model behind the search form about `app\models\Sds_800_atencion`.
 */
class Sds_800_atencionSearch extends Sds_800_atencion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idllamada', 'idpersona', 'beneficio', 'edad', 'sabe_leer', 'nivel_estudio', 'trabajo', 'antiguedad', 'ubicacion_anterior', 'atencion_anterior', 'asistencia_estado', 'familia', 'sentimiento', 'orientado', 'evaluacion_funcional', 'intoxicado', 'alucinaciones', 'violentado', 'expresar', 'tratamiento', 'idusuario'], 'integer'],
            [['causa_situacion', 'trabajo_detalle', 'ubicacion_anterior_detalle', 'atencion_anterior_institucion', 'atencion_anterior_profesional', 'asistencia_estado_detalle', 'evaluacion_funcional_detalle', 'tratamiento_institucion', 'tratamiento_profesional', 'observaciones', 'persona_datos', 'fecha_hora', 'telefono'], 'safe'],
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
        $query = Sds_800_atencion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idllamada' => $this->idllamada,
            'idpersona' => $this->idpersona,
            'beneficio' => $this->beneficio,
            'edad' => $this->edad,
            'sabe_leer' => $this->sabe_leer,
            'nivel_estudio' => $this->nivel_estudio,
            'trabajo' => $this->trabajo,
            'antiguedad' => $this->antiguedad,
            'ubicacion_anterior' => $this->ubicacion_anterior,
            'atencion_anterior' => $this->atencion_anterior,
            'asistencia_estado' => $this->asistencia_estado,
            'familia' => $this->familia,
            'sentimiento' => $this->sentimiento,
            'orientado' => $this->orientado,
            'evaluacion_funcional' => $this->evaluacion_funcional,
            'intoxicado' => $this->intoxicado,
            'alucinaciones' => $this->alucinaciones,
            'violentado' => $this->violentado,
            'expresar' => $this->expresar,
            'tratamiento' => $this->tratamiento,
            'fecha_hora' => $this->fecha_hora,
            'idusuario' => $this->idusuario,
            'telefono' => $this->telefono,
        ]);

        $query->andFilterWhere(['like', 'causa_situacion', $this->causa_situacion])
            ->andFilterWhere(['like', 'trabajo_detalle', $this->trabajo_detalle])
            ->andFilterWhere(['like', 'ubicacion_anterior_detalle', $this->ubicacion_anterior_detalle])
            ->andFilterWhere(['like', 'atencion_anterior_institucion', $this->atencion_anterior_institucion])
            ->andFilterWhere(['like', 'atencion_anterior_profesional', $this->atencion_anterior_profesional])
            ->andFilterWhere(['like', 'asistencia_estado_detalle', $this->asistencia_estado_detalle])
            ->andFilterWhere(['like', 'evaluacion_funcional_detalle', $this->evaluacion_funcional_detalle])
            ->andFilterWhere(['like', 'tratamiento_institucion', $this->tratamiento_institucion])
            ->andFilterWhere(['like', 'tratamiento_profesional', $this->tratamiento_profesional])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'persona_datos', $this->persona_datos]);

        return $dataProvider;
    }
}
