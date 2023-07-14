<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'image'
    ];

    public function accessory_list() {
        return $this->hasOne(AccessoryList::class);
    }
}
