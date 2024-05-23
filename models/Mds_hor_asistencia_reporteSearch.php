<?php

namespace app\models;

use app\models\Mds_hor_asistencia_reporte;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Mds_hor_asistencia_reporteSearch represents the model behind the search form of `app\models\Mds_hor_asistencia_reporte`.
 */
class Mds_hor_asistencia_reporteSearch extends Mds_hor_asistencia_reporte
{
    public $inasistencias = 0;
    public $iddispositivo;
    public $idorganismo;
    public $organismo_search;
    public $eventuales;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'periodo', 'fecha', 'dia', 'estado', 'detalle', 'organismo_search',
                'idorganismo', 'eventuales', 'desde', 'hasta', 'pr_categoria', 'detalle_fichada',
                'empleado', 'legajo'
            ], 'safe'],
            [[
                'idfranco', 'idregistrohorario', 'idlicencia', 'codContacto', 'inasistencias',
                'iddispositivo', 'idorganismo', 'turno_rotativo'
            ], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
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
        set_time_limit(200);

        $query = Mds_hor_asistencia_reporte::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha', 'dia', 'estado', 'detalle', 'codContacto'],
                'defaultOrder' => ['fecha' => SORT_ASC, 'codContacto' => SORT_ASC],
            ],
        ]);

        $dataProvider->pagination->pageSize = 31;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->codContacto == null) {
            $this->codContacto = -1;
        }
        if ($this->desde == null) {
            $this->desde = -1;
        }
        if ($this->hasta == null) {
            $this->hasta = -1;
        }
        if ($this->estado == null) {
            $this->estado = -1;
        }
        if ($this->iddispositivo == null) {
            $this->iddispositivo = -1;
        }
        if ($this->idorganismo == null) {
            $this->idorganismo = -1;
        }
        if ($this->eventuales == null) {
            $this->eventuales = -1;
        }
        if ($this->desde == -1) {
            $this->desde = date('n/Y', strtotime("-1 month"));
        }
        if ($this->hasta == -1) {
            $this->hasta = $this->desde == -1 ? date('n/Y', strtotime("-1 month")) : $this->desde;
        }
        $desde_parts = explode("/", $this->desde);
        $mes_desde = $desde_parts[0];
        $anio_desde = $desde_parts[1];

        $hasta_parts = explode("/", $this->hasta);
        $mes_hasta = $hasta_parts[0];
        $anio_hasta = $hasta_parts[1];
        $desde = $anio_desde . '-' . $mes_desde . '-01';
        $hasta = $anio_hasta . '-' . $mes_hasta . '-' . date("t", strtotime($anio_hasta . '-' . $mes_hasta . '-01'));
        if ($this->inasistencias != 1) {
            $query->addSelect([
                "contpers.legajo AS legajo", "contpers.idcontacto AS codContacto", "contpers.iddispositivo", "temp.fechPer fecha",
                "CONCAT(DAY(fechPer),'/',MONTH(fechPer)) AS dia",
                "if((contpers.rotativo=0 and (DAYOFWEEK(fechPer) in (1,7)
                or fechPer in (select fecha from mds_hor_feriado))) or
                temp.idfranco is not null,
                if(contpers.rotativo=0 and DAYOFWEEK(fechPer) in (1,7),if(DAYOFWEEK(fechPer)=1,'Franco','Franco'),
                if(fechPer in (select fecha from mds_hor_feriado),'Feriado',
                if(francotipo is not null,CONVERT( francotipo USING utf8),'Franco'))),
                if((contpers.idcontacto=codContacto and idregistrohorario is not null) or temp.idcertificacion is not null,'Asistencia',
                if(idlicencia is not null,'Licencia','Inasistencia'))) estado",
                "if(temp.idcertificacion is not null,'ASISTENCIA CERTIFICADA',
                if(idregistrohorario is not null,
                '',
                if(temp.idfranco is not null,francodescr,
                ifnull(if(contpers.rotativo=0,(select descripcion from mds_hor_feriado fer
                where fer.fecha=fechPer),null),
                if(idlicencia is not null,(select licencia.detalle
                from mds_hor_licencia licencia
                where temp.idlicencia=licencia.idlicencia order by desde desc limit 1),''))))) detalle",
                "group_concat(distinct idregistrohorario ORDER BY fecha_reg ASC SEPARATOR ',') detalle_fichada",
                //"CONCAT('PR: ',pr,' | Categoría: ',padron.categoria,' | Org./Disp.: ',organismo_desc,' - ',dispositivo_desc) pr_categoria",
                "CONCAT('Categoría: ',padron.categoria,' | Org./Disp.: ',organismo_desc,' - ',dispositivo_desc) pr_categoria",
                "temp.latitud", "temp.longitud", "foto", "turno_rotativo",
            ]);
            $query->from(["(select  tempPer.fecha fechPer,
            CONCAT(DAY(tempPer.fecha),'/',MONTH(tempPer.fecha)) AS dia,regcon.idfranco,franco.descripcion francodescr,ft.descripcion francotipo,regcon.idregistrohorario,idlicencia,
            tempPer.idcontacto codContacto,fecha_reg,latitud,longitud,foto,regcon.idcertificacion,dispositivo_desc,organismo_desc
            FROM (select periodo.fecha,idcontacto,concat(periodo.fecha,idcontacto) codigo,disp.descripcion dispositivo_desc,
            organismo.descripcion organismo_desc
            from sds_com_periodo periodo,mds_org_contacto contacto, mds_org_dispositivo disp, mds_org_organismo organismo
            where disp.iddispositivo = contacto.iddispositivo 
            and organismo.idorganismo=disp.idorganismo and
            (contacto.iddispositivo=" . $this->iddispositivo . "
            or contacto.idcontacto=" . $this->codContacto . "
            or (-1=" . $this->iddispositivo . " and 0<" . $this->idorganismo . "))
            and (disp.idorganismo =" . $this->idorganismo . "
            or -1=" . $this->idorganismo . ")
            and periodo.fecha BETWEEN '$desde' and '$hasta 23:59:00') tempPer            
            left join (SELECT periodo.periodo AS periodo, periodo.fecha AS fecha, NULL AS idfranco, NULL AS idcertificacion, 
            reg.idregistrohorario AS idregistrohorario, NULL AS idlicencia, reg.idcontacto AS codContacto, reg.fecha AS fecha_reg
            FROM sds_com_periodo periodo
            JOIN mds_hor_registro reg ON DATE_FORMAT(reg.fecha, '%Y-%m-%d') = periodo.fecha
            WHERE reg.idcontacto IS NOT NULL
            and reg.idcontacto=" . $this->codContacto . " 
            and reg.fecha BETWEEN '$desde' and '$hasta 23:59:00'
            UNION 
            SELECT periodo.periodo AS periodo, periodo.fecha AS fecha, franco.idfranco AS idfranco, NULL AS idcertificacion,
            NULL AS idregistrohorario, NULL AS idlicencia, franco.idcontacto AS codContacto, NULL AS fecha_reg
            FROM sds_com_periodo periodo
            LEFT JOIN mds_hor_franco franco ON franco.fecha = periodo.fecha
            WHERE franco.idcontacto IS NOT NULL
            and franco.idcontacto=" . $this->codContacto . " 
            and franco.fecha BETWEEN '$desde' and '$hasta 23:59:00'
            UNION 
            SELECT periodo.periodo AS periodo, periodo.fecha AS fecha, NULL AS idfranco, certificacion.idcertificacion AS idcertificacion,
            NULL AS idregistrohorario, NULL AS idlicencia, certificacion.certificado AS codContacto, NULL AS fecha_reg
            FROM sds_com_periodo periodo
            LEFT JOIN mds_hor_certificacion certificacion ON certificacion.periodo_mes = MONTH(periodo.fecha)
            AND certificacion.periodo_anio = YEAR(periodo.fecha)
            and certificacion.certificado=" . $this->codContacto . " 
            and periodo.fecha BETWEEN '$desde' and '$hasta 23:59:00'
            UNION 
            SELECT periodo.periodo AS periodo, periodo.fecha AS fecha, NULL AS idfranco, NULL AS idcertificacion, NULL AS idregistrohorario,
            licencia.idlicencia AS idlicencia, licencia.idcontacto AS codContacto, NULL AS fecha_reg
            FROM sds_com_periodo periodo
            LEFT JOIN mds_hor_licencia licencia ON licencia.desde <= periodo.fecha AND licencia.hasta >= periodo.fecha
            WHERE (0 <> licencia.idcontacto)
            and licencia.idcontacto=" . $this->codContacto . " 
            and periodo.fecha BETWEEN '$desde' and '$hasta 23:59:00'
            ORDER BY periodo , codContacto , fecha , fecha_reg) regcon ON concat(regcon.fecha,regcon.codContacto)=tempPer.codigo
            left join mds_hor_registro reg ON reg.idregistrohorario=regcon.idregistrohorario
            left join mds_hor_franco franco on regcon.idfranco = franco.idfranco
            left join sds_com_configuracion ft on ft.idconfiguracion = franco.tipo) temp "]);
            $query->leftJoin('view_contactos_personas contpers', 'temp.codContacto=contpers.idcontacto');
            $query->leftJoin('mds_org_padron padron', 'contpers.documento=padron.dni');
            $query->where('((contpers.eventual=' . $this->eventuales .
                ' and ' . $this->eventuales . '=1) or (contpers.planta_politica=1 and '
                . $this->eventuales . '=0) or ' . $this->eventuales . '=-1)');
            $query->groupBy('fecha, contpers.idcontacto');
            $query->having("estado= '" . $this->estado . "' or 
            (estado='Feriado' and 'Franco'='" . $this->estado . "') 
            or '-1'='" . $this->estado . "'");
            $query->orderBy(["fecha" => SORT_ASC, "codContacto" => SORT_ASC]);
        } else {
            \Yii::$app->db->createCommand("CALL mds_hor_inasistencias(:paramName1, :paramName2)")
                ->bindValue(':paramName1', $desde)
                ->bindValue(':paramName2', $hasta)
                ->execute();
            $query->addSelect([
                "temp.idcontacto AS codContacto",
                "group_concat(distinct concat(DAY(fecha),'/',MONTH(fecha)) order by fecha SEPARATOR ' | ' ) AS dia",
                "contpers.legajo",
                "concat(nombre,' ',apellido) empleado",
                "pad.pr pr_categoria"
            ]);
            $query->from(["t_inasistencias temp"]);
            $query->innerJoin('view_contactos_personas contpers', 'temp.idcontacto=contpers.idcontacto');
            $query->leftJoin('mds_org_dispositivo dispositivo', 'contpers.iddispositivo=dispositivo.iddispositivo');
            $query->leftJoin('(select padron.legajo,idunidadoperativa,categoria,apellido_nombre,
            sexo,dni,cuil,fecha_nacimiento,
            fecha_ingreso,antiguedad_administrativa,
            antiguedad_privada,antiguedad_total,eventual,pr,titulo 
            from mds_org_padron padron
            join (select mes,anio
            from mds_org_padron
            group by mes,anio
            order by anio desc,mes desc limit 1) temp
            on (temp.mes=padron.mes and temp.anio=padron.anio)) pad', 'contpers.legajo=pad.legajo');
            $query->where('DATEDIFF(fecha,curdate())<=0 and contpers.legajo is not null and contpers.legajo!=0 
            and contpers.ficha=1 and not contpers.retenido and contpers.activo=1 and
            ((contpers.fecha_ingreso is not null and contpers.fecha_ingreso<=temp.fecha) 
            or (contpers.fecha_ingreso_planta is not null and contpers.fecha_ingreso_planta<=temp.fecha))
            and ((contpers.eventual=' . $this->eventuales .' and ' . $this->eventuales .' =1) 
                or (contpers.planta_politica=1 and '. $this->eventuales . '=0) or ' . $this->eventuales . '=-1) 
            and (contpers.iddispositivo=' . $this->iddispositivo . ' or -1=' . $this->iddispositivo . ')            
            and (dispositivo.idorganismo =' . $this->idorganismo . ' or -1=' . $this->idorganismo . ')');
            $query->groupBy('contpers.idcontacto');
            $query->orderBy(["empleado" => SORT_ASC]);
        }

        return $dataProvider;
    }
}
