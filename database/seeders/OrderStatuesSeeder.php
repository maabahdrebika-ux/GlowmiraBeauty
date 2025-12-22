<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatuesSeeder extends Seeder
{
    public function run()
    {
        // Insert default order statues
        DB::table('order_statues')->insert([
            ['state' => 'قيد الانتظار'],    // Pending
            ['state' => 'قيد التنفيذ'],    // Processing
            ['state' => 'مكتمل'],          // Completed
            ['state' => 'ملغي'],           // Cancelled
        ]);
    }
}
