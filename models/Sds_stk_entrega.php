<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_entrega".
 *
 * @property int $identrega
 * @property string $fecha_hora
 * @property int $organismo
 *
 * @property MdsOrgOrganismo $organismo0
 * @property SdsStkEntregaItem[] $sdsStkEntregaItems
 */
class Sds_stk_entrega extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $fdesde;
    public $fhasta;
    public $hora;
    public $responsable;
    //para reporte entrega
    public $idarticulos;
    public $articulos;
    public $cantidades;
    // destinatario
    public $documento_tipo;
    public $documento;
    public $nombre;
    public $apellido;
    public $fecha_nacimiento;
    public $genero;
    public $nacionalidad;
    public $calle;
    public $numero_calle;
    public $localidad;
     // retira
     public $documento_tipo_retira;
     public $documento_retira;
     public $nombre_retira;
     public $apellido_retira;
     public $fecha_nacimiento_retira;
     public $genero_retira;
     public $nacionalidad_retira;
     public $calle_retira;
     public $numero_calle_retira;
     public $localidad_retira;

    public $detalle_items;
    public $mostrar;
    public $cantidad_total;
    public $idocitems;
    public $cantidades_oc;
    public $ordenes;
    public $temp_archivo_adjunto_entrega;

    public static function tableName()
    {
        return 'sds_stk_entrega';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'organismo', 'idcontacto', 'idpersona', 'fecha_nacimiento', 'documento_tipo', 'documento', 'nombre', 'apellido', 'genero', 'nacionalidad'
            , 'fecha_nacimiento_retira', 'documento_tipo_retira', 'documento_retira', 'nombre_retira', 'apellido_retira', 'genero_retira', 'nacionalidad_retira'], 'required'],
            [['fecha_hora', 'fdesde', 'fhasta', 'hora', 'documento_tipo', 'fecha_nacimiento','cantidad_total','cantidades_oc','idocitems'], 'safe'],
            [['organismo', 'idcontacto', 'documento_tipo', 'documento', 'genero', 'nacionalidad', 'acta_original', 'persona_retira', 'contacto_entrega','referente','mostrar','es_organizacion_social','organizacion_social'], 'integer'],
            [['nombre', 'apellido', 'observaciones', 'detalle_items','responsable','idarticulos','articulos','cantidades','ordenes','adjunto_acta_entrega'], 'string'],
            [['organismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['organismo' => 'idorganismo']],
            [['temp_archivo_adjunto_entrega'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'identrega' => 'Identrega',
            'fecha_hora' => 'Fecha Hora',
            'acta_original' => 'Acta Completa',
            'organismo' => 'Organismo',
            'referente' => 'Entrega a referente'
        ];
    }

    /**
     * Gets query for [[Organismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismo0()
    {
        return $this->hasOne(Mds_org_organismo::className(), ['idorganismo' => 'organismo']);
    }

    /**
     * Gets query for [[SdsStkEntregaItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsStkEntregaItems()
    {
        return $this->hasMany(Sds_stk_entrega_item::className(), ['identrega' => 'identrega']);
    }

    public static function getExtension($file)
    {
        $array = explode(".", $file);
        $extension = end($array);
        $extImagenes = array('jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx');
        if (in_array($extension, $extImagenes)) {
            return 'image';
        } else {
            return $extension;
        }
    }
}
