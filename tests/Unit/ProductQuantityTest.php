<?php

namespace Tests\Unit;

use App\Models\Product;
use DomainException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductQuantityTest extends TestCase
{
    public function test_increases_quantity_by_requested_amount(): void
    {
        $product = $this->makeProduct(4);

        $product->increaseQuantity(3);

        $this->assertSame(7, $product->quantity);
    }

    public function test_decreases_quantity_by_requested_amount(): void
    {
        $product = $this->makeProduct(4);

        $product->decreaseQuantity(2);

        $this->assertSame(2, $product->quantity);
    }

    public function test_decreases_quantity_to_zero(): void
    {
        $product = $this->makeProduct(2);

        $product->decreaseQuantity(2);

        $this->assertSame(0, $product->quantity);
    }

    public function test_rejects_non_positive_quantity_changes(): void
    {
        $product = $this->makeProduct(4);

        $this->expectException(InvalidArgumentException::class);

        $product->increaseQuantity(0);
    }

    public function test_rejects_negative_quantity_changes(): void
    {
        $product = $this->makeProduct(4);

        $this->expectException(InvalidArgumentException::class);

        $product->decreaseQuantity(-1);
    }

    public function test_does_not_allow_quantity_to_become_negative(): void
    {
        $product = $this->makeProduct(2);

        $this->expectException(DomainException::class);

        $product->decreaseQuantity(3);
    }

    public function test_does_not_allow_quantity_to_exceed_the_database_column_limit(): void
    {
        $product = $this->makeProduct(Product::MAX_QUANTITY);

        $this->expectException(DomainException::class);

        $product->increaseQuantity();
    }

    private function makeProduct(int $quantity): Product
    {
        $product = new Product;
        $product->setAttribute('quantity', $quantity);

        return $product;
    }
}
