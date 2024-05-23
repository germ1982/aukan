<?php
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
    // get the HTML
    
    ob_start();
    $a =  $_REQUEST['include'];
   // echo "'".$a.'.pdf';
    include("$a");
//     echo $a;
    $content = ob_get_clean();
    if ($_REQUEST['orientacion'] != '')
    	$orientacion = $_REQUEST['orientacion'];
    else 
        $orientacion = 'P';     	
    // convert in PDF
    require_once('../../html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF($orientacion, 'A4', 'fr');
//      $html2pdf->setModeDebug();
        $html2pdf->setDefaultFont('dejavusans');
        //$html2pdf->parsingCss->convertToMM('8pt');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output("name.pdf");
        
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }