<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    protected $table      = 'service_detail';
    protected $primaryKey = 'detail_id';
    public    $timestamps = false;

    protected $fillable = ['service_id', 'detail_key', 'detail_val'];
}