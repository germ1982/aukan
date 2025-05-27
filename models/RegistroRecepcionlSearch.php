<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RegistroRecepcion;



/**
 * RegistroRecepcionlSearch represents the model behind the search form about `app\models\RegistroRecepcion`.
 */
class RegistroRecepcionlSearch extends RegistroRecepcion
{
    public $dispositivoDescripcion;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_registro_recepcion', 'dni', 'acceso', 'id_dispositivo_derivacion', 'id_responsable_derivacion', 'id_tipo_recepcion'], 'integer'],
            [['dispositivoDescripcion', 'fecha', 'hora', 'motivo', 'observacion'], 'safe'],

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
        $query = RegistroRecepcion::find()->joinWith('dispositivoDerivacion');

        // 🔧 Crear dataProvider ANTES de usarlo
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // 🔃 Definir cómo ordenar por el campo virtual
        $dataProvider->sort->attributes['dispositivoDescripcion'] = [
            'asc' => ['dispositivo_derivacion.descripcion' => SORT_ASC],
            'desc' => ['dispositivo_derivacion.descripcion' => SORT_DESC],
        ];

        // ⚠️ Cargar y validar datos ANTES de aplicar filtros
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // 📌 Filtros exactos
        $query->andFilterWhere([
            'id_registro_recepcion' => $this->id_registro_recepcion,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'dni' => $this->dni,
            'acceso' => $this->acceso,
            'id_dispositivo_derivacion' => $this->id_dispositivo_derivacion,
            'id_responsable_derivacion' => $this->id_responsable_derivacion,
            'id_tipo_recepcion' => $this->id_tipo_recepcion,
        ]);

        // 📌 Filtros con "like"
        $query->andFilterWhere(['like', 'motivo', $this->motivo])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'organismo_dispositivo.descripcion', $this->dispositivoDescripcion]);

        return $dataProvider;
    }
}
