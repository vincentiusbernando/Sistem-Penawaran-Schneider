<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Internal extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = "internals";
    protected $primaryKey = "id";
    protected $hidden = [
        'password',
    ];
    public function penawarans(): HasMany
    {
        return $this->hasMany(Penawaran::class);
    }
    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function getAuthIdentifierName()
    {
        return 'email';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
