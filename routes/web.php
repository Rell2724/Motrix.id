<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use App\Models\UserModel;

Route::get('', function () {
    return redirect('/login');
});
//Register Route
Route::get('/register', [UserController::class, 'register'])->name('register.page');
Route::post('/register', [UserController::class, 'register'])->name('register.post');

//Admin login route
Route::get('/adminlogin', [UserController::class, 'login'])->name('adminlogin.page');
Route::post('/adminlogin', [UserController::class, 'login'])->name('adminlogin.post');

//User login route
Route::get('/login', [UserController::class, 'userLogin'])->name('userlogin.page');
Route::post('/login', [UserController::class, 'userLogin'])->name('userlogin.post');

//Logout Route
Route::get('/logout', [UserController::class, 'logout']);


Route::get('index', [PageController::class, 'index']);
Route::get('shows', [PageController::class, 'shows']);
Route::get('favorites', [PageController::class, 'favorites']);
Route::get('notification', [PageController::class, 'notification']);

Route::get('snacks', [PageController::class, 'snacks']);

Route::get('setting/account', [UserController::class, 'usersetting'])->name('setting.account');
Route::post('setting/account', [UserController::class, 'usersetting'])->name('setting.account_post');
Route::get('setting/balance', [TransactionsController::class, 'userbalance'])->name('setting.balance');
Route::post('adduserbalance', [TransactionsController::class, 'addUserBalance'])->name('adduserbalance');

Route::post('addfavorite', [MovieController::class, 'add_favorite']);
Route::post('removefavorite', [MovieController::class, 'remove_favorite']);

Route::get('findshows', [MovieController::class, 'findshows']);
Route::get('findseats', [BookingController::class, 'availableseats']);

Route::post('bookseats', [BookingController::class, 'purchaseTicket'])->name('user.bookseats');
Route::get('payment', [TransactionsController::class, 'payment'])->name('user.payment');
Route::post('paytickets', [TransactionsController::class, 'payTickets'])->name('user.paytickets');
Route::get('history', [TransactionsController::class, 'fetchUserTicketsHistory'])->name('user.tickets');

Route::middleware(['userAuth'])->group(function () {
});


Route::middleware(['adminAuth'])->group(function () {

    //Admin Dashboard
    Route::get('admin', [UserController::class, 'adminDashboard']);


    //Users
    Route::get('admin/userlist', [UserController::class, 'userlist'])->name('users.userlist');
    Route::get('admin/user/create', [UserController::class, 'create'])->name('users.create');
    //User CRUD
    Route::post('admin/user/store', [UserController::class, 'store'])->name('users.store');
    Route::get('admin/user/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('admin/user/{id}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('admin/user/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');


    //Movie
    Route::get('admin/movielist', [MovieController::class, 'movietable'])->name('movie.list');
    Route::get('admin/movie/create', [MovieController::class, 'createform'])->name('movie.create');
    //Movie CRUD
    Route::post('admin/movie/store', [MovieController::class, 'store'])->name('movie.store');
    Route::get('admin/movie/{id}/edit', [MovieController::class, 'edit'])->name('movie.edit');
    Route::put('admin/movie/{id}/update', [MovieController::class, 'update'])->name('movie.update');
    Route::delete('admin/movie/{id}/delete', [MovieController::class, 'destroy'])->name('movie.destroy');


    //CurrentMovie
    Route::get('admin/currentmovielist', [MovieController::class, 'currentmovie'])->name('currentmovie.list');
    Route::get('admin/currentmovie/create', [MovieController::class, 'createshow'])->name('currentmovie.create');
    //CurrentMovie CRUD
    Route::post('admin/currentmovie/store', [MovieController::class, 'storeshow'])->name('currentmovie.store');
    Route::get('admin/currentmovie/{id}/edit', [MovieController::class, 'editshow'])->name('currentmovie.edit');
    Route::put('admin/currentmovie/{id}/update', [MovieController::class, 'updateshow'])->name('currentmovie.update');
    Route::delete('admin/currentmovie/{id}/delete', [MovieController::class, 'deleteshow'])->name('currentmovie.destroy');


    //Theater
    Route::get('admin/theaterlist', [MovieController::class, 'theaterlist'])->name('theater.list');
    Route::get('admin/theater/create', [MovieController::class, 'createtheater'])->name('theater.create');
    //Theater CRUD
    Route::post('admin/theater/store', [MovieController::class, 'theaterstore'])->name('theater.store');
    Route::get('admin/theater/{id}/edit', [MovieController::class, 'theateredit'])->name('theater.edit');
    Route::put('admin/theater/{id}/update', [MovieController::class, 'theaterupdate'])->name('theater.update');
    Route::delete('admin/theater/{id}/delete', [MovieController::class, 'deletetheater'])->name('theater.destroy');


    //User Tickets
    Route::get('admin/userticketlist', [TransactionsController::class, 'fetchUserTickets'])->name('ticket.list');
    //User Tickets RUD
    Route::get('admin/ticket/{id}/edit', [TransactionsController::class, 'editUserTicket'])->name('ticket.edit');
    Route::put('admin/ticket/update/{id}', [TransactionsController::class, 'updateUserTicket'])->name('ticket.update');
    Route::delete('admin/ticket/{id}/delete', [TransactionsController::class, 'deleteUserTicket'])->name('ticket.destroy');


    //User Transactions
    Route::get('admin/transactionlist', [TransactionsController::class, 'showTransactionList'])->name('transaction.list');
    Route::get('admin/transaction/{id}/details', [TransactionsController::class, 'fetchTransactionDetails'])->name('transaction.details');
});
