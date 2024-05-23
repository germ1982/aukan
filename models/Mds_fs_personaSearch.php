<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_fs_persona;

/**
 * Mds_fs_personaSearch represents the model behind the search form about `app\models\Mds_fs_persona`.
 */
class Mds_fs_personaSearch extends Mds_fs_persona
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idfspersona', 'dni', 'nacionalidad', 'genero', 'estado_civil', 'idlocalidad', 'idprovincia'], 'integer'],
            [['nombre', 'apellido', 'fecha_nacimiento', 'lugar_nacimiento', 'domicilio', 'tiempo_provincia', 'profesion', 'telefono', 'telefono_alternativo', 'mail', 'grupo_familiar', 'inscripto_rua', 'motivo_fs', 'acuerdo_familia', 'conocimiento_programa', 'disponibilidad_horaria', 'franja_etaria', 'consulta', 'fecha_hora', 'inscripto_rua_check', 'estado', 'informe_adjunto_path', 'fecha_desde', 'fecha_hasta'], 'safe'],
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
        $query = Mds_fs_persona::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //esto es para que ande el filtro de las fecha_inscripcion

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fecha_desde != null) {
            $fecha_desde_aux = date_format(date_create($this->fecha_desde), 'Y-m-d');
            $sql_desde = "fecha_hora >= '$fecha_desde_aux'";
        }
        if ($this->fecha_hasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fecha_hasta), 'Y-m-d');
            $sql_hasta = "fecha_hora <= '$fecha_hasta_aux'";
        }

        $query->andFilterWhere([
            'idfspersona' => $this->idfspersona,
            'dni' => $this->dni,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'nacionalidad' => $this->nacionalidad,
            'genero' => $this->genero,
            'estado_civil' => $this->estado_civil,
            'idlocalidad' => $this->idlocalidad,
            'idprovincia' => $this->idprovincia,
            'fecha_hora' => $this->fecha_hora,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'lugar_nacimiento', $this->lugar_nacimiento])
            ->andFilterWhere(['like', 'domicilio', $this->domicilio])
            ->andFilterWhere(['like', 'tiempo_provincia', $this->tiempo_provincia])
            ->andFilterWhere(['like', 'profesion', $this->profesion])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'telefono_alternativo', $this->telefono_alternativo])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'grupo_familiar', $this->grupo_familiar])
            ->andFilterWhere(['like', 'inscripto_rua', $this->inscripto_rua])
            ->andFilterWhere(['like', 'motivo_fs', $this->motivo_fs])
            ->andFilterWhere(['like', 'acuerdo_familia', $this->acuerdo_familia])
            ->andFilterWhere(['like', 'conocimiento_programa', $this->conocimiento_programa])
            ->andFilterWhere(['like', 'disponibilidad_horaria', $this->disponibilidad_horaria])
            ->andFilterWhere(['like', 'franja_etaria', $this->franja_etaria])
            ->andFilterWhere(['like', 'consulta', $this->consulta])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'inscripto_rua_check', $this->inscripto_rua_check])
            ->orderBy(['fecha_hora' => SORT_DESC]);



        return $dataProvider;
    }
}
