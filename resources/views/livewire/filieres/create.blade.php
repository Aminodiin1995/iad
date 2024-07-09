<?php

use App\Models\Filiere;
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

        Filiere::create([
            'name' => $this->name,
        ]);
        $this->toast(
            type: 'success',
            title: 'Filiere cree!',
            description: null,                  // optional (text)
            position: 'toast-bottom toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
        $this->reset('name');

        return back();
    }
    protected $listeners = ['created-saved' => '$refresh'];

}; ?>

<div>
    <h1 class="mb-4 text-2xl font-bold">Create Filiere</h1>

    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <x-input type="text" id="name" wire:model="name" class="w-full p-2 border border-gray-300 rounded" required />
        </div>

        <x-button label="create" icon="o-pencil" spinner="save" class="btn-primary" type="submit" />
        <x-button label="Cancel" link="/filieres" />

    </form>
</div>
