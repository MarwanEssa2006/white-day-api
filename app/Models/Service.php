<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table      = 'service';
    protected $primaryKey = 'service_id';
    public    $timestamps = false;

    protected $fillable = [
        'service_id', 's_name', 'description', 'contact_phone',
        'contact_email', 'price', 'city', 'location', 'user_id', 't_id',
    ];

    public function type()
    {
        return $this->belongsTo(ServiceType::class, 't_id', 't_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function media()
    {
        return $this->hasMany(ServiceMedia::class, 'service_id', 'service_id');
    }

    public function details()
    {
        return $this->hasMany(ServiceDetail::class, 'service_id', 'service_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'service_id', 'service_id');
    }

    public function availability()
    {
        return $this->hasMany(ServiceAvailability::class, 'service_id', 'service_id');
    }
}