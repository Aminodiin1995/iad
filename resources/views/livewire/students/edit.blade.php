<?php

use App\Models\BillMethod;
use App\Models\Status;
use App\Models\Student;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Section;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Carbon\Carbon;

new class extends Component {
    use Toast;

    public Student $student;
    public $name;
    public $address;
    public $telephone;
    public $email;
    public $join_date;
    public $birth_date;
    public $billmethod_id;
    public $status_id;
    public $filiere_id;
    public $niveau_id;
    public $section_id;
    public $father_name;
    public $join_year;
    public $current_year;

    public function mount(Student $student): void
    {
        $this->student = $student;
        $this->name = $student->name;
        $this->address = $student->address;
        $this->telephone = $student->telephone;
        $this->email = $student->email;
        $this->join_date = Carbon::parse($student->join_date)->format('d/m/Y');
        $this->birth_date = Carbon::parse($student->birth_date)->format('d/m/Y');
        $this->billmethod_id = $student->billmethod_id;
        $this->status_id = $student->status_id;
        $this->filiere_id = $student->filiere_id;
        $this->niveau_id = $student->niveau_id;
        $this->section_id = $student->section_id;
        $this->father_name = $student->father_name;
        $this->join_year = $student->join_year;
        $this->current_year = $student->current_year;
    }

    public function update(): void
    {
        $this->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'telephone' => 'required|string',
            'email' => 'required|email',
            'join_date' => 'required|date_format:d/m/Y',
            'birth_date' => 'required|date_format:d/m/Y',
            'billmethod_id' => 'required|integer|exists:bill_methods,id',
            'status_id' => 'required|integer|exists:statuses,id',
            'filiere_id' => 'required|integer|exists:filieres,id',
            'niveau_id' => 'required|integer|exists:niveaux,id',
            'section_id' => 'required|integer|exists:sections,id',
            'father_name' => 'required|string',
            'join_year' => 'required|date_format:Y',
            'current_year' => 'required|date_format:Y',
        ]);

        $this->student->update([
            'name' => $this->name,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'join_date' => Carbon::createFromFormat('d/m/Y', $this->join_date)->format('Y-m-d'),
            'birth_date' => Carbon::createFromFormat('d/m/Y', $this->birth_date)->format('Y-m-d'),
            'billmethod_id' => $this->billmethod_id,
            'status_id' => $this->status_id,
            'filiere_id' => $this->filiere_id,
            'niveau_id' => $this->niveau_id,
            'section_id' => $this->section_id,
            'father_name' => $this->father_name,
            'join_year' => $this->join_year,
            'current_year' => $this->current_year,
        ]);

        $this->success('Student updated successfully.', redirectTo: '/students');
    }

    public function delete(): void
    {
        $this->student->delete();
        $this->success('Student deleted successfully.', redirectTo: '/students');
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
    <x-header :title="$student->name" separator progress-indicator>
        <x-slot:actions>
        </x-slot:actions>
    </x-header>

    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <x-form wire:submit.prevent="update">
                <x-input label="Name" name="name" wire:model.defer="name" />
                <x-input label="Father Name" name="father_name" wire:model.defer="father_name" />
                <x-input label="Address" name="address" wire:model.defer="address" />
                @php
                    $config1 = ['altFormat' => 'd/m/Y'];
                @endphp
                <x-datepicker label="Date of Birth" wire:model.defer="birth_date" name="birth_date" icon-right="o-calendar" :config="$config1" />
                <x-input label="Phone" name="telephone" wire:model.defer="telephone" />
                <x-input label="Email" name="email" wire:model.defer="email" />
                <x-datepicker label="Join Date" wire:model.defer="join_date" name="join_date" icon-right="o-calendar" :config="$config1" />
                <x-input label="Join Year" name="join_year" wire:model.defer="join_year" />
                <x-input label="Current Year" name="current_year" wire:model.defer="current_year" />
                <x-select label="Bill Method" name="billmethod_id" wire:model.defer="billmethod_id" :options="$billMethods" option-label="name" option-value="id" placeholder="---" />
                <x-select label="Status" name="status_id" wire:model.defer="status_id" :options="$statuses" option-label="name" option-value="id" placeholder="---" />
                <x-select label="Filiere" name="filiere_id" wire:model.defer="filiere_id" :options="$filieres" option-label="name" option-value="id" placeholder="---" />
                <x-select label="Niveau" name="niveau_id" wire:model.defer="niveau_id" :options="$niveaux" option-label="name" option-value="id" placeholder="---" />
                <x-select label="Section" name="section_id" wire:model.defer="section_id" :options="$sections" option-label="name" option-value="id" placeholder="---" />
                <div class="mt-4">
                    <x-button label="Cancel" link="/students" />
                    <x-button label="Save Changes" spinner="update" type="submit" icon="o-paper-airplane" class="btn-primary" />
                </div>
            </x-form>
        </div>
        <div>
            <img src="/images/edit user.png" width="300" class="mx-auto" />
        </div>
    </div>
</div>
