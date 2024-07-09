<?php

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Filiere::count()) {
            return;
        }

        Filiere::insert([
            [
                'name' => 'Genie informatique',
               
            ],
            [
                'name' => 'Gestion des entreprises',
                
            ],
            [
                'name' => 'resource humain',
                
            ],
             [
                'name' => 'comptabilite',
                
            ],
            [
                'name' => 'marketing & com',
    
            ],
        ]);
    }
    }

