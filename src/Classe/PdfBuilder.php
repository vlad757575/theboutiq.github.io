<?php

namespace App\Classe;

use App\Repository\CommandeRepository;
use Dompdf\Dompdf;
use Dompdf\Options;


class PdfBuilder
{
    private $domPdf;

    public function __construct()
    {
    }

    public function ShowPdf($html)
    {


        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream("facture.pdf", [
            'attachement' => true
        ]);
    }
}
