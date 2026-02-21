<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * Generar un PDF a partir de una vista.
     */
    public function generate(string $view, array $data, string $filename = null, bool $stream = false)
    {
        $pdf = Pdf::loadView($view, $data);

        if ($stream) {
            return $pdf->stream($filename ?? 'document.pdf');
        }

        return $pdf->download($filename ?? 'document.pdf');
    }

    /**
     * Guardar un PDF en el almacenamiento local.
     */
    public function save(string $view, array $data, string $path)
    {
        $pdf = Pdf::loadView($view, $data);
        Storage::put($path, $pdf->output());
        return $path;
    }
}
