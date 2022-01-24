<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\User;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function create_category()
    {
        $category = Category::factory()->create();
        
        $this->assertCount(1, Category::all());
        
        $this->assertTrue(true);
    }
}
