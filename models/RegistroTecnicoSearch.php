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
    // Propiedad para capturar los valores del filtro (array con 'registros', 'incidencias' o ambos)
    public $filtro_incidencia;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Cargar los enteros específicos (excluyendo filtro_incidencia)
            [['idregistro', 'idsolicitante', 'iddispositivo', 'idtipo_registro', 'usuario_carga'], 'integer'],

            // filtro_incidencia y estado deben tratarse como safe porque pueden ser arrays
            [['estado', 'fecha_solicitud', 'problema', 'solucion', 'fdesde', 'fhasta', 'solicitante', 'usuario_carga', 'filtro_incidencia'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = RegistroTecnico::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idregistro' => SORT_DESC]]
        ]);

        // Si no vienen parámetros de búsqueda en la URL, ponemos los default de estado
        if (!isset($params['RegistroTecnicoSearch']['estado'])) {
            $this->estado = [
                RegistroTecnico::ESTADO_PENDIENTE,
                RegistroTecnico::ESTADO_ASISTENCIA
            ];
        }

        $this->load($params);

        if (!$this->validate()) {
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

        // LÓGICA DEL FILTRO DE INCIDENCIA
        if (!empty($this->filtro_incidencia) && is_array($this->filtro_incidencia)) {
            $tieneRegistros = in_array('registros', $this->filtro_incidencia);
            $tieneIncidencias = in_array('incidencias', $this->filtro_incidencia);

            // Subconsulta pura SQL
            $subQuery = (new \yii\db\Query())
                ->select('idregistro')
                ->from('registro_tecnico_incidencia')
                ->where('idregistro IS NOT NULL');

            if ($tieneRegistros && !$tieneIncidencias) {
                // Solo Registros (NO tienen incidencias asociadas)
                $query->andWhere(['NOT IN', 'idregistro', $subQuery]);
            } elseif (!$tieneRegistros && $tieneIncidencias) {
                // Solo Incidencias (Tienen al menos una incidencia asociada)
                $query->andWhere(['IN', 'idregistro', $subQuery]);
            }
        }

        $query->andFilterWhere(['like', 'problema', $this->problema])
            ->andFilterWhere(['like', 'solucion', $this->solucion])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}