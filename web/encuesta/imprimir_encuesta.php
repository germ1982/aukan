<?php
$dirBase = "";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
require_once '../../vendor/autoload.php';
$id_encuesta = $_REQUEST['id_encuesta'];
$id_tipo_encuesta = $_REQUEST['id_tipo'];
$bd = new BaseDatos();
$bd->Conectarse();

$bd->select("SELECT * FROM mds_encuesta_tipo WHERE id_tipo = ".$id_tipo_encuesta);
$tipo_encuesta = $bd->registro();

$mpdfConfig = array(
                'mode' => 'utf-8', 
                'format' => 'A4',    // format - A4, for example, default ''
                //'margin_footer' => 0,     // 9 margin footer
                'orientation' => 'P',  	// L - landscape, P - portrait.
                'tempDir' => 'tmp',
                'setAutoBottomMargin' => 'stretch',
                'setAutoTopMargin' => 'stretch'
        );
$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->enableImports = true;
//$mpdf->SetImportUse(); // only with mPDF <8.0

$pagecount = $mpdf->SetSourceFile('impresion_template.pdf');
$tplId = $mpdf->ImportPage($pagecount);
$mpdf->SetPageTemplate($tplId);

// Do not add page until page template set, as it is inserted at the start of each page
$mpdf->AddPage();
$mpdf->WriteHTML("<div style='color:#fff;font-family:Calibri;font-size:40px;text-align:center;padding-top:49%'>".mb_strtoupper($tipo_encuesta['nombre'])."<p style='font-size:30px;'>".ucfirst($tipo_encuesta['descripcion'])."</p></div>");

$bd->select("SELECT mds_encuesta_resultado.*,mds_encuesta_seccion.seccion,mds_encuesta_pregunta.pregunta FROM mds_encuesta_resultado JOIN mds_encuesta_seccion USING (id_seccion) JOIN mds_encuesta_pregunta USING (id_pregunta) WHERE id_encuesta = ".$id_encuesta." AND mds_encuesta_seccion.baja_fecha IS NULL AND mds_encuesta_pregunta.baja_fecha IS NULL ORDER BY mds_encuesta_seccion.orden,mds_encuesta_pregunta.orden");

$id_seccion = '';
$i = 0;
while ($resultado = $bd->registro()){
    if ($id_seccion == '' || $id_seccion != $resultado['id_seccion']){
        if ($resultado['valor'] != ''){
            if ($id_seccion == '')
                $id_seccion = $resultado['id_seccion'];
            else
                $id_seccion = $resultado['id_seccion'];        
            //agrego el titulo
            $mpdf->SetPageTemplate("");
            $mpdf->AddPage();
            $html = '<htmlpageheader name="MyHeader1">
                <div style="text-align: right;"><img src="../img/uploads_encuesta/'.$tipo_encuesta['header'].'" /><hr>
            </div>
            </htmlpageheader>

            <htmlpagefooter name="MyFooter1" >
                <div style="text-align: center;" width="100%;padding-left:0;padding-right:0;margin-left:0;margin-right:0"><img src="../img/uploads_encuesta/'.$tipo_encuesta['footer'].'" /></div>
            </htmlpagefooter>

            <sethtmlpageheader name="MyHeader1" value="on" show-this-page="1" />
            <sethtmlpagefooter name="MyFooter1" value="on" />';
            if ($i == 0)
                $br = "<br><br><br><br><br>";
            else
                $br = "";
            $html .=$br."<h1 style='color:#337ab7;font-family:Arial;font-size:14px;font:normal'>".mb_strtoupper($resultado['seccion'])."</h3>";
            $mpdf->WriteHTML($html);
            $i++;
        }
    }
    if ($resultado['valor'] != ''){
        $html = '<p style="font-family:Arial;font-size:12px;"><strong>'.$resultado['pregunta'].': </strong>'.$resultado['valor'].'</p>';
        $mpdf->WriteHTML($html);
    }
    
}


$mpdf->Output();

?>