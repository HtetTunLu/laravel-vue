<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryList extends Model
{
    use HasFactory;

    protected $fillable = [
        'accessory_id', 'team_id', 'quantity', 'remind_limit'
    ];

    public function accessory()
    {
        return $this->belongsTo(Accessory::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
