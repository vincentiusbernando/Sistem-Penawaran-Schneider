<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;
    protected $table="teams";
    protected $primaryKey="id";
    public function penawarans():HasMany{
        return $this->hasMany(Penawaran::class);
    }
    public function internals():HasMany{
        return $this->hasMany(Internal::class);
    }
}
