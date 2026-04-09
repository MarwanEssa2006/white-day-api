<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $table      = 'service_type';
    protected $primaryKey = 't_id';
    public    $timestamps = false;

    protected $fillable = ['service_name'];

    public function services()
    {
        return $this->hasMany(Service::class, 't_id', 't_id');
    }
}