<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'accessory_id', 'count'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function accessory() {
        return $this->belongsTo(Accessory::class);
    }

}
