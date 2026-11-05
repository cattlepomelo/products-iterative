<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'quantity', 'description', 'expiration_date', 'status'])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'expiration_date' => 'date:Y-m-d',
        ];
    }
}
