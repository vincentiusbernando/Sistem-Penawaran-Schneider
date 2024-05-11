<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Penawaran extends Model
{
    use HasFactory;
    protected $table="penawarans";
    protected $primaryKey="id";
    public $timestamps = false;

    public function teams():BelongsTo{
        return $this->belongsTo(Team::class);
    }
    public function internals():BelongsTo{
        return $this->belongsTo(Internal::class);
    }
    public function customers():BelongsTo{
        return $this->belongsTo(Customer::class);
    }
    public function products():BelongsToMany{
        return $this->belongsToMany(Product::class);
    }
}
