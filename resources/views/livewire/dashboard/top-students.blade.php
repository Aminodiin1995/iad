<?php

use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;

new class extends Component {
    #[Reactive]
    public string $period = '-30 days';

    public function topStudents(): Collection
    {
        return Student::query()
            ->with('invoices')
            ->selectRaw("sum(invoices.amount) as total_invoice_amount, students.*")
            ->join('invoices', 'students.id', '=', 'invoices.student_id')
            ->where('invoices.created_at', '>=', Carbon::parse($this->period)->startOfDay())
            ->groupBy('students.id', 'students.name', 'students.address', 'students.telephone', 'students.filiere_id', 'students.niveau_id', 'students.section_id', 'students.email', 'students.studentId', 'students.father_name', 'students.status_id', 'students.join_date', 'students.join_year', 'students.current_year', 'students.birth_date', 'students.total_amount', 'students.billmethod_id', 'students.amount_paid', 'students.created_at', 'students.updated_at')
            ->orderByDesc('total_invoice_amount')
            ->take(10)
            ->get()
            ->transform(function (Student $student) {
                $student->total_invoice_amount = $student->invoices->sum('amount');
                return $student;
            });
    }

    public function with(): array
    {
        return [
            'topStudents' => $this->topStudents(),
        ];
    }
};
?>

<div>
    <x-card title="Top Students" separator shadow>
        <x-slot:menu>
            <x-button label="Students" icon-right="o-arrow-right" link="/students" class="btn-ghost btn-sm" />
        </x-slot:menu>

        @foreach($topStudents as $student)
            <x-list-item :item="$student" sub-value="filiere.name" link="/students/{{ $student->id }}" no-separator>
                <x-slot:actions>
                    <x-badge :value="$student->total_invoice_amount" class="font-bold" />
                </x-slot:actions>
            </x-list-item>
        @endforeach
    </x-card>
</div>
