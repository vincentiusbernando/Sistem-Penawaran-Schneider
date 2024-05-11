<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StandardDiscount extends Model
{
    use HasFactory;
    protected $table="standard_discounts";
    protected $primaryKey="id";
    public function products():HasMany{
        return $this->hasMany(Product::class);
    }
}
