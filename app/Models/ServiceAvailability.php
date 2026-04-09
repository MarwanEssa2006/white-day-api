<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceAvailability extends Model
{
    protected $table      = 'service_availability';
    protected $primaryKey = 'id';
    public    $timestamps = false;

    protected $fillable = ['service_id', 'booked_date'];
}