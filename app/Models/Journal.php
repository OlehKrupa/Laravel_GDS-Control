<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'journal';

    protected $fillable = [
        'pressure_in',
        'pressure_out_1',
        'pressure_out_2',
        'temperature_1',
        'temperature_2',
        'odorant_value_1',
        'odorant_value_2',
        'gas_heater_temperature_in',
        'gas_heater_temperature_out',
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
