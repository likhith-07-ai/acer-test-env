<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'group',
        'description',
    ];

    /**
     * Users that have this permission
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Scope for filtering by group
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
