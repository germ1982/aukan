<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_rum_postulacion;
/*use yii\helpers\ArrayHelper;
use app\models\Mds_rum_persona;
use app\models\Mds_rum_oferta_laboral;*/
/**
 * Mds_rum_postulacionSearch represents the model behind the search form about `app\models\Mds_rum_postulacion`.
 */
class Mds_rum_postulacionSearch extends Mds_rum_postulacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_persona', 'id_oferta'], 'integer'],
            [['id', 'fdesde', 'fhasta','fecha_post', 'hora_post','estado','documento','id_oferta','titulo_oferta','persona','id_persona'], 'safe'],
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
        $query = Mds_rum_postulacion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['id','fecha_post','estado','titulo_oferta','id_oferta','id_persona','persona'],
                
            ]
        ]);
        $dataProvider->setSort([
            'attributes' => [
               
                'documento' => [
                    'asc' => ['sds_com_persona.documento' => SORT_ASC],
                    'desc' => ['sds_com_persona.documento' => SORT_DESC],
                    'label' => 'DNI'
                ],
                'estado' => [
                    'asc' => ['estado' => SORT_ASC],
                    'desc' => ['estado' => SORT_DESC],
                    'label' => 'Estado'
                ],
                'persona' => [
                    'asc' => ['sds_com_persona.nombre' => SORT_ASC,'sds_com_persona.apellido' => SORT_ASC],
                    'desc' => ['sds_com_persona.nombre' => SORT_DESC,'sds_com_persona.apellido' => SORT_DESC],                    
                    'label' => 'Persona'
                ],
                'titulo_oferta' => [
                    'asc' => ['mds_rum_oferta_laboral.titulo' => SORT_ASC],
                    'desc' => ['mds_rum_oferta_laboral.titulo' => SORT_DESC],
                    'label' => 'Oferta Laboral'
                ],
                'fecha_post' => [
                    'asc' => ['fecha_post' => SORT_ASC],
                    'desc' => ['fecha_post' => SORT_DESC],
                    'label' => 'Fecha Postulación'
                ],
                'id' => [
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],                    
                ],
            ],'defaultOrder' => ['id' => SORT_DESC]
            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['mds_rum_oferta_laboral']);
            return $dataProvider;
        }  
        
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fecha_post >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha_post <= '$fecha_hasta_aux'";
        }

        $query 
        ->innerJoin('mds_rum_oferta_laboral', 'mds_rum_oferta_laboral.id = mds_rum_postulacion.id_oferta')
        ->innerJoin('mds_rum_persona', 'mds_rum_persona.id = mds_rum_postulacion.id_persona')
        ->leftJoin('sds_com_persona', 'mds_rum_persona.id_com_persona=sds_com_persona.idpersona')       
        ->all();

       
        $query->andFilterWhere([
            'id' => $this->id,
            'id_persona' => $this->id_persona,
            'id_oferta' => $this->id_oferta,
            /*'fecha_post' => $this->fecha_post,
            'hora_post' => $this->hora_post,*/
            'mds_rum_postulacion.estado' => $this->estado,  
            'sds_com_persona.idpersona' => $this->persona,
            'mds_rum_oferta_laboral.id' => $this->titulo_oferta,
           
        ])        
        ;
        $where_dni=' sds_com_persona.documento like "%'.$this->documento.'%"';
        $where_titulo=" id_oferta in (SELECT mds_rum_oferta_laboral.id from mds_rum_oferta_laboral where mds_rum_oferta_laboral.titulo like '%".$this->titulo_oferta."%')";
       

        $query 
        ->andWhere($where_dni)
        ->andWhere($sql_desde)
        ->andWhere($sql_hasta);
        $query->orderBy(['fecha_post' => SORT_DESC]);
        return $dataProvider;
    }
}
