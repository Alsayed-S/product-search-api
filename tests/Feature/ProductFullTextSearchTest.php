<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductFullTextSearchTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_searches_product_name_and_description(): void
    {
        $this->createProduct([
            'name' => 'Wireless Device',
            'description' => 'A smartphone for daily use',
        ]);

        $this->getJson('/api/products/search?q=Wireless')
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Wireless Device',
            ]);

        $this->getJson('/api/products/search?q=smartphone')
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Wireless Device',
            ]);
    }

    private function createProduct(array $attributes = []): Product
    {
        $category = Category::firstOrCreate(
            ['name' => 'Test Category']
        );

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100,
            'stock_quantity' => 10,
        ], $attributes));
    }
}