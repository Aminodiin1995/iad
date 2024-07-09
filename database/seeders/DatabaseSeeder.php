<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Filiere;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    { 
           $this->call(UserSeeder::class); 
           $this->call(DepartmentSeeder::class);
           $this->call(StatusSeeder::class);
           $this->call(NiveauSeeder::class);
           $this->call(FiliereSeeder::class);
           $this->call(SectionSeeder::class);
           $this->call(BillMethodSeeder::class);
           //$this->call(StudentSeeder::class);

    }
}
