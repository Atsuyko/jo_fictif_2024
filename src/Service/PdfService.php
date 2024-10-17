<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
  private $dompdf;

  public function __construct()
  {
    $this->dompdf = new Dompdf();
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $this->dompdf->setOptions($options);
  }

  public function generatePdfFile($html)
  {
    $this->dompdf->loadHtml($html);
    $this->dompdf->render();
    $this->dompdf->stream('E-Billet.pdf', [
      'Attachement' => false
    ]);
  }
}
