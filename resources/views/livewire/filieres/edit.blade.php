<?php

use App\Models\Filiere;
use Livewire\Volt\Component;

new class extends Component
{
    public Filiere $filiere;
    public string $name;

    public function mount(Filiere $filiere)
    {
        $this->filiere = $filiere;
        $this->name = $filiere->name;
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->filiere->update([
            'name' => $this->name,
        ]);

        $this->toast(
            type: 'success',
            title: 'Filiere updated!',
            description: null,                  // optional (text)
            position: 'toast-bottom toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
        return redirect()->route('filieres.index');
    }
}; ?>

<div>
    <div>
        <h1 class="mb-4 text-2xl font-bold">Edit Filiere</h1>
        <hr />
    
        <form wire:submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <x-input type="text" id="name" wire:model="name" class="border border-gray-300 rounded" required />
            </div>
    
            <button type="submit" class="p-2 text-white bg-blue-500 rounded">Update</button>
        </form>
    </div>
    
</div>
