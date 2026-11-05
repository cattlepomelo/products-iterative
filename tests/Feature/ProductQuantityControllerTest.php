<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductQuantityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_increases_product_quantity_and_returns_the_updated_value(): void
    {
        $product = Product::factory()->create(['quantity' => 4]);

        $this->actingAs(User::factory()->create())
            ->patchJson(route('products.quantity.update', $product), [
                'direction' => 'increase',
                'amount' => 3,
            ])
            ->assertOk()
            ->assertJsonPath('quantity', 7);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 7,
        ]);
    }

    public function test_decreases_product_quantity_and_returns_the_updated_value(): void
    {
        $product = Product::factory()->create(['quantity' => 4]);

        $this->actingAs(User::factory()->create())
            ->patchJson(route('products.quantity.update', $product), [
                'direction' => 'decrease',
                'amount' => 2,
            ])
            ->assertOk()
            ->assertJsonPath('quantity', 2);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_rejects_decrease_that_would_make_quantity_negative(): void
    {
        $product = Product::factory()->create(['quantity' => 2]);

        $this->actingAs(User::factory()->create())
            ->patchJson(route('products.quantity.update', $product), [
                'direction' => 'decrease',
                'amount' => 3,
            ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Quantity cannot be reduced below zero.');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_rejects_invalid_quantity_change_input(): void
    {
        $product = Product::factory()->create(['quantity' => 2]);

        $this->actingAs(User::factory()->create())
            ->patchJson(route('products.quantity.update', $product), [
                'direction' => 'increase',
                'amount' => 0,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['amount']);
    }

    public function test_rejects_quantity_increase_beyond_the_database_column_limit(): void
    {
        $product = Product::factory()->create(['quantity' => Product::MAX_QUANTITY]);

        $this->actingAs(User::factory()->create())
            ->patchJson(route('products.quantity.update', $product), [
                'direction' => 'increase',
                'amount' => 1,
            ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Quantity exceeds the maximum supported value.');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => Product::MAX_QUANTITY,
        ]);
    }

    public function test_product_show_page_renders_quantity_and_tag_controls(): void
    {
        $product = Product::factory()->create(['quantity' => 4]);

        $this->actingAs(User::factory()->create())
            ->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('data-quantity-control', false)
            ->assertSee('data-tag-editor', false)
            ->assertSee(route('products.quantity.update', $product))
            ->assertSee(route('tags.search'));
    }
}
