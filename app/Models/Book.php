<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'bibliography',
        'cover_image',
        'price',
        'author_id', 
        'publisher_id',
        'user_id',
    ];

    public function setBibliographyAttribute($value)
    {
        $this->attributes['bibliography'] = Crypt::encryptString($value);
    }

    public function getBibliographyAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
