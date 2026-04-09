<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceMedia extends Model
{
    protected $table      = 'service_media';
    protected $primaryKey = 'media_id';
    public    $timestamps = false;

    protected $fillable = ['service_id', 'file_path', 'file_type', 'sort_order'];
}