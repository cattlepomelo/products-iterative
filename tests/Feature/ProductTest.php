<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_routes_require_authentication(): void
    {
        $this->get(route('products.index'))
            ->assertRedirect(route('login'));
    }

    public function test_home_page_displays_the_product_catalog_branding(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSeeText('Produktu katalogs')
            ->assertSeeText('Tavs produktu katalogs vienuviet.');
    }

    public function test_dashboard_displays_the_active_dashboard_navigation_link(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('Dashboard')
            ->assertSee('aria-current="page"', false);
    }

    public function test_authenticated_user_can_view_products(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Piemēra produkts',
            'description' => 'Produkta apraksts',
            'price' => '12.50',
        ]);

        $this->actingAs($user)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('holy-grail-layout')
            ->assertSee('holy-grail-navigation')
            ->assertSee('holy-grail-main')
            ->assertSee('holy-grail-aside')
            ->assertSee('holy-grail-footer')
            ->assertSeeText('Informācija')
            ->assertSee('aria-current="page"', false)
            ->assertSeeText($product->name)
            ->assertSeeText('12,50 €');
    }

    public function test_product_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('products.store'), [
                'name' => 'Jauns produkts',
                'description' => 'Apraksts',
                'price' => '9.99',
            ])
            ->assertRedirect(route('products.show', Product::first()))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('products', [
            'name' => 'Jauns produkts',
            'description' => 'Apraksts',
            'price' => '9.99',
        ]);
    }

    public function test_product_creation_validates_required_fields_and_price(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('products.create'))
            ->post(route('products.store'), [
                'name' => '',
                'price' => '-1',
            ])
            ->assertRedirect(route('products.create'))
            ->assertSessionHasErrors(['name', 'price']);
    }

    public function test_product_can_be_viewed_edited_and_deleted_using_route_model_binding(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Sākotnējais nosaukums',
            'description' => null,
            'price' => '10.00',
        ]);

        $this->actingAs($user)
            ->get(route('products.show', $product))
            ->assertOk()
            ->assertSeeText($product->name);

        $this->actingAs($user)
            ->get(route('products.edit', $product))
            ->assertOk()
            ->assertSeeText('Rediģēt produktu');

        $this->actingAs($user)
            ->put(route('products.update', $product), [
                'name' => 'Atjaunināts nosaukums',
                'description' => 'Jauns apraksts',
                'price' => '14.25',
            ])
            ->assertRedirect(route('products.show', $product))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Atjaunināts nosaukums',
            'price' => '14.25',
        ]);

        $this->actingAs($user)
            ->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_unknown_product_returns_not_found(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('products.show', 999))
            ->assertNotFound();
    }
}
