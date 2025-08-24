<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [

            ['name' => 'Female','is_active' => 1],
            ['name' => 'Male','is_active' => 1],
        ];

        foreach ($genders as $key => $value) {

            Gender::create($value);

        }
    }
}
