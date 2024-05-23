
<?php
require("fpdf/fpdf.php");

class PDF extends FPDF
    {
        // Page header
        function Header()
            {
                // Logo
                $this->Image('../Lib/Imagenes/Membrete2018.png',50,10,110,20);
                // Marca de Agua
                //$this->Image('../Lib/Imagenes/fondo.jpg',57,100,100,100);
            }

        // Page footer
        function Footer()
            {
                // Position at 1.5 cm from bottom
                $this->SetY(-15);
                 //$this->SetX(-15);
                // Arial italic 8
                $this->SetFont('Arial','',7);
                $this->Cell(0,4,utf8_decode('Subsecretaría de Desarrollo Social | Provincia del Neuquén '),0,0,'C');
                $this->Ln();
                $this->SetFont('Arial','',7);
                $this->Cell(0,4,'Teodoro Planas y Gobernador Anaya (8300) | Tel.:(0299) 4493800',0,0,'C');
                $this->Ln();
                $this->SetFont('Arial','',7);
                $this->Cell(0,4,'www.social.neuquen.gob.ar ',0,0,'C');


                // Page number
                //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            }
    }
?>
