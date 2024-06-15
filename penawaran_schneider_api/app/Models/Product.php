<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $primaryKey = "id";
    public $timestamps = false;
    public function standard_discounts(): BelongsTo
    {
        return $this->belongsTo(StandardDiscount::class);
    }
    public function penawarans(): BelongsToMany
    {
        return $this->belongsToMany(Penawaran::class);
    }
}
