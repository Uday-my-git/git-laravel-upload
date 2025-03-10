<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function productImage() {
        return $this->hasMany(ProductImage::class);
    }

    public function productRatingFun()
    {
        return $this->hasMany(ProductRating::class)->where('status', 1);
    }
}
