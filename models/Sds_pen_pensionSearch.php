<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_pen_pension;

/**
 * Sds_pen_pensionSearch represents the model behind the search form about `app\models\Sds_pen_pension`.
 */
class Sds_pen_pensionSearch extends Sds_pen_pension
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpension', 'idpersona', 'programa', 'legajo', 'legajo_rh', 'tipo_otorgado', 'anio_otorgado', 'numero_otorgado', 'tipo_baja', 'anio_baja', 'causa_baja', 'numero_baja', 'persona_transferida', 'lugar_pago', 'idlocalidad', 'idbarrio', 'estado', 'documento_tipo', 'documento'], 'integer'],
            [[
                'tramite_nacion', 'fecha_carga', 'fecha_otorgado', 'fecha_baja', 'transferida', 'observaciones_baja', 'notas', 'calle',
                'numero', 'casa', 'manzana', 'lote', 'departamento', 'expediente', 'resolucion', 'documento_tipo', 'documento', 'nombre',
                'apellido', 'estado_descripcion', 'programa_descripcion', 'fdesde', 'fhasta', 'causa_baja_descripcion'
            ], 'safe'],
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
        $query = Sds_pen_pension::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idpersona', 'documento_tipo', 'documento', 'nombre',
                    'apellido', 'legajo', 'idlocalidad', 'idbarrio',
                    'programa', 'estado', 'fecha_carga', 'fecha_otorgado',
                    'fecha_baja', 'estado_descripcion', 'programa_descripcion', 'causa_baja_descripcion'
                ],
                'defaultOrder' => ['apellido' => SORT_ASC]
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
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha_baja,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_baja,'$fecha_hasta_aux')<=0 ";
        }

        if ($this->documento_tipo == null) {
            $having_documento_tipo = '';
            $ban = 0;
        } else {
            $having_documento_tipo = '(documento_tipo=' . $this->documento_tipo . ')';
            $ban = 1;
        }

        if ($this->documento == null) {
            $having_documento = '';
        } else {
            $having_documento = '(documento=' . $this->documento . ')';
            if ($ban == 1) {
                $having_documento = " and $having_documento";
            }
        }



        $mysql_documento_tipo = 'select sds_com_persona.documento_tipo from sds_com_persona where sds_com_persona.idpersona = pension.idpersona';

        $mysql_documento = 'select sds_com_persona.documento from sds_com_persona where sds_com_persona.idpersona = pension.idpersona';


        $query->addSelect([
            "pension.*", "pers.nombre", "pers.apellido",
            "($mysql_documento_tipo) as documento_tipo", "($mysql_documento) as documento",
            "conf_programa.descripcion programa_descripcion",
            "conf_estado.descripcion estado_descripcion",
            "IFNULL(conf_causa_baja.descripcion,'') causa_baja_descripcion"

        ]);
        $query->from('sds_pen_pension pension');
        $query->join('left join', 'sds_com_persona pers', 'pension.idpersona=pers.idpersona');
        $query->join('left join', 'sds_com_configuracion conf_programa', 'pension.programa=conf_programa.idconfiguracion');
        $query->join('left join', 'sds_com_configuracion conf_estado', 'pension.estado=conf_estado.idconfiguracion');
        $query->join('left join', 'sds_com_configuracion conf_causa_baja', 'pension.causa_baja=conf_causa_baja.idconfiguracion');
        $query->andFilterWhere([
            'pension.idpension' => $this->idpension,
            'pension.idpersona' => $this->idpersona,
            'pension.programa' => $this->programa_descripcion != null ? $this->programa_descripcion : $this->programa,
            'pension.legajo' => $this->legajo,
            'pension.legajo_rh' => $this->legajo_rh,
            'pension.fecha_carga' => $this->fecha_carga,
            'pension.fecha_otorgado' => $this->fecha_otorgado,
            'pension.tipo_otorgado' => $this->tipo_otorgado,
            'pension.anio_otorgado' => $this->anio_otorgado,
            'pension.numero_otorgado' => $this->numero_otorgado,
            'pension.fecha_baja' => $this->fecha_baja,
            'pension.tipo_baja' => $this->tipo_baja,
            'pension.anio_baja' => $this->anio_baja,
            'pension.causa_baja' => $this->causa_baja,
            'pension.numero_baja' => $this->numero_baja,
            'pension.persona_transferida' => $this->persona_transferida,
            'pension.lugar_pago' => $this->lugar_pago,
            'pension.idlocalidad' => $this->idlocalidad,
            'pension.idbarrio' => $this->idbarrio,
            'pension.estado' => $this->estado_descripcion!=null ? $this->estado_descripcion : $this->estado,
            'pension.causa_baja' => $this->causa_baja_descripcion,
        ]);

        $query->andFilterWhere(['like', 'tramite_nacion', $this->tramite_nacion])
            ->andFilterWhere(['like', 'transferida', $this->transferida])
            ->andFilterWhere(['like', 'observaciones_baja', $this->observaciones_baja])
            ->andFilterWhere(['like', 'notas', $this->notas])
            ->andFilterWhere(['like', 'calle', $this->calle])
            ->andFilterWhere(['like', 'numero', $this->numero])
            ->andFilterWhere(['like', 'casa', $this->casa])
            ->andFilterWhere(['like', 'manzana', $this->manzana])
            ->andFilterWhere(['like', 'lote', $this->lote])
            ->andFilterWhere(['like', 'departamento', $this->departamento])
            ->andFilterWhere(['like', 'expediente', $this->expediente])
            ->andFilterWhere(['like', 'resolucion', $this->resolucion])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        $query->having($having_documento_tipo . $having_documento . ($having_documento_tipo . $having_documento != "" ? "and " : "") . "(concat(apellido,', ',nombre) like '%" . $this->apellido . "%')");

        return $dataProvider;
    }
}
