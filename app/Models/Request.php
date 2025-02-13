<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'request_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'request_number',
        'user_name_at_request',
        'user_email_at_request',
        'user_photo_at_request',
    ];

    protected $dates = [
        'request_date',
        'expected_return_date',
        'actual_return_date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
