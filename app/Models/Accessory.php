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

    public function accessory_lists() {
        return $this->hasMany(AccessoryList::class);
    }

    public function records() {
        return $this->hasMany(Record::class);
    }
}
