<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gassiness extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'MPR',
        'measurements',
        'device',
        'factory_number',
        'user_id',
        'user_station_id',
    ];

    protected $casts = [
        'measurements' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'user_station_id');
    }
}
