<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrentShows extends Model
{
    protected $table = 'currentmovietable';
    protected $primaryKey = 'show_id';
    protected $columns = ['showtime', 'movie_id', 'theater_id'];
}
