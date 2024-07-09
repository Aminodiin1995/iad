<?php

use App\Models\Payment;
use App\Models\Status;
use App\Models\Invoice;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Mail;
use App\Traits\CalculateRemainingTrait;
use App\Traits\CalculateTotalPaidTrait;

new class extends Component
{
    use Toast, CalculateRemainingTrait, CalculateTotalPaidTrait;

    public $paymentId;
    public $selectedInvoiceId;
    public $paymentType;
    public $amount = 0; // Default amount
    public $status_id;
    public $remaining = 0; // Default remaining
    public array $invoices = [];
    public array $paymentTypes;
    public $chequeNumber; // Add cheque number field
    public $virementDetails; // Add virement details field
    public $showAmountInput = false;
    public $payment;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Payment $payment = null): void
    {
        if ($payment) {
            $this->payment = $payment->load('status', 'invoice'); // Ensure relationships are loaded
            $this->paymentId = $payment->id;
            $this->selectedInvoiceId = $payment->invoice_id;
            $this->paymentType = $payment->payment_type;
            $this->amount = $payment->amount;
            $this->remaining = $payment->remaining;
            $this->status_id = $payment->status_id;
            $this->chequeNumber = $payment->chequeNumber;
            $this->virementDetails = $payment->virementDetails;
            
        }

        $this->invoices = Invoice::select('id', 'invoiceId as label', 'subject', 'discount', 'amount', 'start_date', 'due_date', 'student_id')->get()->toArray();
        $this->paymentTypes = [
            ['id' => 'cash', 'name' => 'Cash'],
            ['id' => 'D-Money', 'name' => 'D-Money'],
            ['id' => 'waafi', 'name' => 'Waafi'],
            ['id' => 'cac', 'name' => 'CAC'],
            ['id' => 'cheque', 'name' => 'Cheque'],
            ['id' => 'virement', 'name' => 'Virement'],
        ];

        if ($this->selectedInvoiceId) {
            $this->updateAmount($this->selectedInvoiceId);
        }
        $this->showAmountInput = in_array($this->status_id, [2, 3]);
    }

    protected $rules = [
        'selectedInvoiceId' => 'required|exists:invoices,id',
        'paymentType' => 'required|in:cash,D-Money,waafi,cac,cheque,virement',
        'amount' => 'required|numeric|min:0.01',
        'status_id' => 'required|exists:statuses,id',
        'chequeNumber' => 'required_if:paymentType,cheque|string|nullable',
        'virementDetails' => 'required_if:paymentType,virement|string|nullable',
    ];

    public function save(): void
    {
        $this->validate();

        $remaining = $this->calculateRemaining($this->selectedInvoiceId, $this->amount);

        $paymentData = [
            'invoice_id' => $this->selectedInvoiceId,
            'payment_type' => $this->paymentType,
            'amount' => $this->amount,
            'remaining' => $remaining,
            'status_id' => $this->status_id,
            'chequeNumber' => $this->chequeNumber,
            'virementDetails' => $this->virementDetails,
        ];

        if ($this->paymentType === 'cheque') {
            $paymentData['cheque_number'] = $this->chequeNumber;
        } elseif ($this->paymentType === 'virement') {
            $paymentData['virement_details'] = $this->virementDetails;
        }

        if ($this->payment) {
            $this->payment->update($paymentData);
        } else {
            $this->payment = Payment::create($paymentData);
        }

        $this->updateInvoice($this->selectedInvoiceId, $this->amount, $this->status_id);

        $invoice = Invoice::find($this->selectedInvoiceId);
        if ($invoice) {
            $this->updateTotalPaid($invoice->student);
        }

        $this->toast('Payment saved successfully.', 'success');
    }

    public function changeStatus()
    {
        $this->payment->update(['status_id' => $this->status_id]);

        if ($this->status_id == 3) {
            $invoice = Invoice::find($this->selectedInvoiceId);
            if ($invoice) {
                $this->amount = $invoice->amount;
                $this->remaining = $this->calculateRemaining($this->selectedInvoiceId, $this->amount);
                $this->payment->update([
                    'amount' => $this->amount,
                    'remaining' => $this->remaining,
                ]);
            }
        }

        $this->updateInvoiceStatus($this->selectedInvoiceId, $this->status_id);

        $invoice = Invoice::find($this->selectedInvoiceId);
        if ($invoice) {
            $this->updateTotalPaid($invoice->student);
        }

        $this->toast(
            type: 'warning',
            title: 'Status Updated!',
            description: null,
            position: 'toast-bottom toast-end',
            icon: 'o-information-circle',
            css: 'alert-warning',
            timeout: 3000,
            redirectTo: null
        );
    }

    public function changePaymentType()
    {
        if ($this->paymentType === 'cheque' || $this->paymentType === 'virement') {
            $this->showAmountInput = true;
        } else {
            $this->showAmountInput = false;
        }
    }

    public function changeTheAmount()
    {
        $remaining = $this->calculateRemaining($this->selectedInvoiceId, $this->amount);

        $this->payment->update(['amount' => $this->amount, 'remaining' => $remaining]);

        $this->updateInvoice($this->selectedInvoiceId, $this->amount, $this->status_id);

        $invoice = Invoice::find($this->selectedInvoiceId);
        if ($invoice) {
            $this->updateTotalPaid($invoice->student);
        }

        $this->toast(
            type: 'warning',
            title: 'Amount Updated!',
            description: null,
            position: 'toast-bottom toast-end',
            icon: 'o-information-circle',
            css: 'alert-warning',
            timeout: 3000,
            redirectTo: null
        );
    }

    public function updatedStatusId($value)
    {
        $this->changeStatus($value);
        $this->showAmountInput = in_array($value, [2]);  
    }

    public function updatedPaymentType($value)
    {
        $this->changePaymentType($value);
    }

    private function updateAmount($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            $this->amount = $invoice->amount_paid;
            $this->remaining = $this->calculateRemaining($invoiceId, $this->amount);
        } else {
            $this->amount = 0;
            $this->remaining = 0;
        }
    }

    private function updateInvoice($invoiceId, $paymentAmount, $statusId)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            $invoice->amount_paid = $paymentAmount;
            $invoice->remaining = $invoice->amount - $paymentAmount;
            $invoice->status_id = $statusId;
            $invoice->save();
        }
    }

    private function updateInvoiceStatus($invoiceId, $statusId)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            $invoice->status_id = $statusId;
            $invoice->save();
        }
    }

    public function sendInvoiceEmail()
    {
        $invoiceDetails = Invoice::find($this->selectedInvoiceId);
        if ($invoiceDetails) {
            $studentEmail = $invoiceDetails->student->email;

            Mail::send('emails.invoice', ['invoice' => $invoiceDetails, 'payment' => $this->payment], function ($message) use ($studentEmail) {
                $message->to($studentEmail)
                        ->subject('Your Invoice Payment');
            });

            $this->toast(
                type: 'success',
                title: 'Invoice Sent!',
                description: 'The invoice has been sent to the student\'s email.',
                position: 'toast-bottom toast-end',
                icon: 'o-mail',
                css: 'alert-success',
                timeout: 3000,
                redirectTo: null
            );
        }
    }

    public function updatedSelectedInvoiceId($value)
    {
        $this->updateAmount($value);
    }

    public function with(): array
    {
        return [
            'statuses' => Status::all(),
        ];
    }
};
?>
<div>
    <x-header title="Payment" separator progress-indicator />
    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <x-form wire:submit.prevent="save">
                <x-choices-offline
                    label="Invoice ID"
                    wire:model="selectedInvoiceId"
                    :options="$invoices"
                    option-label="label"
                    option-value="id"
                    single
                    searchable
                    readonly
                />
                <p class="underline"> {{ $payment->invoice->amount ?? '' }} DJF</p>
                <x-select
                    label="Payment Option"
                    wire:model="paymentType"
                    :options="$paymentTypes"
                    option-label="name"
                    option-value="id"
                    wire:change="changePaymentType"
                />
                @if(in_array($paymentType, ['cheque', 'virement']))
                @if($paymentType === 'cheque')
                    <x-input
                        label="Cheque Number"
                        wire:model="chequeNumber"
                        type="text"
                        required
                    />
                @elseif($paymentType === 'virement')
                    <x-input
                        label="Virement Details"
                        wire:model="virementDetails"
                        type="text"
                        required
                    />
                @endif
            @endif
                <x-select label="Payment Status" icon-right="o-user" :options="$statuses" wire:model="status_id" class="w-full" wire:change="changeStatus"  />
                
               
                
                @if($showAmountInput)
                    <x-input
                        label="Amount"
                        wire:model="amount"
                        type="number" wire:change="changeTheAmount"
                        required
                    />
                @endif

                <div class="mt-4">
                    <x-button label="Save Payment" icon="o-paper-airplane" spinner="save" class="btn-primary" type="submit" />
                </div>
            </x-form>
        </div>

        <div id="invoice-preview">
            <div class="max-w-4xl p-8 mx-auto bg-white rounded-lg shadow-md">
                @if($invoiceDetails = \App\Models\Invoice::find($selectedInvoiceId))
                    <header class="flex items-center justify-center mb-8">
                        <img src="/images/iad_new1.png" alt="Logo" class="h-20">
                    </header>
                    <div class="flex justify-center mb-8">
                        <div class="text-center">
                            <h1 class="text-4xl font-bold">Reçu</h1>
                            <p class="font-extrabold text-gray-500">#{{ $invoiceDetails->invoiceId }}</p>
                        </div>
                    </div>
                    <div class="flex justify-between mb-8">
                        <div>
                            <h2 class="text-lg font-semibold">To: {{ $invoiceDetails->student->name }}</h2>
                            <p>{{ $invoiceDetails->student->studentId }}.</p>
                            <p> {{ $invoiceDetails->student->filiere->name }}, {{ $invoiceDetails->student->niveau->name }}, {{ $invoiceDetails->student->section->name }}</p>
                        </div>
                    </div>
                    <div class="flex justify-between mb-8">
                        <div>
                            <h2 class="text-lg font-semibold">Issued</h2>
                            <p>{{ \Carbon\Carbon::parse($invoiceDetails->start_date)->format('l, F j, Y') }}</p>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-center">Status</h2>
                            @if ($invoiceDetails->status)
                                <x-badge :value="$invoiceDetails->status->name" :class="$invoiceDetails->status->color" />
                            @endif
                        </div>
                    </div>
                    <table class="w-full mb-8">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2 text-left">Description</th>
                                <th class="px-4 py-2 text-right">Echeance</th>
                                <th class="px-4 py-2 text-right">Amount</th>
                                <th class="px-4 py-2 text-right">Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 border">{{ $invoiceDetails->subject }}</td>
                                <td class="px-4 py-2 text-right border">{{ $invoiceDetails->billMethodQuantity->echeance }}</td>
                                <td class="px-4 py-2 text-right border"> {{ $invoiceDetails->amount }} DJF</td>
                                <td class="px-4 py-2 text-right border"> {{ $invoiceDetails->remaining }} DJF</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex items-center justify-between mb-8">
                        <div>
                           Payment option:  <span class="text-xl font-semibold">{{ $payment->payment_type }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-lg"><span class="text-2xl font-semibold">Total Paid:</span> {{ $invoiceDetails->amount_paid }} DJF</p>
                            <hr>
                            <p class="mt-2 text-2xl font-bold">Reste:  {{ $invoiceDetails->remaining }} DJF</p>
                        </div>
                    </div>

                    <footer>
                        <x-button onclick="printInvoice()" class="btn btn-primary print-hide">Print</x-button>
                    </footer>
                @endif
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
