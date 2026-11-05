<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_seeder_creates_twenty_products(): void
    {
        $this->seed(ProductSeeder::class);

        $this->assertDatabaseCount('products', 20);
    }

    public function test_database_seeder_can_run_again_without_duplicate_users(): void
    {
        $user = User::factory()->create([
            'name' => 'Existing User',
            'email' => 'test@example.com',
        ]);

        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Existing User',
            'email' => 'test@example.com',
        ]);
        $this->assertDatabaseCount('products', 40);
    }
}
