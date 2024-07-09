<?php

use App\Models\Student;
use App\Models\BillMethodQuantity;
use App\Models\Invoice;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use WithPagination, Toast;

    public Student $student;
    public $quantities;
    public string $newInvoiceSubject = '';
    public string $newInvoiceAmount = '';

    public function mount(Student $student)
    {
        $this->student = $student;
        $this->loadStudentData();
    }
    
    private function loadStudentData() {
        $this->student->load('billMethod', 'billMethodQuantities.invoices.status');
        $this->quantities = $this->student->billMethodQuantities;
    }
   
    public function deleteQuantityAndInvoices($quantityId)
    {
        $quantity = BillMethodQuantity::findOrFail($quantityId);
        
        foreach ($quantity->invoices as $invoice) {
            $invoice->delete();
        }
        
        $quantity->delete();

        // Update the amount paid by the student
        $this->updateStudentAmountPaid();

        $this->toast('Quantity and related invoices deleted successfully.', 'invoice deleted successfully.', 'success', 'o-warning');
        $this->loadStudentData(); 
    }

    private function updateStudentAmountPaid()
    {
        $totalAmountPaid = $this->student->billMethodQuantities->pluck('invoices')->flatten()->sum('amount_paid');
        $this->student->amount_paid = $totalAmountPaid;
        $this->student->save();
    }

    public function with(): array
    {
        return [
            'student' => $this->student,
            'quantities' => $this->quantities,
        ];
    }

    protected $listeners = ['invoice-saved' => '$refresh'];
};
?>
<div>
    <main class="">
        <div class="p-6 mx-auto bg-white rounded-lg shadow-lg">
            <hr>
            <div class="pt-4 mb-10">
                <p class="text-lg font-bold">Detail</p>
                <div class="flex items-center justify-between">
                    <div>
                        <p>Student ID: <span class="font-semibold">{{ $student->studentId }}</span></p>
                        <p>Student Name: <span class="font-semibold">{{ $student->name }}</span></p>
                        <p>{{ $student->join_date }}</p>
                        <p>{{ $student->address }}</p>
                    </div>
                    <div>
                        <p>Amount : <span class="font-semibold">{{ $student->total_amount }} DJF</span></p>
                        <p>Amount Paid : <span class="font-semibold {{ $student->total_amount > $student->amount_paid ? 'text-red-500' : '' }}">{{ $student->amount_paid }} DJF</span></p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="mb-4">
                <p class="text-lg font-bold">Engagment <span class="text-green-600"> {{ $student->billMethod->name }} </span></p>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="text-sm leading-normal text-gray-600 uppercase bg-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-center">Invoice_No</th>
                                <th class="px-4 py-3 text-center">Echeance</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Total Amt</th>
                                <th class="px-4 py-3 text-center">Amount Paid</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-light text-gray-600">
                            @forelse ($quantities as $index => $quantity)
                                @foreach ($quantity->invoices as $invoice)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="px-4 py-3 text-left">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-bold text-center text-green-600 underline">
                                            <a href="">{{ $invoice->invoiceId }}</a>
                                        </td>
                                        <td class="px-4 py-3 text-center"><span class="font-semibold text-center">{{ $index + 1 }}/{{ $quantities->count() }}</span></td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="font-semibold text-center 
                                                @if($invoice->status_id == 1) text-red-500 
                                                @elseif($invoice->status_id == 2) text-orange-500 
                                                @elseif($invoice->status_id == 3) text-green-500 
                                                @endif">
                                                {{ $invoice->status->name ?? 'Not_Paid' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center"><span class="font-semibold text-center">{{ $quantity->amount }} DJF</span></td>
                                        <td class="px-4 py-3 text-center"><span class="font-semibold text-center">{{ $invoice->amount_paid }} DJF</span></td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="font-semibold">
                                                <x-button link="/invoices/{{ $invoice->id }}/edit" icon="o-pencil" responsive />
                                                <x-button wire:click="deleteQuantityAndInvoices({{ $quantity->id }})" icon="o-trash" class="btn-error" responsive />
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-3 text-center">No Invoice for this bill method.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-button label="Create invoice" icon="o-plus" link="{{ route('invoices.create', ['student' => $student->id]) }}" class="btn-primary" responsive />
            </div>
        </div>
    </main>
</div>
