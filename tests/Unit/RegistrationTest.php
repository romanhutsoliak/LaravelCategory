<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\User;
use App\Models\Product;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function registration()
    {
        $response = $this->call('POST', '/api/register', [
            'name' => 'Test user',
            'email' => 'test@test.te',
            'password' => '11111111',
            'password_confirmation' => '11111111',
        ]);
        $this->assertEquals(200, $response->status());
    
        $response = $this->call('POST', '/api/login', [
            'email' => 'test@test.te',
            'password' => '11111111',
        ]);
        $this->assertEquals(200, $response->status());
        $response->assertJson(fn(AssertableJson $json) =>
            $json->hasAll('user', 'token')->missing('message')
        );
        
        $token = $response->getData()->token ?? null;
        $this->assertTrue(is_string($token), $token);
    }
}
