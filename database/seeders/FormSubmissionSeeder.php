<?php

namespace Database\Seeders;

use App\Models\FormSubmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            FormSubmission::create([
                'form_id' => 1,
                'data' => [
                    'name' => 'User '.$i,
                    'phone' => '98765432'.str_pad($i, 2, '0', STR_PAD_LEFT),
                ],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
