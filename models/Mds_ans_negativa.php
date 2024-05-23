<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_ans_negativa".
 *
 * @property float|null $cuit
 * @property string|null $nombre
 * @property int|null $periodo
 * @property string|null $fallecido
 * @property int|null $fecha_fallecido
 * @property string|null $trabajador_dependiente
 * @property string|null $autonomo
 * @property string|null $monotributista
 * @property string|null $ddjprovincial
 * @property string|null $casas_particulares
 * @property string|null $efectores_sociales
 * @property string|null $jubilado_pensionado
 * @property string|null $previsional_provincia
 * @property string|null $previsional_tramite
 * @property string|null $desempleo
 * @property string|null $programa_empleo
 * @property string|null $os_vigente
 * @property string|null $asignacion_familiar
 * @property string|null $auh
 * @property string|null $cuota_beca_progresar
 * @property string|null $beca_progresar
 * @property string|null $maternidad_casasparticulares
 * @property string|null $asignacion_familiar_jubilados
 * @property string|null $pnc
 * @property string|null $iniciacion_pnc
 * @property string|null $aaff_discontinuos
 * @property int $idnegativa
 */
class Mds_ans_negativa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_ans_negativa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cuit','dni'], 'string','max'=>50],
            [['periodo', 'fecha_fallecido'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['fallecido', 'trabajador_dependiente', 'autonomo', 'monotributista', 'ddjprovincial', 'casas_particulares', 'efectores_sociales', 
            'jubilado_pensionado', 'previsional_provincia', 'previsional_tramite', 'desempleo', 'programa_empleo', 'os_vigente', 'asignacion_familiar', 
            'auh', 'cuota_beca_progresar', 'beca_progresar', 'maternidad_casasparticulares', 'asignacion_familiar_jubilados', 'pnc', 'iniciacion_pnc',
             'aaff_discontinuos'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cuit' => 'CUIL',
            'dni' => 'DNI',
            'nombre' => 'Nombre',
            'periodo' => 'Período',
            'fallecido' => 'Fallecido',
            'fecha_fallecido' => 'Fecha Fallecido',
            'trabajador_dependiente' => 'Trabajador Dependiente',
            'autonomo' => 'Autónomo',
            'monotributista' => 'Monotributista',
            'ddjprovincial' => 'Ddj Provincial',
            'casas_particulares' => 'Casas Particulares',
            'efectores_sociales' => 'Efectores Sociales',
            'jubilado_pensionado' => 'Jubilado Pensionado',
            'previsional_provincia' => 'Previsional Provincia',
            'previsional_tramite' => 'Previsional Trámite',
            'desempleo' => 'Desempleo',
            'programa_empleo' => 'Programa Empleo',
            'os_vigente' => 'OS Vigente',
            'asignacion_familiar' => 'Asignacion Familiar',
            'auh' => 'AUH',
            'cuota_beca_progresar' => 'Cuota Beca Progresar',
            'beca_progresar' => 'Beca Progresar',
            'maternidad_casasparticulares' => 'Maternidad Casas Particulares',
            'asignacion_familiar_jubilados' => 'Asignacion Familiar Jubilados',
            'pnc' => 'PNC',
            'iniciacion_pnc' => 'Iniciación PNC',
            'aaff_discontinuos' => 'AAFF Discontinuos',
        ];
    }
}
