<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'floor'
    ];

    public function user_info()
    {
        return $this->hasOne(UserInfo::class);
    }

    public function accessory_list()
    {
        return $this->hasOne(AccessoryList::class);
    }
}
