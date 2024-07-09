<?php

use App\Models\Payment;
use App\Models\Status;
use App\Models\Invoice;
use App\Models\BillMethodQuantity;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use Toast;

    public $selectedInvoiceId;
    public $paymentType = 'cash';
    public $status_id = null;
    public array $invoices = [];
    public $amount = 0;
    public array $paymentTypes;


    // Load existing data
    public function mount(): void
{
    $this->user_id = Auth::id();
    $this->invoices = Invoice::select('id', 'invoiceId as label')->get()->toArray(); // Use 'label' for the display
    $this->paymentTypes = [
            ['id' => 'cash', 'name' => 'Cash'],
            ['id' => 'D-Money', 'name' => 'D-Money'],
            ['id' => 'waafi', 'name' => 'Waafi'],
            ['id' => 'cac', 'name' => 'CAC'],
            ['id' => 'cheque', 'name' => 'Cheque'],
            ['id' => 'virement', 'name' => 'Virement'],
        ];

}


protected $rules = [
        'selectedInvoiceId' => 'required|exists:invoices,id',
        'paymentType' => 'required|in:cash,D-Money,waafi,cac,cheque,virement',

    ];
    // Save payment
    public function save(): void
    {
        $this->validate();
        $payment = Payment::create([
            'invoice_id' => $this->selectedInvoiceId,
            'payment_type' => $this->paymentType,
            'amount' => $this->amount,
            'status_id' => $this->status_id,
            'user_id' => Auth::id(),
        ]);

        $this->redirect("/payments/{$payment->id}/edit");
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
    <x-header title="Create Payment" separator progress-indicator />
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
            />

            <x-select label="Payment Type"
            wire:model="paymentType"
            :options="$paymentTypes"
            option-label="name"
            option-value="id"
        />

                <!-- Submit button -->
                <div class="mt-4">
                    <x-button label="Create Payment" icon="o-paper-airplane" spinner="save" class="btn-primary" type="submit" />
                </div>
            </x-form>
        </div>
        <div>
            <img src="/images/payment2.png" width="700" class="mx-auto" />
        </div>
    </div>
</div>
