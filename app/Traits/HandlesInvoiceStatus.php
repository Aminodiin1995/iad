<?php

namespace App\Traits;

use App\Models\Invoice;

trait HandlesInvoiceStatus
{
    public function toggleInvoiceStatus(Invoice $invoice)
    {
        $invoice->invoice_status = !$invoice->invoice_status;
        $invoice->save();
    }
}
