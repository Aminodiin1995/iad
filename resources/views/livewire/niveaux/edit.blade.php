<?php

use App\Models\Niveau;
use Livewire\Volt\Component;

new class extends Component
{
    public Niveau $niveau;
    public string $name;

    public function mount(Niveau $niveau)
    {
        $this->niveau = $niveau;
        $this->name = $niveau->name;
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->niveau->update([
            'name' => $this->name,
        ]);

       

        return redirect()->route('niveaux.index');
    }
};
?>

<div>
    <div>
        <h1 class="mb-4 text-2xl font-bold">Edit Niveau</h1>
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
