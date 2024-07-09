<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'bill_method_quantities_id',
        'subject',
        'invoiceId',
        'start_date',
        'due_date',
        'amount',
        'remaining',
        'discount',
        'user_id',
        'status_id',
        'invoice_status',
        'amount_paid',
    ];

    protected $dates = ['start_date', 'due_date'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function billMethodQuantity()
    {
        return $this->belongsTo(BillMethodQuantity::class, 'bill_method_quantities_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
