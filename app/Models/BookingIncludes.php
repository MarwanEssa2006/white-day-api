<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookingIncludes extends Model
{
    protected $table      = 'booking_includes';
    public    $timestamps = false;

    protected $fillable = [
        'booking_id',
        'service_id',
        'quantity',
        'unit_price',
    ];
}