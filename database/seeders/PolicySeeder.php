<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Policy;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Policy::create([
            'title_ar' => 'الشروط والسياسات',
            'title_en' => 'Terms and Policies',
            'description_ar' => 'هذه هي الشروط والسياسات الخاصة بالموقع. يرجى قراءتها بعناية.',
            'description_en' => 'These are the terms and policies of the site. Please read them carefully.',
        ]);
    }
}
