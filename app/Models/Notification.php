<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'notification_id',
        'admin',
        'target',
        'title',
        'message',
        'notification_date',
        'status',
    ];
     public function User()
    {
        return $this->belongsTo(User::class);
    }
}
