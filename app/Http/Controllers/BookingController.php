<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function availableseats(Request $request)
    {
        // Mendefinisikan metode `availableseats` untuk memeriksa ketersediaan kursi pada suatu pertunjukan.

        if (isset($request->showid)) {
            // Memeriksa apakah parameter `showid` ada dalam request.

            $showExists = DB::table('currentmovietable')
                ->where('show_id', $request->showid)
                ->exists();
            // Memeriksa apakah pertunjukan dengan `show_id` yang diberikan ada dalam tabel `currentmovietable`.

            if (!$showExists) {
                // Jika pertunjukan tidak ada, mengembalikan tampilan error 'No such show exists!'.
                return view('errors/Nosuchseat', ['error' => 'No such show exists!']);
            }

            $seatstable = DB::table('seatstable')
                ->get(['seat_id', 'seatrow', 'seatcol']);
            // Mengambil semua kursi dari tabel `seatstable`.

            $seatprice = DB::table('currentmovietable')
                ->where('show_id', $request->showid)
                ->value('price');
            // Mengambil harga kursi untuk pertunjukan yang diberikan dari tabel `currentmovietable`.

            $showseatstable = DB::table('showseatstable')
                ->where('show_id', $request->showid)
                ->get(['status', 'seat_id']);
            // Mengambil status dan id kursi untuk pertunjukan yang diberikan dari tabel `showseatstable`.

            return view('userviews/show_seats', compact('seatstable', 'seatprice', 'showseatstable'));
            // Mengembalikan tampilan `show_seats` dengan data kursi, harga, dan status kursi.
        }
    }

    public function purchaseTicket(Request $request)
    {
        // Mendefinisikan metode `purchaseTicket` untuk pembelian tiket.

        $showId = $request->input('show_id');
        $seatIds = $request->input('seat_id');
        $userId = $request->session()->get('id');
        $totalAmount = $request->input('totalamount');
        // Mendapatkan `show_id`, `seat_id`, `user_id` dari session, dan `totalamount` dari request.

        DB::transaction(function () use ($showId, $seatIds, $userId, $totalAmount) {
            // Membuka transaksi database.

            $transactionId = DB::table('user_transaction')
                ->insertGetId([
                    'user_id' => $userId,
                    'transaction_type' => 'Buy',
                    'amount' => $totalAmount,
                    'status' => 'Pending',
                    'status_reason' => '',
                    'created_at' => now(),
                ]);
            // Menyisipkan data transaksi pengguna ke dalam tabel `user_transaction` dan mendapatkan `transaction_id`.

            foreach ($seatIds as $seatId) {
                // Melakukan iterasi pada setiap `seat_id` yang dipilih.

                $seatmapId = DB::table('showseatstable')
                    ->insertGetId([
                        'show_id' => $showId,
                        'seat_id' => $seatId,
                        'user_id' => $userId,
                        'status' => '1',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                // Menyisipkan data pemetaan kursi ke dalam tabel `showseatstable` dan mendapatkan `seatmap_id`.

                DB::table('user_tickets')
                    ->insert([
                        'transaction_id' => $transactionId,
                        'show_id' => $showId,
                        'seatmap_id' => $seatmapId,
                        'booked_time' => now(),
                    ]);
                // Menyisipkan data tiket pengguna ke dalam tabel `user_tickets`.
            }
        });

        return redirect()->route('user.tickets');
        // Mengalihkan pengguna ke rute 'user.tickets' setelah transaksi selesai.
    }
}
