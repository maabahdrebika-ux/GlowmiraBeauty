<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ExchangesTypesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('invoice_types')->insert([
            [
                'name' => 'من المحل',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'توصيل',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
