<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Section::count()) {
            return;
        }

        Section::insert([
            [
                'name' => ' Section a',
            ],
            [
                'name' => 'Section b',
            ],
            [
                'name' => 'Section c',
            ],
            [
                'name' => 'ali sabieh',
            ],
        ]);
    }
}
