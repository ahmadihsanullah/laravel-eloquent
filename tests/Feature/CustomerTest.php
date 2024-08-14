<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

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

    public function testHasOneThrough()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

        $customer = Customer::find("AHMAD");
        assertNotNull($customer);
        Log::info($customer);
        Log::info($customer->wallet);

        $virtualAccount = $customer->virtualAccount;
        Log::info($virtualAccount);
        assertNotNull($virtualAccount);
        assertEquals("BCA", $virtualAccount->bank);
    }

    public function testManyToMany()
    {
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class]);

        $customer = Customer::find("AHMAD");
        self::assertNotNull($customer);
        
        $customer->likeProducts()->attach('1');
        $products = $customer->likeProducts;
        self::assertCount(1, $products);

        self::assertEquals("1", $products[0]->id);
    }

    public function testManyToManyDetach()
    {
        $this->testManyToMany();

        $customer = Customer::find("AHMAD");
        $customer->likeProducts()->detach("1");

        $products = $customer->likeProducts;
        self::assertCount(0, $products);
    }

    public function testPivotAttribute()
    {
        $this->testManyToMany();

        $customer = Customer::find("AHMAD");
        $products = $customer->likeProducts;

        foreach ($products as $product) {
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }

    }

    public function testPivotAttributeCondition()
    {
        $this->testManyToMany();

        $customer = Customer::find("AHMAD");
        $products = $customer->likeProductsLastWeek;

        foreach ($products as $product) {
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }

    }

    public function testPivotModel()
    {
        $this->testManyToMany();

        $customer = Customer::find("AHMAD");
        $products = $customer->likeProducts;

        foreach ($products as $product) {
            $pivot = $product->pivot; //model Like
            Log::info($pivot);
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);

            $customer = $pivot->customer;
            self::assertNotNull($customer);

            $product = $pivot->product;
            self::assertNotNull($product);
        }

    }

}
