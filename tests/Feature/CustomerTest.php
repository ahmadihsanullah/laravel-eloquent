<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Wallet;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function testCustomer()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class]);

        $customer = Customer::find("AHMAD");
        $this->assertNotNull($customer);

        // $wallet = Wallet::query()->where('customer_id', $customer->id)->first();
        $wallet = $customer->wallet;
        $this->assertNotNull($wallet);

        $this->assertEquals(1_000_000, $wallet->amount);
    }

    public function testOnetoOneQuery(){
        $customer = new Customer();
        $customer->id = "ihsan";
        $customer->name = "Ahmad Ihsan";
        $customer->email = "ihsan@gmail.com";
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount= 1_000_000;
        $customer->wallet()->save($wallet);

        self::assertNotNull($wallet->customer_id);
    }
}
