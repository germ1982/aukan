<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_rum_oferta_laboral;

/**
 * Mds_rum_oferta_laboralSearch represents the model behind the search form about `app\models\Mds_rum_oferta_laboral`.
 */
class Mds_rum_oferta_laboralSearch extends Mds_rum_oferta_laboral
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_nivel_ocupacion', 'id_experiencia', 'genero', 'id_categoria', 'id_cualificacion', 'id_tipo_trabajo', 'activo', 'num_visto', 'id_dur_trabajo', 'id_localidad', 'id_empleador', 'fin_dias', 'fin_horas', 'fin_min', 'fin_seg'], 'integer'],
            [['empresa','activo','id','fdesde', 'fhasta','id_dur_trabajo2','genero2','id_categoria2','titulo_of','titulo', 'fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion', 'fecha_publicacion', 'hora_publicacion', 'descripcion', 'competencia', 'email1', 'email2', 'telefono1', 'telefono2', 'imagen', 'ubicacion'], 'safe'],            
            [['salario'], 'number'],
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
        $query = Mds_rum_oferta_laboral::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['activo','id','postulaciones','fecha_publicacion','titulo_of','num_visto2','id_categoria2','genero2','id_dur_trabajo2','empresa'],
               
            ]
        ]);
        $dataProvider->setSort([
            'attributes' => [                              
                'titulo_of' => [
                    'asc' => ['titulo' => SORT_ASC],
                    'desc' => ['titulo' => SORT_DESC],                    
                    'label' => 'Titulo'
                ],
                'num_visto2' => [
                    'asc' => ['num_visto' => SORT_ASC],
                    'desc' => ['num_visto' => SORT_DESC],                    
                    'label' => 'Visto por'
                ],
                'id_categoria2' => [
                    'asc' => ['sds_com_configuracion.descripcion' => SORT_ASC],
                    'desc' => ['sds_com_configuracion.descripcion' => SORT_DESC],                    
                    'label' => 'Categoria'
                ],
                'genero2' => [
                    'asc' => ['genero' => SORT_ASC],
                    'desc' => ['genero' => SORT_DESC],                    
                    'label' => 'Genero'
                ],
                'id_dur_trabajo2' => [
                    'asc' => ['id_dur_trabajo' => SORT_ASC],
                    'desc' => ['id_dur_trabajo' => SORT_DESC],                    
                    'label' => 'Dur. Trabajo'
                ],
                'empresa' => [
                    'asc' => ['id_empleador' => SORT_ASC],
                    'desc' => ['id_empleador' => SORT_DESC],                    
                    'label' => 'Empresa'
                ],
                'fecha_publicacion' => [
                    'asc' => ['fecha_publicacion' => SORT_ASC],
                    'desc' => ['fecha_publicacion' => SORT_DESC],
                    'label' => 'Fecha Publicación'
                ],
                'id' => [
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],                   
                ],
                'activo' => [
                    'asc' => ['mds_rum_oferta_laboral.activo' => SORT_ASC],
                    'desc' => ['mds_rum_oferta_laboral.activo' => SORT_DESC],                   
                ],
            ],'defaultOrder' => ['id' => SORT_DESC]
            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fecha_publicacion >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha_publicacion <= '$fecha_hasta_aux'";
        }
        $query 
        ->innerJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion = mds_rum_oferta_laboral.id_categoria')    
        ->all();

        $query->andFilterWhere([
            'id' => $this->id,
            'fechaalta' => $this->fechaalta,
            'horaalta' => $this->horaalta,
            'fechamodificacion' => $this->fechamodificacion,
            'horamodificacion' => $this->horamodificacion,            
            'hora_publicacion' => $this->hora_publicacion,
            'salario' => $this->salario,
            'id_nivel_ocupacion' => $this->id_nivel_ocupacion,
            'id_experiencia' => $this->id_experiencia,
            'genero' => $this->genero2,            
            'id_cualificacion' => $this->id_cualificacion,
            'id_tipo_trabajo' => $this->id_tipo_trabajo,
            'mds_rum_oferta_laboral.activo' => $this->activo,            
            'id_dur_trabajo' => $this->id_dur_trabajo2,
            'id_localidad' => $this->id_localidad,
            'id_empleador' => $this->id_empleador,
            'fin_dias' => $this->fin_dias,
            'fin_horas' => $this->fin_horas,
            'fin_min' => $this->fin_min,
            'fin_seg' => $this->fin_seg,
            'titulo' => $this->titulo_of,
            'id_categoria' => $this->id_categoria2,  
            'id_empleador' => $this->empresa,  
            
        ]);

        $query
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'competencia', $this->competencia])
            ->andFilterWhere(['like', 'email1', $this->email1])
            ->andFilterWhere(['like', 'email2', $this->email2])
            ->andFilterWhere(['like', 'telefono1', $this->telefono1])
            ->andFilterWhere(['like', 'telefono2', $this->telefono2])
            ->andFilterWhere(['like', 'imagen', $this->imagen])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion]);
        $query             
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);
        return $dataProvider;
    }
}
