<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_search_returns_matching_existing_tags(): void
    {
        $tag = Tag::create(['name' => 'Pārtika']);
        Tag::create(['name' => 'Dzērieni']);

        $this->actingAs(User::factory()->create())
            ->getJson(route('tags.search', ['query' => 'Pārt']))
            ->assertOk()
            ->assertJsonPath('tags.0.id', $tag->id)
            ->assertJsonPath('tags.0.name', 'Pārtika')
            ->assertJsonCount(1, 'tags');
    }

    public function test_tag_search_requires_a_query(): void
    {
        $this->actingAs(User::factory()->create())
            ->getJson(route('tags.search'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['query']);
    }

    public function test_product_tags_can_be_created_reused_and_synchronized(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $otherProduct = Product::factory()->create();
        $sharedTag = Tag::create(['name' => 'Vasara']);
        $removedTag = Tag::create(['name' => 'Akcija']);
        $otherProduct->tags()->attach($sharedTag);
        $product->tags()->attach([$sharedTag->id, $removedTag->id]);

        $this->actingAs($user)
            ->put(route('products.tags.update', $product), [
                'tags' => ['Vasara', 'Jaunums'],
            ])
            ->assertRedirect(route('products.show', $product))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('tags', 3);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $product->id,
            'tag_id' => $sharedTag->id,
        ]);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $product->id,
            'tag_id' => Tag::query()->where('name', 'Jaunums')->value('id'),
        ]);
        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $product->id,
            'tag_id' => $removedTag->id,
        ]);
        $this->assertDatabaseHas('product_tag', [
            'product_id' => $otherProduct->id,
            'tag_id' => $sharedTag->id,
        ]);
    }

    public function test_product_tags_can_be_cleared(): void
    {
        $product = Product::factory()->hasAttached(Tag::create(['name' => 'Vecā birka']))->create();

        $this->actingAs(User::factory()->create())
            ->put(route('products.tags.update', $product))
            ->assertRedirect(route('products.show', $product));

        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $product->id,
        ]);
    }

    public function test_product_tag_update_rejects_duplicate_names(): void
    {
        $product = Product::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('products.tags.update', $product), [
                'tags' => ['Jaunums', 'jaunums'],
            ])
            ->assertSessionHasErrors(['tags.1']);

        $this->assertDatabaseCount('tags', 0);
    }
}
