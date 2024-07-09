<?php

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $number = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $department_id = null;
    public $avatar_file;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'number' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'department_id' => 'nullable|exists:departments,id',
            'avatar_file' => 'nullable|image|max:1024',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'number' => $this->number,
            'department_id' => $this->department_id,
            'password' => Hash::make($this->password),
            'remember_token' => Str::random(10),
            'status' => 1, // default status
            'avatar' => '/images/empty-user.jpg',
            'email_verified_at' => now(),
        ]);

        if ($this->avatar_file) {
            $url = $this->avatar_file->store('users', 'public');
            $user->update(['avatar' => "/storage/$url"]);
        }

        $this->success('User created successfully.', redirectTo: '/users');
    }

    public function with(): array
    {
        return [
            'departments' => Department::all(),
        ];
    }
};
?>
<div>
    <x-header title="New User" separator progress-indicator />

    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <form wire:submit.prevent="save">
                @csrf
                
                <x-input label="Name" name="name" wire:model="name" />
                <x-input label="Email" name="email" wire:model="email" />
                <x-input label="Number" name="number" wire:model="number" />
                <x-input label="Password" name="password"  type="password" wire:model="password" />
                <x-input label="Confirmation Password" name="password_confirmation"  type="password" wire:model="password_confirmation" />

                <div class="flex justify-between mt-4">
                    <x-button label="Cancel" link="/users" />
                    <x-button label="Create" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary">Submit</x-button>
                </div>
            </form>
        </div>
        <div>
            <img src="/images/edit-form.png" width="300" class="mx-auto" />
        </div>
    </div>
</div>
