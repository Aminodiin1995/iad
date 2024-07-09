<?php

namespace App\Traits;

use App\Models\Student;

trait CalculateTotalPaidTrait
{
    public function updateTotalPaid(Student $student)
    {
        $totalPaid = $student->invoices()->sum('amount_paid');
        $student->amount_paid = $totalPaid;
        $student->save();
    }
}
?>
