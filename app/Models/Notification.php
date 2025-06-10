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
    protected $primaryKey = 'notification_id';
    //định nghĩa các quan hệ với các model khác
   public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
