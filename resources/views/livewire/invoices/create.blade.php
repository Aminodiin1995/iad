<?php

use App\Models\Invoice;
use App\Models\BillMethod;
use App\Models\Student;
use App\Models\BillMethodQuantity;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use Toast;

    public Student $student;
    public $quantities;
    public string $newInvoiceSubject = '';
    public string $newInvoiceAmount = '';
    public string $start_date;
    public string $due_date;
    public $billMethods;
    public int $selectedBillMethodId;

    public function mount(Student $student)
    {
        $this->student = $student->load('billMethod', 'billMethodQuantities.invoices');
        $this->loadStudentData();
        $this->start_date = Carbon::now()->format('Y-m-d');
        $this->due_date = Carbon::now()->format('Y-m-d');
        $this->billMethods = BillMethod::all();
        $this->selectedBillMethodId = $this->student->billmethod_id ?? 0;
    }

    private function loadStudentData() {
        $this->quantities = $this->student->billMethodQuantities;
    }

    public function createNewInvoice()
    {
        $this->validate([
            'newInvoiceSubject' => 'required|string',
            'newInvoiceAmount' => 'required|numeric|min:0',
            'selectedBillMethodId' => 'required|exists:bill_methods,id',
        ]);

        $billMethod = BillMethod::find($this->selectedBillMethodId);
        if ($billMethod) {
            $billMethodQuantity = BillMethodQuantity::create([
                'bill_method_id' => $billMethod->id,
                'student_id' => $this->student->id,
                'quantity' => $this->quantities->count() + 1,
                'remaining' => $this->newInvoiceAmount,
                'amount' => $this->newInvoiceAmount,
                'status_id' => 1,
                'echeance' => $this->quantities->count() + 1,
            ]);

            Invoice::create([
                'student_id' => $this->student->id,
                'bill_method_quantities_id' => $billMethodQuantity->id,
                'subject' => $this->newInvoiceSubject,
                'invoiceId' => $this->generateUniqueInvoiceId(),
                'start_date' => $this->start_date,
                'due_date' => $this->due_date,
                'amount' => $this->newInvoiceAmount,
                'user_id' => Auth::id(),
                'status_id' => 1,
            ]);

            $this->loadStudentData();
            $this->show = false;
            $this->toast('New invoice created successfully.', 'success');
        } else {
            $this->toast('Error: Invalid billing method.', 'error');
        }
    }

    private function generateUniqueInvoiceId()
    {
        do {
            $invoiceId = mt_rand(1000, 9999999);
        } while (Invoice::where('invoiceId', $invoiceId)->exists());

        return $invoiceId;
    }

    public function with(): array
    {
        return [
            'student' => $this->student,
            'quantities' => $this->quantities,
            'billMethods' => $this->billMethods,
        ];
    }

};
?>
<div>

    
            <hr class="mb-5" />
            <x-form wire:submit.prevent="createNewInvoice">
                <x-input label="Subject" wire:model="newInvoiceSubject" required />
                <x-select label="Bill Method" wire:model="selectedBillMethodId" :options="$billMethods" option-label="name" option-value="id" placeholder="Select Bill Method" required />
                <x-input label="Amount" wire:model="newInvoiceAmount" type="number" required />

                <x-slot:actions>
                    <x-button label="Save" icon="o-paper-airplane" class="btn-primary" type="submit" />
                </x-slot:actions>
            </x-form>
      
</div>
