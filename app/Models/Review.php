<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table      = 'review';
    protected $primaryKey = 'review_id';
    public    $timestamps = false;

    protected $fillable = [
        'rate_star',
        'comment',
        'user_id',
        'service_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }
}