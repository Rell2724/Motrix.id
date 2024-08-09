<?php

namespace App\Http\Controllers;
// Mendeklarasikan namespace `App\Http\Controllers` untuk mengatur kode dalam aplikasi.

use App\Models\CurrentShows;
use App\Models\MovieModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// Menggunakan beberapa model dan fasad yang diperlukan dalam kontroler seperti `CurrentShows`, `MovieModel`, `Request`, `DB`, `Storage`, dan `Log`.

class MovieController extends Controller
{
    // Mendeklarasikan kelas `MovieController` yang merupakan turunan dari `Controller`.

    public function findshows(Request $request)
    {
        // Mendefinisikan metode `findshows` yang menerima objek `Request` sebagai parameter.

        if (isset($request->val)) {
            // Memeriksa apakah parameter `val` ada dalam objek `Request`.

            $moviedetails = DB::table('movietable')
                ->where('movie_id', $request->val)
                ->first();
            // Mengambil detail film dari tabel `movietable` berdasarkan `movie_id` yang diterima dari request.

            $user_id = session()->get('id');
            $is_favorited = DB::table('userfavmovie')
                ->where('user_id', $user_id)
                ->where('movie_id', $request->val)
                ->count();
            // Mengambil `user_id` dari sesi dan memeriksa apakah film tersebut sudah difavoritkan oleh pengguna.

            $data['is_favorited'] = $is_favorited > 0 ? 1 : 0;
            // Mengatur nilai `is_favorited` dalam array data berdasarkan hasil perhitungan.

            $shows = DB::table('currentmovietable')
                ->join('movietable', 'currentmovietable.movie_id', '=', 'movietable.movie_id')
                ->join('theatertable', 'currentmovietable.theater_id', '=', 'theatertable.theater_id')
                ->where('currentmovietable.movie_id', $request->val)
                ->select(
                    'currentmovietable.movie_id',
                    'theatertable.theater_name',
                    'theatertable.theater_address',
                    'currentmovietable.showtime',
                    'currentmovietable.show_id',
                    'currentmovietable.theater_id',
                    'currentmovietable.price'
                )
                ->get();
            // Mengambil daftar pertunjukan dari tabel `currentmovietable` yang di-join dengan `movietable` dan `theatertable` berdasarkan `movie_id`.
        }
        return view('userviews/current_show', compact('shows', 'moviedetails', 'data'));
        // Mengembalikan tampilan `current_show` dengan data `shows`, `moviedetails`, dan `data`.
    }

    public function add_favorite(Request $request)
    {
        // Mendefinisikan metode `add_favorite` yang menerima objek `Request` sebagai parameter.

        $user_id = $request->input('user_id');
        $movie_id = $request->input('movie_id');
        // Mengambil `user_id` dan `movie_id` dari input request.

        if (!$user_id || !$movie_id) {
            return response('Bad Request', 400);
        }
        // Memeriksa apakah `user_id` dan `movie_id` ada, jika tidak, mengembalikan respons "Bad Request" dengan status 400.

        $data = [
            'user_id' => $user_id,
            'movie_id' => $movie_id
        ];
        // Membuat array `data` yang berisi `user_id` dan `movie_id`.

        DB::table('userfavmovie')->insert($data);
        // Menyisipkan data ke dalam tabel `userfavmovie`.

        return response('Favorite added', 200);
        // Mengembalikan respons "Favorite added" dengan status 200.
    }

    public function remove_favorite(Request $request)
    {
        // Mendefinisikan metode `remove_favorite` yang menerima objek `Request` sebagai parameter.

        $user_id = $request->input('user_id');
        $movie_id = $request->input('movie_id');
        // Mengambil `user_id` dan `movie_id` dari input request.

        if (!$user_id || !$movie_id) {
            return response('Bad Request', 400);
        }
        // Memeriksa apakah `user_id` dan `movie_id` ada, jika tidak, mengembalikan respons "Bad Request" dengan status 400.

        DB::table('userfavmovie')
            ->where('user_id', $user_id)
            ->where('movie_id', $movie_id)
            ->delete();
        // Menghapus entri dari tabel `userfavmovie` berdasarkan `user_id` dan `movie_id`.

        return response('Favorite removed', 200);
        // Mengembalikan respons "Favorite removed" dengan status 200.
    }

    // Admin - Movie CRUD

