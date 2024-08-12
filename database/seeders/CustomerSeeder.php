<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = New Customer();
        $customer->id = "AHMAD";
        $customer->name = "ahmad";
        $customer->email = "ahmad@gmail.com";
        $customer->save();
    }
}
