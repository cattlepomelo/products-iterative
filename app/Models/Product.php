<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use DomainException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use InvalidArgumentException;

#[Fillable(['name', 'quantity', 'description', 'expiration_date', 'status'])]
class Product extends Model
{
    public const MAX_QUANTITY = 4_294_967_295;

    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function increaseQuantity(int $amount = 1): void
    {
        if ($amount < 1) {
            throw new InvalidArgumentException('Quantity increase must be a positive integer.');
        }

        if ($amount > self::MAX_QUANTITY - $this->quantity) {
            throw new DomainException('Quantity exceeds the maximum supported value.');
        }

        $this->quantity += $amount;
    }

    public function decreaseQuantity(int $amount = 1): void
    {
        if ($amount < 1) {
            throw new InvalidArgumentException('Quantity decrease must be a positive integer.');
        }

        if ($amount > $this->quantity) {
            throw new DomainException('Quantity cannot be reduced below zero.');
        }

        $this->quantity -= $amount;
    }

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'expiration_date' => 'date:Y-m-d',
        ];
    }
}
