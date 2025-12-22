<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categories;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'عناية بالبشرة', 'englishname' => 'Skincare'],
            ['name' => 'عناية بالشعر',   'englishname' => 'Hair Care'],
            ['name' => 'مكياج',          'englishname' => 'Makeup'],
            ['name' => 'عطور',           'englishname' => 'Perfumes'],
            ['name' => 'عناية بالجسم',   'englishname' => 'Body Care'],
            ['name' => 'أدوات تجميل',    'englishname' => 'Beauty Tools'],
            ['name' => 'عناية بالأظافر', 'englishname' => 'Nail Care'],
            ['name' => 'عناية بالشفاه',  'englishname' => 'Lip Care'],
        ];

        foreach ($categories as $category) {
            // يمنع التكرار لو شغّلت seeder أكثر من مرة
            Categories::updateOrCreate(
                ['englishname' => $category['englishname']],
                $category
            );
        }
    }
}
