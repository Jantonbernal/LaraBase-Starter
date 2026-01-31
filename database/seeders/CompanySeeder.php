<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = File::factory()->withPath('company')->create();

        Company::factory()->create([
            'file_id' => $file->id,
        ]);
    }
}
