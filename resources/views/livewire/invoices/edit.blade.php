<?php

use App\Models\BillMethod;
use App\Models\Status;
use App\Models\Student;
use App\Models\Invoice;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Actions\ToggleInvoiceStatusAction;


new class extends Component {
    use Toast;

    public Invoice $invoice;
    public $amount;
    public $discount;
    public $status_id;
    public $student_name;
    public $studentId;
    public $student_email;
    public $subject;
    public $start_date;
    public $due_date;
    public $dis;
    public $echeance;
    public bool $invoice_status;  // Directly bind to invoice_status



    protected $listeners = ['refreshComponent' => '$refresh'];


    protected $rules = [
        'amount' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'status_id' => 'required|exists:statuses,id',
        'subject' => 'required|string',
        'start_date' => 'required|date',
        'due_date' => 'required|date',
        'invoice_status' => 'boolean',

    ];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->amount = $invoice->amount;
        $this->discount = $invoice->discount;
        $this->status_id = $invoice->status_id;
        $this->student_name = $invoice->student->name;
        $this->studentId = $invoice->student->studentId;
        $this->student_email = $invoice->student->email;
        $this->subject = $invoice->subject;
        $this->start_date = $invoice->start_date;
        $this->due_date = $invoice->due_date;
        $this->echeance = $invoice->billMethodQuantity->echeance; 
        $this->dis = $invoice->amount*$invoice->discount/100;
        $this->invoice_status = (bool) $invoice->invoice_status; 


    }

    public function save()
    {
        $this->validate();

        $this->invoice->update([
            'amount' => $this->amount,
            'discount' => $this->discount,
            'status_id' => $this->status_id,
            'subject' => $this->subject,
            'start_date' => $this->start_date,
            'due_date' => $this->due_date,
            'invoice_status' => $this->invoice_status,

        ]);

        $this->toast('Invoice updated.', 'info', 'bottom-left', 'o-warning');

        return redirect()->route('invoices.edit', $this->invoice);
    }
    public function toggleInvoiceStatus()
    {
        $this->invoice->invoice_status = $this->checked ? 0 : 1;
        $this->invoice->save();
        $this->checked = !$this->checked; // Toggle the boolean state
        $this->emitSelf('refreshComponent');  // Refresh the component to reflect changes

        Session::flash('message', 'Invoice status updated successfully.');
    }



    public function with(): array
    {
        return [
            'statuses' => Status::all(),
        ];
    }
}; ?>

<div>
    <div class="p-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <h2 class="mb-4 text-2xl font-bold">Edit Invoice</h2>
                <form wire:submit.prevent="save">
                    
                    @php
                    $config1 = ['altFormat' => 'd/m/Y'];
                    @endphp
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Issue Date</label>
                        <x-datepicker type="date" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model="start_date" icon-right="o-calendar" :config="$config1" readonly />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Due Date</label>
                        <x-datepicker type="date" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model="due_date" icon-right="o-calendar" :config="$config1" readonly />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model="amount" required  readonly/>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Discount</label>
                        <input type="number" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model="discount" readonly />
                    </div>
                      
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Echeance</label>
                        <input type="number" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" wire:model="echeance" readonly/>
                    </div>
                   
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
                            @foreach($statuses as $status)
                                <option value="" >{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                  <!--  <div class="flex space-x-4">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Update Invoice</span>
                            <span wire:loading>Processing...</span>
                        </button>
                        <a href="/students" class="btn btn-secondary">Cancel</a>
                    </div>-->
                </form>
            </div>

            <div id="invoice-preview">
<hr>
                <div class="max-w-4xl p-8 mx-auto mt-10 bg-white rounded-lg shadow-md">
                    <header class="flex items-center justify-center mb-8">
                        <img src="/images/iad_new1.png" alt="Logo" class="h-20">
                    </header>
                    <div class="flex justify-center mb-8">
                        <div class="text-center">
                            <h1 class="text-4xl font-bold">Facture</h1>
                            <p class="font-extrabold text-gray-500">#{{ $invoice->invoiceId }}</p>
                        </div>
                    </div>
                    <div class="flex justify-between mb-8">
                        <div>
                            <div>
                                <h2 class="text-lg font-semibold">To:</h2>
                                <p>{{ $invoice->student->name }}, IAD, {{ $invoice->student->studentId }}.</p>
                                <p>{{ $invoice->student->email }}</p>
                                <p>{{ $invoice->student->filiere->name }}, {{ $invoice->student->niveau->name }}, {{ $invoice->student->section->name }}</p>
                            </div>
                        </div>
                        
                    </div>
                    <div class="flex justify-between mb-8">
                        <div>
                            <h2 class="text-lg font-semibold">Issued</h2>
                            <p>{{ \Carbon\Carbon::parse($invoice->start_date)->format('l, F j, Y') }}</p>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold">Due</h2>
                            <p>{{ \Carbon\Carbon::parse($invoice->due_date)->format('l, F j, Y') }}</p>
                        </div>
                    </div>
                    <table class="w-full mb-8">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-1 py-2 text-left">Description</th>
                                <th class="px-1 py-2 text-right">Discount</th>
                                <th class="px-1 py-2 text-right">Echance</th>
                                <th class="px-1 py-2 text-right">Fee Amount</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-1 py-2 border">{{ $invoice->subject }}</td>
                                <td class="px-1 py-2 text-right border"> {{ $discount }} </td>
                                 <td class="px-1 py-2 text-right border">{{ $invoice->billMethodQuantity->echeance }}</td>
                                 <td class="px-1 py-2 text-right border">DJF {{ $invoice->amount }} </td>
                                
                            </tr>
                        </tbody>
                    </table>
                
                    <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-lg font-semibold"></h2>
                            
                            </div>
                        
                        <div class="text-right">
                           
                            <p class="mt-2 text-2xl font-bold">Total Amount:  {{ $amount - $dis }} DJF</p>

                        </div>
                    </div>
                    <footer>
                        <button onclick="printInvoice()" class="btn btn-primary print-hide">Print</button>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    function printInvoice() {
        var printContents = document.getElementById('invoice-preview').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>