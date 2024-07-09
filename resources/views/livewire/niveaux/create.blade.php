<?php

use App\Models\Niveau;
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

        Niveau::create([
            'name' => $this->name,
        ]);
        $this->toast(
            type: 'success',
            title: 'niveau created!',
            description: null,                  // optional (text)
            position: 'toast-bottom toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
        return redirect()->route('niveaux.index');
    }
};
?>
<div>
    <h1 class="mb-4 text-2xl font-bold">Create Niveau</h1>

    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <x-input type="text" id="name" wire:model="name" class="w-full p-2 border border-gray-300 rounded" required />
        </div>

        <x-button label="Create" icon="o-pencil" spinner="save" class="btn-primary" type="submit" />
    </form>
</div>