    public function movietable()
    {
        // Mendefinisikan metode `movietable` untuk mengambil data film dari tabel `movietable`.

        $movies = DB::table('movietable')
            ->select(
                'movie_id',
                'movie_name',
                'release_date',
                'genre',
                DB::raw('CASE WHEN EXISTS (SELECT * FROM currentmovietable WHERE currentmovietable.movie_id = movietable.movie_id) THEN "Yes" ELSE "No" END AS is_current')
            )
            ->groupBy('movie_id', 'movie_name', 'release_date', 'genre')
            ->get();
        // Mengambil data film dengan kolom yang diperlukan dan menambahkan kolom `is_current` untuk menandai apakah film saat ini sedang diputar.

        return view('adminviews/movies/movie_list', compact('movies'), ['title' => 'Movie List']);
        // Mengembalikan tampilan `movie_list` dengan data `movies` dan judul 'Movie List'.
    }

    public function createform()
    {
        // Mendefinisikan metode `createform` untuk mengembalikan tampilan formulir pembuatan film baru.

        return view('adminviews/movies/movie_create', ['title' => 'Store New Movie']);
        // Mengembalikan tampilan `movie_create` dengan judul 'Store New Movie'.
    }

    public function store(Request $request)
    {
        // Mendefinisikan metode `store` untuk menyimpan film baru ke dalam database.

        $bannerPath = $request->file('movie_banner')->store('public/img/moviebanner');
        $posterPath = $request->file('movie_poster')->store('public/img/movieposter');
        // Menyimpan file banner dan poster film ke dalam direktori yang ditentukan.

        DB::table('movietable')->insert([
            'movie_name' => $request->input('movie_name'),
            'movie_banner' => str_replace('public/', '', $bannerPath),
            'movie_poster' => str_replace('public/', '', $posterPath),
            'movie_trailer' => $request->input('movie_trailer'),
            'release_date' => $request->input('release_date'),
            'genre' => $request->input('genre'),
            'movie_synopsis' => $request->input('movie_synopsis'),
            'movie_thisweeks' => $request->input('movie_thisweeks'),
        ]);
        // Menyisipkan data film baru ke dalam tabel `movietable`.

        return redirect()->route('admin')->with('success', 'Movie added successfully!');
        // Mengalihkan kembali ke rute admin dengan pesan sukses 'Movie added successfully!'.
    }

    public function edit($id)
    {
        // Mendefinisikan metode `edit` untuk mengambil data film yang akan diedit berdasarkan `movie_id`.

        $selectedmovie = DB::table('movietable')->where('movie_id', $id)->first();
        // Mengambil data film yang dipilih dari tabel `movietable` berdasarkan `movie_id`.

        return view('adminviews/movies/movie_edit', ['title' => 'Edit movie data'], compact('selectedmovie'));
        // Mengembalikan tampilan `movie_edit` dengan data `selectedmovie` dan judul 'Edit movie data'.
    }

    public function update(Request $request, $id)
    {
        // Mendefinisikan metode `update` untuk memperbarui data film berdasarkan `movie_id`.

        DB::table('movietable')
            ->where('movie_id', $id)
            ->update([
                'movie_name' => $request->input('movie_name'),
                'genre' => $request->input('genre'),
                'release_date' => $request->input('release_date'),
                'movie_synopsis' => $request->input('movie_synopsis'),
            ]);
        // Memperbarui data film dalam tabel `movietable`.

        if ($request->hasFile('movie_banner')) {
            $bannerPath = $request->file('movie_banner')->store('public/img/moviebanner');
            $BpublicPath = 'storage/' . substr($bannerPath, strlen('public/'));
            DB::table('movietable')
                ->where('movie_id', $id)
                ->update(['movie_banner' => str_replace('public/', '', $BpublicPath)]);
            // Memperbarui path banner film jika file baru diunggah.
        }

        if ($request->hasFile('movie_poster')) {
            $posterPath = $request->file('movie_poster')->store('public/img/movieposters');
            $PpublicPath = 'storage/' . substr($posterPath, strlen('public/'));
            DB::table('movietable')
                ->where('movie_id', $id)
                ->update(['movie_poster' => str_replace('public/', '', $PpublicPath)]);
            // Memperbarui path poster film jika file baru diunggah.
        }

        return redirect()->route('movie.list')->with('success', 'Movie updated successfully.');
        // Mengalihkan kembali ke rute daftar film dengan pesan sukses 'Movie updated successfully.'.
    }

