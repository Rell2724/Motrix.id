<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    // Menampilkan saldo pengguna
    public function userbalance()
    {
        // Mendapatkan ID pengguna dari sesi
        $userId = session('id');

        // Mengambil data dompet pengguna dari database
        $wallet = DB::table('user_wallets')
            ->where('user_id', $userId)
            ->first();

        // Menampilkan tampilan saldo dengan data saldo pengguna
        return view('userviews/balance', ['currentBalance' => $wallet->amount]);
    }

    // Menambahkan saldo pengguna
    public function addUserBalance(Request $request)
    {
        // Memvalidasi input saldo
        $request->validate([
            'balance' => 'required|integer|min:0',
        ]);

        // Mendapatkan ID pengguna dari sesi
        $userId = session('id');
        // Mendapatkan saldo yang akan ditambahkan dari permintaan
        $balanceToAdd = $request->input('balance');

        // Mengambil data dompet pengguna dari database
        $wallet = DB::table('user_wallets')
            ->where('user_id', $userId)
            ->first();

        // Memeriksa apakah dompet pengguna ditemukan
        if ($wallet) {
            // Menghitung jumlah saldo baru
            $newAmount = $wallet->amount + $balanceToAdd;

            // Memperbarui saldo dompet pengguna di database
            DB::table('user_wallets')
                ->where('user_id', $userId)
                ->update(['amount' => $newAmount]);

            // Menambahkan catatan transaksi penambahan saldo
            DB::table('user_transaction')->insert([
                'user_id' => $userId,
                'transaction_type' => 'Add Balance',
                'amount' => $balanceToAdd,
                'status' => 'Success',
                'created_at' => now(),
            ]);

            // Mengembalikan respons JSON dengan saldo baru
            return response()->json(['newBalance' => $newAmount]);
        } else {
            // Mengembalikan respons JSON dengan pesan kesalahan jika dompet tidak ditemukan
            return response()->json(['error' => 'Wallet not found'], 404);
        }
    }

    // Mengambil riwayat tiket pengguna
    public function fetchUserTicketsHistory()
    {
        // Mendapatkan ID pengguna dari sesi
        $userId = session('id');

        // Memeriksa apakah pengguna terautentikasi
        if (!$userId) {
            return back()->withErrors(['message' => 'User not authenticated.']);
        }

        // Mengambil data riwayat tiket pengguna dari database
        $usertickets = DB::table('user_transaction')
            ->join('user_tickets', 'user_tickets.transaction_id', '=', 'user_transaction.transaction_id')
            ->join('currentmovietable', 'user_tickets.show_id', '=', 'currentmovietable.show_id')
            ->join('movietable', 'currentmovietable.movie_id', '=', 'movietable.movie_id')
            ->join('theatertable', 'currentmovietable.theater_id', '=', 'theatertable.theater_id')
            ->select(
                'movietable.movie_name',
                'movietable.movie_poster',
                'theatertable.theater_name',
                DB::raw('COUNT(user_tickets.id) as total_tickets'),
                'user_tickets.booked_time',
                'user_transaction.status',
                'user_transaction.transaction_id'
            )
            ->where('user_transaction.user_id', $userId)
            ->groupBy(
                'movietable.movie_name',
                'movietable.movie_poster',
                'theatertable.theater_name',
                'user_tickets.booked_time',
                'user_transaction.status',
                'user_transaction.transaction_id'
            )
            ->get();

        // Memeriksa apakah riwayat tiket ditemukan
        if ($usertickets->isEmpty()) {
            return view('userviews/ticket_history')->withErrors(['message' => 'No tickets found for the current user.']);
        }

        // Menampilkan tampilan riwayat tiket dengan data riwayat tiket pengguna
        return view('userviews/ticket_history', compact('usertickets'));
    }

    // Menampilkan detail pembayaran
    public function payment(Request $request)
    {
        // Mendapatkan ID transaksi dari permintaan
        $transactionId = $request->query('transaction_id');

        // Mengambil detail pembayaran dari database
        $paymentdetails = DB::table('user_transaction')
            ->join('user_tickets', 'user_tickets.transaction_id', '=', 'user_transaction.transaction_id')
            ->join('showseatstable', 'user_tickets.seatmap_id', '=', 'showseatstable.showseatmap')
            ->join('seatstable', 'showseatstable.seat_id', '=', 'seatstable.seat_id')
            ->join('currentmovietable', 'user_tickets.show_id', '=', 'currentmovietable.show_id')
            ->join('movietable', 'currentmovietable.movie_id', '=', 'movietable.movie_id')
            ->join('theatertable', 'currentmovietable.theater_id', '=', 'theatertable.theater_id')
            ->select(
                'movietable.movie_name',
                'movietable.movie_poster',
                'theatertable.theater_name',
                'currentmovietable.showtime',
                'user_tickets.booked_time',
                'user_transaction.amount',
                DB::raw('GROUP_CONCAT(seatstable.seatcol) as seatcols'),
                DB::raw('GROUP_CONCAT(seatstable.seatrow) as seatrows'),
                DB::raw('COUNT(user_tickets.id) as total_tickets')
            )
            ->where('user_transaction.transaction_id', $transactionId)
            ->groupBy(
                'movietable.movie_name',
                'movietable.movie_poster',
                'theatertable.theater_name',
                'currentmovietable.showtime',
                'user_tickets.booked_time',
                'user_transaction.amount',
            )
            ->first();

        // Memisahkan kolom dan baris kursi menjadi array
        $seatcols = explode(',', $paymentdetails->seatcols);
        $seatrows = explode(',', $paymentdetails->seatrows);

        // Menggabungkan kolom dan baris kursi menjadi array tunggal
        $seats = array_map(function ($col, $row) {
            return $col . $row;
        }, $seatcols, $seatrows);

        // Mengubah array kursi kembali menjadi string
        $paymentdetails->seats = implode(', ', $seats);

        // Menampilkan tampilan pembayaran tiket dengan detail pembayaran
        return view('userviews/user_tickets_payment', compact('paymentdetails'));
    }

    // Membayar tiket
    public function payTickets(Request $request)
    {
        // Mendapatkan ID pengguna dari sesi
        $userId = session('id');
        // Mendapatkan saldo yang akan dikurangi dari permintaan
        $balanceToSubtract = $request->input('balance');

        // Mengambil data dompet pengguna dari database
        $wallet = DB::table('user_wallets')
            ->where('user_id', $userId)
            ->first();

        // Memeriksa apakah saldo mencukupi
        if ($wallet->amount < $balanceToSubtract) {
            return redirect('/balance')->withErrors(['error' => 'Insufficient balance.']);
        }

        // Mengurangi saldo dompet pengguna di database
        DB::table('user_wallets')
            ->where('user_id', $userId)
            ->decrement('amount', $balanceToSubtract);

        // Mendapatkan ID transaksi dari permintaan
        $transactionId = $request->input('transaction_id');

        // Memperbarui status transaksi menjadi sukses
        DB::table('user_transaction')
            ->where('transaction_id', $transactionId)
            ->update(['status' => 'Success']);

        // Mengarahkan ke halaman riwayat
        return redirect('/history');
    }

    // Menampilkan daftar transaksi
    public function showTransactionList()
    {
        // Mengambil semua data transaksi dari database
        $transactions = DB::table('user_transaction')->get();

        // Menampilkan tampilan daftar transaksi dengan data transaksi
        return view('adminviews.transactions.transaction_list', ['title' => 'Transaction List', 'transactions' => $transactions]);
    }

    // Mengambil detail transaksi
    public function fetchTransactionDetails($id)
    {
        // Mengambil detail transaksi berdasarkan ID dari database
        $transactiondetails = DB::table('user_transaction')
            ->leftJoin('user', 'user_transaction.user_id', '=', 'user.id')
            ->where('user_transaction.transaction_id', $id)
            ->select(
                'user_transaction.*',
                DB::raw('COALESCE(user.username, "No User") as username'),
                DB::raw('COALESCE(user.name, "No User") as name')
            )
            ->first();

        // Menampilkan tampilan detail transaksi dengan data transaksi
        return view('adminviews/transactions/transaction_details', ['title' => 'Transaction Details', 'transactiondetails' => $transactiondetails]);
    }

    // Mengambil tiket pengguna
    public function fetchUserTickets()
    {
        // Mengambil semua data tiket pengguna dari database
        $fetchTickets = DB::table('user_tickets')
            ->join('user_transaction', 'user_tickets.transaction_id', '=', 'user_transaction.transaction_id')
            ->join('currentmovietable', 'user_tickets.show_id', '=', 'currentmovietable.show_id')
            ->join('movietable', 'currentmovietable.movie_id', '=', 'movietable.movie_id')
            ->join('theatertable', 'currentmovietable.theater_id', '=', 'theatertable.theater_id')
            ->join('showseatstable', 'user_tickets.seatmap_id', '=', 'showseatstable.showseatmap')
            ->join('seatstable', 'showseatstable.seat_id', '=', 'seatstable.seat_id')
            ->select(
                'user_tickets.*',
                'user_tickets.transaction_id',
                'user_transaction.user_id',
                'movietable.movie_name',
                'theatertable.theater_name',
                'seatstable.seatrow',
                'seatstable.seatcol',
                'user_tickets.booked_time'
            )
            ->get();

        // Menampilkan tampilan daftar tiket dengan data tiket
        return view('adminviews/tickets/ticket_list', ['title' => 'All Tickets'], compact('fetchTickets'));
    }

    // Menampilkan formulir pengeditan tiket pengguna
    public function editUserTicket(Request $request)
    {
        // Mengambil detail tiket pengguna berdasarkan ID dari permintaan
        $ticketdetails = DB::table('user_tickets')
            ->join('user_transaction', 'user_tickets.transaction_id', '=', 'user_transaction.transaction_id')
            ->join('currentmovietable', 'user_tickets.show_id', '=', 'currentmovietable.show_id')
            ->join('movietable', 'currentmovietable.movie_id', '=', 'movietable.movie_id')
            ->join('theatertable', 'currentmovietable.theater_id', '=', 'theatertable.theater_id')
            ->join('showseatstable', 'user_tickets.seatmap_id', '=', 'showseatstable.showseatmap')
            ->join('seatstable', 'showseatstable.seat_id', '=', 'seatstable.seat_id')
            ->where('user_tickets.id', $request->id)
            ->select(
                'user_tickets.*',
                'seatstable.seat_id',
                'currentmovietable.show_id',
                'showseatstable.showseatmap',
                'user_tickets.transaction_id',
                'user_transaction.user_id',
                'movietable.movie_name',
                'theatertable.theater_name',
                'seatstable.seatrow',
                'seatstable.seatcol',
                'user_tickets.booked_time'
            )
            ->first();

        // Mengambil data semua kursi dari database
        $seatstable = DB::table('seatstable')
            ->get(['seat_id', 'seatrow', 'seatcol']);

        // Mengambil data kursi yang tersedia untuk show tertentu dari database
        $showseatstable = DB::table('showseatstable')
            ->where('show_id', $ticketdetails->show_id)
            ->get(['status', 'seat_id']);

        // Menampilkan tampilan edit tiket dengan data detail tiket, kursi, dan showseat
        return view('adminviews/tickets/ticket_edit', ['title' => 'Edit Ticket'], compact('ticketdetails', 'seatstable', 'showseatstable'));
    }

    // Memperbarui detail tiket pengguna
    public function updateUserTicket(Request $request, $id)
    {
        // Mendapatkan ID kursi dari permintaan
        $seat_id = $request->input('seat_id');

        // Mengambil data seatmap berdasarkan ID tiket
        $seatmap = DB::table('user_tickets')
            ->where('id', $id)
            ->select('seatmap_id')
            ->first();

        // Memeriksa apakah seatmap ditemukan
        if (!$seatmap) {
            return back()->withErrors(['seat_id' => 'Invalid seat selection.']);
        }

        // Memperbarui data kursi di showseatstable
        DB::table('showseatstable')
            ->where('showseatmap', $seatmap->seatmap_id)
            ->update(['seat_id' => $seat_id]);

        // Mengarahkan ke halaman daftar tiket dengan pesan sukses
        return redirect()->route('ticket.list')->with('success', 'Ticket updated successfully');
    }
}
