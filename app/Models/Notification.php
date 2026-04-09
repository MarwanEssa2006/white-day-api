<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table      = 'notification';
    protected $primaryKey = 'notification_id';
    public    $timestamps = false;

    protected $fillable = [
        'message', 'is_read', 'user_id',
    ];
}