    public function destroy($id)
    {
        // Mendefinisikan metode `destroy` untuk menghapus film berdasarkan `movie_id`.

        DB::table('movietable')->where('movie_id', $id)->delete();
        // Menghapus data film dari tabel `movietable` berdasarkan `movie_id`.

        return redirect()->route('movie.list')->with('success', 'Movie deleted successfully.');
        // Mengalihkan kembali ke rute daftar film dengan pesan sukses 'Movie deleted successfully.'.
    }

    // Admin - CurrentMovie CRUD

    public function currentmovie()
    {
        // Mendefinisikan metode `currentmovie` untuk mengambil data pertunjukan film saat ini.

        $shows = DB::table('currentmovietable')
            ->join('movietable', 'currentmovietable.movie_id', '=', 'movietable.movie_id')
            ->join('theatertable', 'currentmovietable.theater_id', '=', 'theatertable.theater_id')
            ->select('movietable.movie_name', 'theatertable.theater_name', 'currentmovietable.showtime', 'currentmovietable.price', 'currentmovietable.show_id')
            ->get();
        // Mengambil data pertunjukan film yang di-join dengan tabel `movietable` dan `theatertable`.

        return view('adminviews/movies/currentmovie_list', compact('shows'), ['title' => 'Current Movie List']);
        // Mengembalikan tampilan `currentmovie_list` dengan data `shows` dan judul 'Current Movie List'.
    }

    public function createshow()
    {
        // Mendefinisikan metode `createshow` untuk mengembalikan tampilan formulir pembuatan pertunjukan baru.

        $movies = DB::table('movietable')->get();
        $theaters = DB::table('theatertable')->get();
        // Mengambil data semua film dan teater dari tabel `movietable` dan `theatertable`.

        return view('adminviews/movies/currentmovie_create', compact('movies', 'theaters'), ['title' => 'Create new show']);
        // Mengembalikan tampilan `currentmovie_create` dengan data `movies`, `theaters`, dan judul 'Create new show'.
    }

    public function editshow(Request $request)
    {
        // Mendefinisikan metode `editshow` untuk mengambil data pertunjukan yang akan diedit berdasarkan `show_id`.

        $movies = DB::table('movietable')->get();
        $theaters = DB::table('theatertable')->get();
        // Mengambil data semua film dan teater dari tabel `movietable` dan `theatertable`.

        $selectedshows = DB::table('currentmovietable')
            ->join('movietable', 'currentmovietable.movie_id', '=', 'movietable.movie_id')
            ->join('theatertable', 'currentmovietable.theater_id', '=', 'theatertable.theater_id')
            ->where('show_id', $request->id)
            ->select('currentmovietable.show_slug', 'movietable.movie_id', 'theatertable.theater_id', 'movietable.movie_name', 'theatertable.theater_name', 'currentmovietable.showtime', 'currentmovietable.price', 'currentmovietable.show_id')
            ->first();
        // Mengambil data pertunjukan yang dipilih dari tabel `currentmovietable` yang di-join dengan `movietable` dan `theatertable` berdasarkan `show_id`.

        return view('adminviews/movies/currentmovie_edit', compact('selectedshows', 'movies', 'theaters'), ['title' => 'Edit show']);
        // Mengembalikan tampilan `currentmovie_edit` dengan data `selectedshows`, `movies`, `theaters`, dan judul 'Edit show'.
    }

    public function updateshow(Request $request, $id)
    {
        // Mendefinisikan metode `updateshow` untuk memperbarui data pertunjukan berdasarkan `show_id`.

        DB::table('currentmovietable')
            ->where('show_id', $id)
            ->update([
                'movie_id' => $request->movie_id,
                'theater_id' => $request->theater_id,
                'price' => $request->price,
                'showtime' => $request->showtime,
                'show_slug' => $request->show_slug,
            ]);
        // Memperbarui data pertunjukan dalam tabel `currentmovietable`.

        return redirect()->route('currentmovie.list')->with('success', 'Current movie updated successfully!');
        // Mengalihkan kembali ke rute daftar pertunjukan dengan pesan sukses 'Current movie updated successfully!'.
    }

