<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo',
        'user_id',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Crypt::encryptString($value);
    }

    public function getNameAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'author_book');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
