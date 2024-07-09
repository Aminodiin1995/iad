    <?php

    use App\Models\Invoice;
    use App\Models\Status;
    use Livewire\Volt\Component;
    use Mary\Traits\Toast;

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
    public $showAmountInput = false;




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
        $this->showAmountInput = ($this->status_id == 2);



    }

    public function save()
    {
        $this->validate();

        $this->invoice->update([
            'amount' => $this->amount,
            'discount' => $this->discount,
            'status_id' => $this->status_id,

        ]);

        $this->toast('Invoice updated.', 'Invoice updated.', '', 'o-warning');

        return redirect()->route('invoices.show', $this->invoice);
    }
// update task status //
public function changeStatus()
    {

        $this->invoice->update(['status_id' => $this->status_id]);


        $this->toast(
            type: 'warning',
            title: 'update,le Status !',
            description: null,                  // optional (text)
            position: 'toast-bottom toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-warning',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );


    }

    public function updatedStatusId($value)
{
    $this->changeStatus($value);
    $this->showAmountInput = ($value == 2);  // Show amount input only when status_id is 2
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
                />
                <p class="underline"> {{ $payment->invoice->amount }} DJF</p>
                <x-select
                    label="Payment Type"
                    wire:model="paymentType"
                    :options="$paymentTypes"
                    option-label="name"
                    option-value="id"
                />
                <x-select label="Status" icon-right="o-user" :options="$statuses" wire:model="status_id" class="w-full" wire:change="changeStatus" />

                @if($showAmountInput)
                <x-input
                    label="Amount"
                    wire:model="amount"
                    type="number" wire:change="changeTheAmount"
                    required
                />
                @endif

                <div class="mt-4">
                    <x-button label="Update Payment" icon="o-pencil" spinner="save" class="btn-primary" type="submit" />
                </div>
            </x-form>
        </div>

        <div id="invoice-preview">
            <hr>
            <div class="max-w-4xl p-8 mx-auto mt-10 bg-white rounded-lg shadow-md">
                @if($invoiceDetails = \App\Models\Invoice::find($selectedInvoiceId))
                <header class="flex items-center justify-center mb-8">
                    <img src="/images/iad_new1.png" alt="Logo" class="h-20">
                </header>
                <div class="flex justify-center mb-8">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold">Facture</h1>
                        <p class="font-extrabold text-gray-500">#{{ $invoiceDetails->invoiceId }}</p>
                    </div>
                </div>
                <div class="flex justify-between mb-8">
                    <div>
                        <h2 class="text-lg font-semibold">From:</h2>
                        <p>Comptabilité, IAD</p>
                        <p>comptabilite@IAD.com</p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">To:</h2>
                        <p>{{ $invoiceDetails->student->name }}, IAD, {{ $invoiceDetails->student->studentId }}.</p>
                        <p>{{ $invoiceDetails->student->email }}</p>
                    </div>
                </div>
                <div class="flex justify-between mb-8">
                    <div>
                        <h2 class="text-lg font-semibold">Issued</h2>
                        <p>{{ \Carbon\Carbon::parse($invoiceDetails->start_date)->format('l, F j, Y') }}</p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Due</h2>
                        <p>{{ \Carbon\Carbon::parse($invoiceDetails->due_date)->format('l, F j, Y') }}</p>
                    </div>
                </div>
                <table class="w-full mb-8">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-right">Discount</th>
                            <th class="px-4 py-2 text-right">Echance</th>
                            <th class="px-4 py-2 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 border">{{ $invoiceDetails->subject }}</td>
                            <td class="px-4 py-2 text-right border">{{ $invoiceDetails->discount }}%</td>
                            <td class="px-4 py-2 text-right border">{{ $invoiceDetails->due_date }}</td>
                            <td class="px-4 py-2 text-right border">DJF {{ $invoiceDetails->amount }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-lg font-semibold"></h2>
                    </div>
                    <div class="text-right">
                        <p class="text-lg"><span class="font-semibold">Subtotal:</span> {{ $invoiceDetails->amount }} DJF</p>
                        <hr>
                        <p class="text-lg"><span class="font-semibold">Discount ({{ $invoiceDetails->discount }}%):</span> DJF {{ $invoiceDetails->amount * $invoiceDetails->discount / 100 }}</p>
                        <hr>
                        <p class="mt-2 text-2xl font-bold">Total Amount: DJF {{ $invoiceDetails->amount - ($invoiceDetails->amount * $invoiceDetails->discount / 100) }}</p>
                    </div>
                </div>
                <footer>
                    <x-button onclick="printInvoice()" class="btn btn-primary print-hide">Print</x-button>
                    <x-button class="btn btn-success print-hide" icon="o-check" wire:click="sendInvoiceEmail" />
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