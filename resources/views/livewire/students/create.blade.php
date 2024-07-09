<?php
use App\Models\Student;
use App\Models\BillMethod;
use App\Models\BillMethodQuantity;
use App\Models\Status;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Section;
use App\Models\Invoice;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use Toast;

    public string $name = '';
    public string $father_name = '';
    public string $address = '';
    public string $telephone = '';
    public int $filiere_id;
    public int $niveau_id;
    public int $section_id;
    public string $email = '';
    public string $join_date = '';
    public string $join_year = '';
    public string $current_year = '';
    public string $birth_date = '';
    public int $billmethod_id;
    public int $status_id = 1;

    public function mount(): void
    {
        $this->join_date = Carbon::now()->format('d/m/Y');
        $this->join_year = Carbon::now()->format('Y');
        $this->current_year = Carbon::now()->format('Y');
    }

    protected $rules = [
        'name' => 'required|string',
        'father_name' => 'required|string',
        'address' => 'required|string',
        'telephone' => 'required|string',
        'filiere_id' => 'required|integer|exists:filieres,id',
        'niveau_id' => 'required|integer|exists:niveaux,id',
        'section_id' => 'required|integer|exists:sections,id',
        'email' => 'required|email',
        'join_date' => 'required|date_format:d/m/Y',
        'join_year' => 'required|date_format:Y',
        'current_year' => 'required|date_format:Y',
        'birth_date' => 'required|date',
        'billmethod_id' => 'required|integer|exists:bill_methods,id',
        'status_id' => 'required|integer|exists:statuses,id',
    ];

    public function save(): void
    {
        $this->validate();

        $studentId = $this->generateUniqueStudentId();

        $student = Student::create([
            'name' => $this->name,
            'father_name' => $this->father_name,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'filiere_id' => $this->filiere_id,
            'niveau_id' => $this->niveau_id,
            'section_id' => $this->section_id,
            'email' => $this->email,
            'studentId' => $studentId,
            'join_date' => Carbon::createFromFormat('d/m/Y', $this->join_date)->format('Y-m-d'),
            'join_year' => $this->join_year,
            'current_year' => Carbon::now()->format('Y'),
            'birth_date' => $this->birth_date,
            'total_amount' => 350000,
            'billmethod_id' => $this->billmethod_id,
            'status_id' => $this->status_id,
        ]);

        $billMethod = BillMethod::find($this->billmethod_id);
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

            Invoice::create([
                'student_id' => $student->id,
                'bill_method_quantities_id' => $billMethodQuantity->id,
                'subject' => 'frais scolaire',
                'invoiceId' => $this->generateUniqueInvoiceId(),
                'start_date' => $dates['start_date'],
                'due_date' => $dates['due_date'],
                'amount' => $invoiceAmount,
                'user_id' => Auth::id(),
                'status_id' => $this->status_id,
            ]);
        }

        $this->success('Student and invoices created successfully.', redirectTo: '/students');
    }

    private function generateUniqueStudentId()
    {
        do {
            $studentId = 'IAD' . mt_rand(1000, 9999);
        } while (Student::where('studentId', $studentId)->exists());

        return $studentId;
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
        for ($i = 0; $i < 5; $i++) {
            $start = $startDate->copy()->addMonths($i * 3)->format('Y-m-d');
            $due = $startDate->copy()->addMonths(($i + 1) * 3)->subDay()->format('Y-m-d');
            $dates[] = ['start_date' => $start, 'due_date' => $due];
        }
    } elseif ($billMethod->id == 3) { 
        for ($i = 0; $i < 3; $i++) {
            $start = $startDate->copy()->addMonths($i * 4)->format('Y-m-d');
            $due = $startDate->copy()->addMonths(($i + 1) * 4)->subDay()->format('Y-m-d');
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


    public function with(): array
    {
        return [
            'billMethods' => BillMethod::all(),
            'statuses' => Status::all(),
            'filieres' => Filiere::all(),
            'niveaux' => Niveau::all(),
            'sections' => Section::all(),
        ];
    }
};
?>

<div>
    <x-header title="New Student" separator progress-indicator />

    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <x-form wire:submit.prevent="save">
                <x-input label="Name" name="name" wire:model="name" />
                <x-input label="father name" name="father_name" wire:model="father_name" />
                <x-input label="Address" name="address" wire:model="address" />
                @php
                $config1 = ['altFormat' => 'd/m/Y'];
                @endphp
                <x-datepicker label="Date of Birth" wire:model="birth_date" name="birth_date" icon-right="o-calendar" :config="$config1" />
               
                <x-input label="Phone" name="phone" wire:model="telephone" />
                <x-input label="Email" name="email" wire:model="email" />
                <x-select label="Bill Method" name="billmethod_id" wire:model="billmethod_id" :options="$billMethods" option-label="name" option-value="id" placeholder="---" />
                <x-select label="Filiere" name="filiere_id" wire:model="filiere_id" :options="$filieres" option-label="name" option-value="id" placeholder="---" />
                <x-select label="Niveau" name="niveau_id" wire:model="niveau_id" :options="$niveaux" option-label="name" option-value="id" placeholder="---" />
                <x-select label="Section" name="section_id" wire:model="section_id" :options="$sections" option-label="name" option-value="id" placeholder="---" />
                <div class="mt-4">
                    <x-button label="Cancel" link="/students" />
                    <x-button label="Create" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
                </div>
            </x-form>
        </div>
        <div>
            <img src="/images/edit-form.png" width="300" class="mx-auto" />
        </div>
    </div>
</div>
