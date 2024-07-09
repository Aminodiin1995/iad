<?php

namespace App\Traits;

use App\Models\Invoice;

trait CalculateRemainingTrait
{
    public function calculateRemaining($invoiceId, $paymentAmount)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            return $invoice->amount - $paymentAmount;
        }
        return 0;
    }
}
?>
