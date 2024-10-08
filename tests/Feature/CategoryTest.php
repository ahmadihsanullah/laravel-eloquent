<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ReviewSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertNotNull;

class CategoryTest extends TestCase
{
    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("FOOD");
        self::assertNotNull($category);

        // $products = Product::where("category_id", $category->id)->get();
        $products = $category->products;

        self::assertNotNull($products);
        self::assertCount(2, $products);
    }

    public function testOneToManyQuery()
    {
        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "FOOD Category";
        $category->is_active = true;
        $category->save();

        $product = new Product();
        $product->id = '1';
        $product->name = 'product 1';
        $product->description = 'description 1';
        $category->products()->save($product);

        assertNotNull($product->category_id);
    }

    public function testSearchQuery()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("FOOD");

        $products = $category->products;
        self::assertCount(2, $products);

        $outOfBoundStock = $category->products()->where('stock', '<=', 0)->get();
        self::assertCount(1, $outOfBoundStock);
    }

    public function testHasManyThrough()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, CustomerSeeder::class, ReviewSeeder::class]);
        
        $category = Category::find('FOOD');
        $this->assertNotNull($category);
        
        $reviews = $category->reviews;
        Log::info($reviews);
        $this->assertNotNull($reviews);
        $this->assertCount(2, $reviews);
        
        Log::info("has many through success");
    }
}
