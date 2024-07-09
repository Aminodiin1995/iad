<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\BillMethod;
use App\Models\BillMethodQuantity;
use App\Models\Invoice;
use App\Models\Status;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Payment;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'father_name' => $this->faker->name,
            'address' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber,
            'filiere_id' => $this->faker->numberBetween(1, 4),
            'niveau_id' => $this->faker->numberBetween(1, 3),
            'section_id' => $this->faker->numberBetween(1, 3),
            'email' => $this->faker->unique()->safeEmail,
            'studentId' => $this->faker->unique()->numerify('S####'),
            'join_date' => Carbon::now()->format('Y-m-d'),
            'join_year' => Carbon::now()->year,
            'current_year' => Carbon::now()->year,
            'birth_date' => $this->faker->date(),
            'total_amount' => 350000,
            'billmethod_id' => $this->faker->numberBetween(1, 5),
            'status_id' => 1,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Student $student) {
            $billMethod = $student->billMethod;
            $issueDates = $this->generateIssueDates($billMethod);
            $invoiceAmount = $student->total_amount / count($issueDates);

            foreach ($issueDates as $index => $dates) {
                $billMethodQuantity = BillMethodQuantity::create([
                    'bill_method_id' => $billMethod->id,
                    'student_id' => $student->id,
                    'quantity' => $index + 1,
                    'remaining' => 0,
                    'amount' => $invoiceAmount,
                    'status_id' => 1,
                    'echeance' => $index + 1,
                ]);

                $invoice = Invoice::create([
                    'student_id' => $student->id,
                    'bill_method_quantities_id' => $billMethodQuantity->id,
                    'subject' => 'frais scolaire',
                    'invoiceId' => $this->generateUniqueInvoiceId(),
                    'start_date' => $dates['start_date'],
                    'due_date' => $dates['due_date'],
                    'amount' => $invoiceAmount,
                    'user_id' => 1,
                    'status_id' => 1,
                    'status_id' => 1,
                    'created_at' => $this->faker->dateTimeBetween('2024-05-01 00:00:00', '2024-06-07 23:59:59'),
                    'updated_at' => $this->faker->dateTimeBetween('2024-05-01 00:00:00', '2024-06-07 23:59:59'),
                ]);
                Payment::factory()->create([
                    'invoice_id' => $invoice->id,
                    'amount' => $invoiceAmount,
                ]);
            }
        });
    }

    private function generateUniqueInvoiceId()
    {
        do {
            $invoiceId = mt_rand(1000, 9999999);
        } while (Invoice::where('invoiceId', $invoiceId)->exists());

        return $invoiceId;
    }

    private function generateIssueDates(BillMethod $billMethod): array
    {
        $dates = [];
        $startDate = Carbon::now();

        if ($billMethod->id == 1) { 
            for ($i = 0; $i < 10; $i++) {
                $start = $startDate->copy()->addMonths($i)->format('Y-m-d');
                $due = $startDate->copy()->addMonths($i + 1)->subDay()->format('Y-m-d');
                $dates[] = ['start_date' => $start, 'due_date' => $due];
            }
        } elseif ($billMethod->id == 2) { 
            for ($i = 0; $i < 4; $i++) {
                $start = $startDate->copy()->addMonths($i * 5)->format('Y-m-d');
                $due = $startDate->copy()->addMonths(($i + 1) * 5)->subDay()->format('Y-m-d');
                $dates[] = ['start_date' => $start, 'due_date' => $due];
            }
        } elseif ($billMethod->id == 3) { 
            for ($i = 0; $i < 3; $i++) {
                $start = $startDate->copy()->addMonths($i * 3)->format('Y-m-d');
                $due = $startDate->copy()->addMonths(($i + 1) * 3)->subDay()->format('Y-m-d');
                $dates[] = ['start_date' => $start, 'due_date' => $due];
            }
        } elseif ($billMethod->id == 4) { 
            for ($i = 0; $i < 2; $i++) {
                $start = $startDate->copy()->addMonths($i * 6)->format('Y-m-d');
                $due = $startDate->copy()->addMonths(($i + 1) * 6)->subDay()->format('Y-m-d');
                $dates[] = ['start_date' => $start, 'due_date' => $due];
            }
        } elseif ($billMethod->id == 5) {
            $start = $startDate->format('Y-m-d');
            $due = $startDate->copy()->addYear()->subDay()->format('Y-m-d');
            $dates[] = ['start_date' => $start, 'due_date' => $due];
        }

        return $dates;
    }
}