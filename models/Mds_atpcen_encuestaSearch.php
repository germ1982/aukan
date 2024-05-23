<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_atpcen_encuesta;

/**
 * Mds_atpcen_encuestaSearch represents the model behind the search form about `app\models\Mds_atpcen_encuesta`.
 */
class Mds_atpcen_encuestaSearch extends Mds_atpcen_encuesta
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_atpcen', 'id_persona_carga', 'entrevistador', 'id_localidad_entrevista', 'id_risneu', 'tip_control', 'id_fuente_ingreso', 'sexo_tutor', 'tiene_obra_social', 'tiene_biopsia', 'concurre_a_control', 'frecuencia', 'integrante_celiaco', 'establecimiento_salud', 'id_establ_salud', 'organismo_asiste', 'cantidad_asistencia', 'periocidad_asistencia'], 'integer'],
            [['persona','fecha_alta', 'fecha_hora_entrevista', 'dni_beneficiario', 'telefono_contacto1', 'telefono_contacto2', 'email', 'tipo_documento_tutor', 'documento_tutor', 'cuil_tutor', 'apellido_tutor', 'fecha_nac_tutor', 'parentezco_tutor', 'frente_dni_tutor', 'dorso_dni_tutor', 'nombre_tutor', 'obra_social', 'fecha_diagnostico', 'estudio_biopsia', 'capacitacion_solicitada', 'observacion'], 'safe'],
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
        $query = Mds_atpcen_encuesta::find();

       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['persona'],
                
            ]
        ]);
        $dataProvider->setSort([
            'attributes' => [
               
               
                'persona' => [
                    'asc' => ['sds_com_persona.nombre' => SORT_ASC,'sds_com_persona.apellido' => SORT_ASC],
                    'desc' => ['sds_com_persona.nombre' => SORT_DESC,'sds_com_persona.apellido' => SORT_DESC],                    
                    'label' => 'Persona'
                ],
                
            ]
            
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query 
        ->innerJoin('sds_ris_persona', 'mds_atpcen_encuesta.id_risneu = sds_ris_persona.idrisneu')
        ->innerJoin('sds_com_persona', 'sds_com_persona.idpersona = sds_ris_persona.idpersona')       
        ->all();

            
        $query->andFilterWhere([           
            'sds_com_persona.idpersona' => $this->persona,                      
        ])        
        ;
            

        return $dataProvider;
    }
}
