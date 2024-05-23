<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_rum_novedad;

/**
 * Mds_rum_novedadaSearch represents the model behind the search form about `app\models\Mds_rum_novedad`.
 */
class Mds_rum_novedadaSearch extends Mds_rum_novedad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'autor', 'comment_count', 'activo', 'publicado'], 'integer'],
            [['publicado','fdesde', 'fhasta', 'activo','autor2','comment_status', 'contenido', 'titulo', 'fechamodificacion', 'horamodificacion', 'fechaalta', 'horaalta', 'fecha_publicacion', 'imagen'], 'safe'],
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
        $query = Mds_rum_novedad::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['autor2','titulo','activo','fecha_publicacion','publicado'],
               
            ]
        ]);
        $dataProvider->setSort([
            'attributes' => [                              
                
                'autor2' => [
                    'asc' => ['mds_seg_usuario.nombre' => SORT_ASC,'mds_seg_usuario.apellido' => SORT_ASC],
                    'desc' => ['mds_seg_usuario.nombre' => SORT_DESC,'mds_seg_usuario.apellido' => SORT_DESC],                    
                    'label' => 'Categoria'
                ],  
                'titulo' => [
                    'asc' => ['titulo' => SORT_ASC],
                    'desc' => ['titulo' => SORT_DESC],                    
                    'label' => 'Titulo'
                ],       
                'activo' => [
                    'asc' => ['activo' => SORT_ASC],
                    'desc' => ['activo' => SORT_DESC],                    
                    'label' => 'Activo'
                ], 
                'publicado' => [
                    'asc' => ['publicado' => SORT_ASC],
                    'desc' => ['publicado' => SORT_DESC],                    
                    'label' => 'Publicado'
                ], 
                'fecha_publicacion' => [
                    'asc' => ['fecha_publicacion' => SORT_ASC],
                    'desc' => ['fecha_publicacion' => SORT_DESC],
                    'label' => 'Fecha Publicación'
                ],       
            ]
            
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
        ->innerJoin('mds_seg_usuario', 'mds_rum_novedad.autor=mds_seg_usuario.idusuario')            
        ->all();
        $query->andFilterWhere([
            'id' => $this->id,            
            'comment_count' => $this->comment_count,
            'mds_rum_novedad.activo' => $this->activo,
            'fechamodificacion' => $this->fechamodificacion,
            'horamodificacion' => $this->horamodificacion,
            'fechaalta' => $this->fechaalta,
            'horaalta' => $this->horaalta,
            'fecha_publicacion' => $this->fecha_publicacion,
            'publicado' => $this->publicado,
        ]);
        $where_autor=" autor in (SELECT mds_seg_usuario.idusuario from mds_seg_usuario where (mds_seg_usuario.nombre like '%".$this->autor2."%') or (mds_seg_usuario.apellido like '%".$this->autor2."%'))";


        $query->andFilterWhere(['like', 'comment_status', $this->comment_status])
            ->andFilterWhere(['like', 'contenido', $this->contenido])
            ->andFilterWhere(['like', 'titulo', $this->titulo])           
            ->andFilterWhere(['like', 'imagen', $this->imagen]);

        $query 
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta)
            ->andWhere($where_autor);          
        return $dataProvider;
    }
}
