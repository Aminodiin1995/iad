<?php

use App\Models\Section;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;
    public string $name = '';

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        Section::create([
            'name' => $this->name,
        ]);

        $this->toast('Section created successfully.', 'Section created successfully.', '', 'o-warning');
        $this->name = ''; // Clear the input field
    }

    protected $listeners = ['sectionCreated' => '$refresh'];
};
?>

<div>
    <h1 class="mb-4 text-2xl font-bold">Create Section</h1>

    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <x-input type="text" id="name" wire:model="name" class="w-full p-2 border border-gray-300 rounded" required />
        </div>

        <x-button label="Create" icon="o-pencil" spinner="save" class="btn-primary" type="submit" />
        <x-button label="Cancel" link="/sections" />

    </form>
</div>
