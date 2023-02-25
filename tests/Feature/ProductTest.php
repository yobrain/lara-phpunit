<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    private User $user;

    protected function setUp():void
    {
        parent::setUp();
        $this->user = User::factory()->create();

    }
    /**
     * A basic feature test example.
     */
    public function test_products_table_is_empty(): void
    {
      $response = $this->actingAs($this->user)->get('/products');
        $response->assertSee(__('No Products found!'));
    }

    public function test_products_table_is_not_empty(): void
    {
        $product = Product::create(['name'=> 'Product 1','price'=> 20, 'quantity'=> 23]);
        $response = $this->actingAs($this->user)->get('/products');
        //  $response->assertDontSee(__('No Products found!')); & not good for testing
        // $response->assertSee('Product 1'); // too not good
        $response->assertViewHas('products', function($collection) use($product) {
        return $collection->contains($product);
    });
    }

    public function test_products_paginate_10_items()
    {
        $products = Product::factory(10)->create();
        $lastProduct  = $products->last();
        $reponse = $this->actingAs($this->user)->get('/products');
        $reponse->assertStatus(200);
        $reponse->assertViewHas('products', function($collection) use ($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }
}
