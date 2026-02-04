<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\HasStatus;
use App\Traits\SerializableDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, SerializableDate, HasStatus;

    protected $fillable = [
        'menu',
        'hierarchy',
        'parent',
        'permission_id',
        'icon',
        'status'
    ];

    // cast for status attribute
    protected $casts = [
        'status' => Status::class,
    ];

    /**
     * Get the permission that owns the menu.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Get the child menus for the menu.
     *
     * @return  [type]  Retorna los menús hijos de este menú.
     */
    public function childrenMenus()
    {
        return $this->hasMany(Menu::class, 'parent', 'id');
    }

    /**
     * Get all descendant menus recursively.
     *
     * @return  [type]  Retorna todos los menús descendientes de este menú.
     */
    public function allChildrenMenus()
    {
        return $this->childrenMenus()->with('allChildrenMenus');
    }
}
