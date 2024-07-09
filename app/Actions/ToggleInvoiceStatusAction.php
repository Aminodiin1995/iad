<?php

namespace App\Actions;

use App\Models\Invoice;

class ToggleInvoiceStatusAction
{
    public function execute(Invoice $invoice)
    {
        $invoice->invoice_status = !$invoice->invoice_status; 
        $invoice->save();
    }
}
