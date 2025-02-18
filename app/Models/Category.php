<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'status', 'showHome'];   // Type 1

    // protected $guarded = [];     // Type 2

    public function sub_category()
    {
        return $this->hasMany(SubCategoryModel::class);
    }
}
