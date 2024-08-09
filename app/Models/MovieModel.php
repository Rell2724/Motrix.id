<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieModel extends Model
{
    protected $table = 'movietable';
    protected $primaryKey = 'movie_id';
    protected $fillable = ['movie_name', 'movie_banner', 'movie_trailer', 'release_year', 'genre', 'movie_synopsis'];
}
