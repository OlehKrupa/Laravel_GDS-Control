<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'action', 'old_data', 'new_data', 'table_name',];

    protected $table = 'audit_logs';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
