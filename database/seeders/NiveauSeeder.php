<?php

namespace Database\Seeders;

use App\Models\Niveau;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NiveauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Niveau::count()) {
            return;
        }

        Niveau::insert([
            [
                'name' => 'licence 1',
            ],
            [
                'name' => 'licence 2',
            ],
            [
                'name' => 'licence 3',
            ],
        ]);
    }
    }

