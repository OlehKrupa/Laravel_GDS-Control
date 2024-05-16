<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notes extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'operational_switching',
        'received_orders',
        'completed_works',
        'visits_by_outsiders',
        'inspection_of_pressure_tanks',
        'user_id',
        'user_station_id',
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
