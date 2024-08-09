<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    // Menampilkan halaman utama pengguna
    public function index(Request $request)
    {
        // Mengambil film yang sedang tayang dengan banner yang tidak null
        $movies = DB::table('currentmovietable')
            ->join('movietable', 'currentmovietable.movie_id', '=', 'movietable.movie_id')
            ->whereNotNull('movietable.movie_banner')
            ->take(1)
            ->get();

        // Mengambil data sesi pengguna
        $userdata = $request->session()->all();

        // Mengambil saldo pengguna dari database
        $userbalance = DB::table('user_wallets')
            ->select('amount')
            ->where('user_id', $userdata['id'])
            ->first();

        // Mengambil 6 film terbaik minggu ini
        $top4movies = DB::table('movietable')
            ->select('movie_id', 'movie_name', 'genre', 'movie_poster')
            ->where('movie_thisweeks', '1')
            ->take(6)
            ->get();

        // Menampilkan tampilan halaman utama pengguna dengan data film, saldo, dan data pengguna
        return view('userviews/user_homepage', compact('top4movies', 'userdata', 'movies', 'userbalance'));
    }

    // Menampilkan halaman acara film
    public function shows(Request $request)
    {
        // Mengambil data sesi pengguna
        $userdata = $request->session()->all();

        // Mengambil saldo pengguna dari database
        $userbalance = DB::table('user_wallets')
            ->select('amount')
            ->where('user_id', $userdata['id'])
            ->first();

        // Mengambil film yang sedang tayang
        $movies = DB::table('currentmovietable')
            ->join('movietable', 'currentmovietable.movie_id', '=', 'movietable.movie_id')
            ->select('movietable.movie_id', 'movietable.movie_name', 'movietable.genre', 'movietable.movie_poster')
            ->where('currentmovietable.status', '=', 1)
            ->distinct()
            ->get();

        // Menampilkan tampilan halaman acara dengan data film, saldo, dan data pengguna
        return view('userviews/shows', compact('movies', 'userdata', 'userbalance'));
    }

    // Menampilkan halaman pembelian makanan ringan
    public function snacks(Request $request)
    {
        // Mengambil data sesi pengguna
        $userdata = $request->session()->all();

        // Mengambil saldo pengguna dari database
        $userbalance = DB::table('user_wallets')
            ->select('amount')
            ->where('user_id', $userdata['id'])
            ->first();

        // Menampilkan tampilan halaman pembelian makanan ringan dengan data pengguna dan saldo
        return view('userviews/buy_snack', compact('userdata', 'userbalance'));
    }

    // Menampilkan halaman notifikasi pengguna
    public function notification()
    {
        // Menampilkan tampilan halaman notifikasi pengguna
        return view('userviews.user_notification');
    }

    // Menampilkan halaman favorit pengguna
    public function favorites(Request $request)
    {
        // Mengambil data sesi pengguna
        $userdata = $request->session()->all();

        // Mengambil saldo pengguna dari database
        $userbalance = DB::table('user_wallets')
            ->select('amount')
            ->where('user_id', $userdata['id'])
            ->first();

        // Mengambil film favorit pengguna
        $favmovies = DB::table('movietable')
            ->join('userfavmovie', 'userfavmovie.movie_id', '=', 'movietable.movie_id')
            ->where('userfavmovie.user_id', $userdata['id'])
            ->get();

        // Menampilkan tampilan halaman favorit dengan data pengguna, saldo, dan film favorit
        return view('userviews/user_favorites', compact('userdata', 'userbalance', 'favmovies'));
    }
}
