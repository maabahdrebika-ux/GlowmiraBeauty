<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PolicySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ContactusesTableSeeder::class);

        $this->call(ContactusesTableSeeder::class);
        $this->call(ExchangesTypesSeeder::class);

        $this->call(AddressTableSeeder::class);
        $this->call(RolePermissionSeeder::class); // Create roles and permissions first
         $this->call(CreateAdminUserSeeder::class); // Then create admin user and assign role
         $this->call(OrderStatuesSeeder::class);
         $this->call(PolicySeeder::class);
         $this->call(CategoriesSeeder::class);

    }
}
