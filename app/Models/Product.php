<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
protected static function boot()
{
    parent::boot();

    static::deleting(function ($product) {
        if ($product->purchases()->count() > 0) {
            throw new \Exception("Cannot delete product with associated purchases.");
        }
    });
}

public function totalPurchasedQuantity()
{
    return $this->purchases()->sum('quantity');
}
}


