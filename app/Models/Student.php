<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'telephone',
        'filiere_id',
        'niveau_id',
        'section_id',
        'email',
        'studentId',
        'father_name',
        'status_id',
        'join_date',
        'join_year',
        'current_year',
        'birth_date',
        'total_amount',
        'billmethod_id',
        'amount_paid',
    ];

    public function billMethod()
    {
        return $this->belongsTo(BillMethod::class, 'billmethod_id');
    }

    public function billMethodQuantities()
    {
        return $this->hasMany(BillMethodQuantity::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, BillMethodQuantity::class, 'student_id', 'bill_method_quantities_id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
