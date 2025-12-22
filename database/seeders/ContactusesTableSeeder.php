<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contactuses')->insert([
            'email' => 'info@solidarity.ly',
            'phonenumber' => '+218910000000',
            'adress' => 'Tripoli',
            'adressen' => 'Tripoli',
            'whatsapp' => '+218910000000',

            
            'lan' => '2.43242342',
            'long' => '22.33445',
            'ourworksa' => 'من الاثنين إلى الجمعة: 9:00 صباحًا - 10:00 مساءً

السبت: 10:00 صباحًا - 8:30 مساءً

الأحد: 12:00 ظهرًا - 5:00 مساءً',
            'ourworkse' => 'Mon-Fri: 9:00AM - 10:00PM

Saturday: 10:00AM - 8:30PM

Sunday: 12:00PM - 5:00PM',
            'facebook_url' => 'https://facebook.com/solidarity',
            'instagram_url' => 'https://instagram.com/solidarity',
            'twitter_url' => 'https://twitter.com/solidarity',
            'linkedin_url' => 'https://linkedin.com/company/solidarity',
            'youtube_url' => 'https://youtube.com/solidarity',
            'pinterest_url' => 'https://pinterest.com/solidarity',
        ]);
    }
}
