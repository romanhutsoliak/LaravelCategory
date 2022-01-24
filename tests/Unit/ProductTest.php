<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function products_list()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->call('GET', '/api/products');
        
        $this->assertEquals(200, $response->status());
    }
    
    /** @test */
    public function products_create()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->json('POST', '/api/categories', [
            'name' => 'The firs category',
        ]);
        $this->assertEquals(201, $response->status());
        
        $response = $this->json('POST', '/api/products', [
            'name' => 'The first product',
            'description' => 'The first product description',
            'price' => 10.99,
            'categoryIds[]' => Category::first()->id,
        ]);
        
        if ($response->status() == 422) dd($response->getData());
        
        $this->assertEquals(201, $response->status());
    }
}
