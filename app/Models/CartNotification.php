<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartNotification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'cart_last_updated', 'notified'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
