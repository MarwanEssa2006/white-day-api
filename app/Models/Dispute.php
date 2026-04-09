<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $table      = 'dispute';
    protected $primaryKey = 'dispute_id';
    public    $timestamps = false;

    protected $fillable = [
        'issue_description',
        'manager_decision',
        'user_id',
        'booking_id',
    ];
}