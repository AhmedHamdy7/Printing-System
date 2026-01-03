<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function generateInvoicePdf($invoice)
    {
        $invoice->load(['user', 'items.product']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function streamInvoicePdf($invoice)
    {
        $invoice->load(['user', 'items.product']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