    public function storeshow(Request $request)
    {
        // Mendefinisikan metode `storeshow` untuk menyimpan pertunjukan baru ke dalam database.

        $request->validate([
            'movie_id' => 'required|exists:movietable,movie_id',
            'theater_id' => 'required|exists:theatertable,theater_id',
            'price' => 'required|numeric',
            'showtime' => 'required|date',
        ]);
        // Validasi input request untuk memastikan data yang diterima sesuai dengan aturan.

        DB::table('currentmovietable')->insert([
            'movie_id' => $request->movie_id,
            'theater_id' => $request->theater_id,
            'price' => $request->price,
            'showtime' => $request->showtime,
        ]);
        // Menyisipkan data pertunjukan baru ke dalam tabel `currentmovietable`.

        return redirect()->route('currentmovie.list')->with('success', 'Current movie stored successfully!');
        // Mengalihkan kembali ke rute daftar pertunjukan dengan pesan sukses 'Current movie stored successfully!'.
    }

    public function deleteshow($id)
    {
        // Mendefinisikan metode `deleteshow` untuk menghapus pertunjukan berdasarkan `show_id`.

        DB::table('currentmovietable')->where('show_id', $id)->delete();
        // Menghapus data pertunjukan dari tabel `currentmovietable` berdasarkan `show_id`.

        return redirect()->route('currentmovie.list')->with('success', 'Current movie deleted successfully!');
        // Mengalihkan kembali ke rute daftar pertunjukan dengan pesan sukses 'Current movie deleted successfully!'.
    }

    // Admin - Theater CRUD

    public function theaterlist()
    {
        // Mendefinisikan metode `theaterlist` untuk mengambil daftar teater.

        $theaterlist = DB::table('theatertable')->get();
        // Mengambil semua data teater dari tabel `theatertable`.

        return view('adminviews/theaters/theater_list', compact('theaterlist'), ['title' => 'Theater List']);
        // Mengembalikan tampilan `theater_list` dengan data `theaterlist` dan judul 'Theater List'.
    }

    public function createtheater()
    {
        // Mendefinisikan metode `createtheater` untuk mengembalikan tampilan formulir pembuatan teater baru.

        return view('adminviews/theaters/theater_create', ['title' => 'Create New Theater']);
        // Mengembalikan tampilan `theater_create` dengan judul 'Create New Theater'.
    }

    public function theaterstore(Request $request)
    {
        // Mendefinisikan metode `theaterstore` untuk menyimpan teater baru ke dalam database.

        DB::table('theatertable')->insert([
            'theater_slug' => $request->input('theater_slug'),
            'total_seats' => $request->input('total_seats'),
            'theater_name' => $request->input('theater_name'),
            'theater_address' => $request->input('theater_address'),
            'city' => $request->input('city'),
        ]);
        // Menyisipkan data teater baru ke dalam tabel `theatertable`.

        return redirect()->route('theater.list')->with('success', 'Theater added successfully!');
        // Mengalihkan kembali ke rute daftar teater dengan pesan sukses 'Theater added successfully!'.
    }

    public function theateredit(Request $request)
    {
        // Mendefinisikan metode `theateredit` untuk mengambil data teater yang akan diedit berdasarkan `theater_id`.

        $theaterdetails = DB::table('theatertable')->where('theater_id', $request->id)->first();
        // Mengambil data teater yang dipilih dari tabel `theatertable` berdasarkan `theater_id`.

        return view('adminviews/theaters/theater_edit', compact('theaterdetails'), ['title' => 'Edit Theater']);
        // Mengembalikan tampilan `theater_edit` dengan data `theaterdetails` dan judul 'Edit Theater'.
    }

    public function theaterupdate(Request $request, $id)
    {
        // Mendefinisikan metode `theaterupdate` untuk memperbarui data teater berdasarkan `theater_id`.

        DB::table('theatertable')
            ->where('theater_id', $id)
            ->update([
                'theater_slug' => $request->input('theater_slug'),
                'total_seats' => $request->input('total_seats'),
                'theater_name' => $request->input('theater_name'),
                'theater_address' => $request->input('theater_address'),
                'city' => $request->input('city'),
            ]);
        // Memperbarui data teater dalam tabel `theatertable`.

        return redirect()->route('theater.list')->with('success', 'Theater updated successfully!');
        // Mengalihkan kembali ke rute daftar teater dengan pesan sukses 'Theater updated successfully!'.
    }

    public function theaterdelete($id)
    {
        // Mendefinisikan metode `theaterdelete` untuk menghapus teater berdasarkan `theater_id`.

        DB::table('theatertable')->where('theater_id', $id)->delete();
        // Menghapus data teater dari tabel `theatertable` berdasarkan `theater_id`.

        return redirect()->route('theater.list')->with('success', 'Theater deleted successfully!');
        // Mengalihkan kembali ke rute daftar teater dengan pesan sukses 'Theater deleted successfully!'.
    }
}
