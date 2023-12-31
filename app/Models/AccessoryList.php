<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryList extends Model
{
    use HasFactory;

    protected $fillable = [
        'accessory_id', 'floor', 'quantity', 'remind_limit'
    ];

    public function accessory()
    {
        return $this->belongsTo(Accessory::class);
    }
}
