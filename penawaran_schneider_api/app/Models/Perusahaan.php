<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perusahaan extends Model
{
    use HasFactory;
    protected $table="perusahaans";
    public $timestamps = false;
    protected $primaryKey="id";
    public function customers():HasMany{
        return $this->hasMany(Customer::class);
    }
}
