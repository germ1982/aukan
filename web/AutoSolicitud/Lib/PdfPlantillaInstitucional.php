
<?php
require("fpdf/fpdf.php");

class PDF extends FPDF
    {
        // Page header
        function Header()
            {
                // Logo
                $this->Image('../Lib/Imagenes/Membrete2017.png',10,10,190,20);
                // Marca de Agua
                $this->Image('../Lib/Imagenes/fondo.jpg',57,100,100,100);
            }

        // Page footer
        function Footer()
            {
                // Position at 1.5 cm from bottom
                $this->SetY(-15);
                 //$this->SetX(-15);
                // Arial italic 8
                $this->SetFont('Arial','B',7);
                $this->Cell(70,6,'GOBIERNO DE LA PROVINCIA DEL NEUQUEN -',0,0,'R');
                $this->SetFont('Arial','I',7);
                $this->Cell(0,6,utf8_decode('Subsecretaria de Desarrollo Social - Dir. Gral. De Informática y Comunicaciones'),0,0,'L');
                $this->Ln();
                $this->SetFont('Arial','',7);
                $this->Cell(0,4,'Planas y Anaya (8300)-Tel.: 4493889',0,0,'C');
                
                // Page number
                //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            }
    }
?>