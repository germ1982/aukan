<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RegistroTecnico;

/**
 * RegistroTecnicoSearch represents the model behind the search form about `app\models\RegistroTecnico`.
 */
class RegistroTecnicoSearch extends RegistroTecnico
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Quitá 'estado' de la regla de 'integer'
            [['idregistro', 'idsolicitante', 'iddispositivo', 'idtipo_registro'], 'integer'],

            // Agregalo a la regla de 'safe' para que permita recibir el array de checkboxes
            [['estado', 'fecha_solicitud', 'problema', 'solucion', 'fdesde', 'fhasta', 'solicitante'], 'safe'],
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
        $query = RegistroTecnico::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idregistro' => SORT_DESC]] // Opcional: ver últimos primero
        ]);

        // Si no vienen parámetros de búsqueda en la URL, ponemos los default
        if (!isset($params['RegistroTecnicoSearch']['estado'])) {
            $this->estado = [
                RegistroTecnico::ESTADO_PENDIENTE,
                RegistroTecnico::ESTADO_ASISTENCIA
            ];
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha_solicitud,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_solicitud,'$fecha_hasta_aux')<=0 ";
        }


        $query->andFilterWhere([
            'idregistro' => $this->idregistro,
            'fecha_solicitud' => $this->fecha_solicitud,
            'idsolicitante' => $this->idsolicitante,
            'iddispositivo' => $this->iddispositivo,
            'idtipo_registro' => $this->idtipo_registro,
            'fecha_solucion' => $this->fecha_solucion,
            'estado' => ($this->estado !== null && $this->estado !== '') ? $this->estado : null,

        ]);

        $query->andFilterWhere(['like', 'problema', $this->problema])
            ->andFilterWhere(['like', 'solucion', $this->solucion])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);;

        return $dataProvider;
    }
}
