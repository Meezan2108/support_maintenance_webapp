<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    use HasFactory;

    const MENU_TYPE = [
        0 => "Link",
        1 => "Header",
        2 => "Dropdown"
    ];

    protected $table = "menu";

    protected $fillable = ["parent_id", "code", "name", "icon", "description", "order", "type"];

    public function permission(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, "menu_has_permission", "menu_id", "permission_id");
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}
