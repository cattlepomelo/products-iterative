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

    public function test_home_page_redirects_to_the_product_list(): void
    {
        $this->get(route('home'))
            ->assertRedirect(route('products.index'));
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
            'quantity' => 3,
            'description' => 'Produkta apraksts',
            'expiration_date' => '2027-01-15',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('holy-grail-layout')
            ->assertSee('holy-grail-navigation')
            ->assertSee('holy-grail-main')
            ->assertSee('holy-grail-aside')
            ->assertSee('holy-grail-footer')
            ->assertSeeText('Musordrop')
            ->assertSee('https://media1.tenor.com/m/92fiqKsu_YoAAAAC/cs2-musor-drop.gif')
            ->assertDontSee('youtube.com')
            ->assertDontSee('<iframe', false)
            ->assertSeeText('Azartspēles var izraisīt atkarību.')
            ->assertSee('aria-current="page"', false)
            ->assertSeeText($product->name)
            ->assertSeeText('3')
            ->assertSeeText('active');
    }

    public function test_product_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('products.store'), [
                'name' => 'Jauns produkts',
                'quantity' => '5',
                'description' => 'Apraksts',
                'expiration_date' => '2027-06-30',
                'status' => 'active',
            ])
            ->assertRedirect(route('products.show', Product::first()))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('products', [
            'name' => 'Jauns produkts',
            'quantity' => 5,
            'description' => 'Apraksts',
            'expiration_date' => '2027-06-30',
            'status' => 'active',
        ]);
    }

    public function test_product_creation_validates_required_fields_and_product_data(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('products.create'))
            ->followingRedirects()
            ->post(route('products.store'), [
                'name' => '',
            ])
            ->assertOk()
            ->assertSee('role="alert"', false)
            ->assertSee('The name field is required.')
            ->assertSee('The quantity field is required.')
            ->assertSee('The status field is required.');

        $this->actingAs(User::factory()->create())
            ->from(route('products.create'))
            ->post(route('products.store'), [
                'name' => 'Nederīgs produkts',
                'quantity' => '-1',
                'expiration_date' => 'not-a-date',
                'status' => 'active',
            ])
            ->assertRedirect(route('products.create'))
            ->assertSessionHasErrors(['quantity', 'expiration_date']);
    }

    public function test_successful_product_action_displays_flash_message(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->followingRedirects()
            ->post(route('products.store'), [
                'name' => 'Jauns produkts',
                'quantity' => 5,
                'description' => 'Apraksts',
                'expiration_date' => '2027-06-30',
                'status' => 'Pieejams',
            ])
            ->assertOk()
            ->assertSee('role="status"', false)
            ->assertSee('Produkts veiksmīgi izveidots.');
    }

    public function test_product_can_be_viewed_edited_and_deleted_using_route_model_binding(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Sākotnējais nosaukums',
            'quantity' => 1,
            'description' => null,
            'expiration_date' => null,
            'status' => 'active',
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
                'quantity' => '4',
                'description' => 'Jauns apraksts',
                'expiration_date' => '2027-12-01',
                'status' => 'inactive',
            ])
            ->assertRedirect(route('products.show', $product))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Atjaunināts nosaukums',
            'quantity' => 4,
            'expiration_date' => '2027-12-01',
            'status' => 'inactive',
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
