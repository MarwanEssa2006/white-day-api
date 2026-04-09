<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table      = 'booking';
    protected $primaryKey = 'booking_id';
    public    $timestamps = false;

    protected $fillable = [
        'status',
        'booking_date',
        'delivery_date',
        'total_amount',
        'notes',
        'user_id',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'booking_includes',
            'booking_id',
            'service_id'
        )->withPivot('quantity', 'unit_price');
    }

    public function payment()
    {
        return $this->hasOne(WalletPayment::class, 'booking_id', 'booking_id');
    }

    public function dispute()
    {
        return $this->hasOne(Dispute::class, 'booking_id', 'booking_id');
    }
}