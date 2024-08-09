<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Login Admin
    public function login(Request $request)
    {
        // Memeriksa apakah metode permintaan adalah POST
        if ($request->isMethod('post')) {
            // Mengambil username dan password dari permintaan
            $username = $request->input('username');
            $password = $request->input('password');

            // Mengambil user berdasarkan username dari database
            $user = UserModel::where('username', $username)->first();

            // Memeriksa apakah user ada dan password benar
            if ($user && Hash::check($password, $user->password)) {
                // Menyimpan detail user dalam sesi
                $request->session()->put([
                    'id' => $user->id,
                    'username'  => $user->username,
                    'name' => $user->name,
                    'status' => $user->status,
                    'logged_in' => true
                ]);
                // Mengarahkan ke dashboard admin
                return redirect('/admin');
            } else {
                // Mengarahkan kembali dengan pesan kesalahan jika kredensial salah
                return redirect()->back()->withInput()->withErrors('Username / Password Salah.');
            }
        }
        // Menampilkan tampilan login admin
        return view('login_admin', ['title' => 'Motrix : Admin Login']);
    }

    // Login Pengguna
    public function userLogin(Request $request)
    {
        // Memeriksa apakah metode permintaan adalah POST
        if ($request->isMethod('post')) {
            // Mengambil username dan password dari permintaan
            $username = $request->input('username');
            $password = $request->input('password');

            // Mengambil user berdasarkan username dari database
            $user = UserModel::where('username', $username)->first();

            // Memeriksa apakah user ada dan password benar
            if ($user && Hash::check($password, $user->password)) {
                // Memeriksa apakah akun pengguna sudah diaktifkan
                if ($user->flag == 1) {
                    // Menyimpan detail user dalam sesi
                    $request->session()->put([
                        'id' => $user->id,
                        'username'  => $user->username,
                        'current_balance' => $user->current_balance,
                        'name' => $user->name,
                        'status' => $user->status,
                        'logged_in' => true
                    ]);
                    // Mengarahkan ke dashboard pengguna
                    return redirect('/index');
                } else {
                    // Mengarahkan kembali dengan pesan kesalahan jika akun belum diaktifkan
                    return redirect()->back()->withInput()->withErrors('Akun anda belum diaktifkan<br> Segera cek email anda.');
                }
            } else {
                // Mengarahkan kembali dengan pesan kesalahan jika kredensial salah
                return redirect()->back()->withInput()->withErrors('Username / Password Salah.');
            }
        }
        // Menampilkan tampilan login pengguna
        return view('login_user');
    }

    // Logout Semua Pengguna
    public function logout(Request $request)
    {
        // Menghapus semua data sesi
        $request->session()->flush();
        // Mengarahkan ke halaman utama
        return redirect('/');
    }

    public function usersetting(Request $request)
    {
        // Memeriksa apakah metode permintaan adalah POST
        if ($request->isMethod('post')) {
            // Memvalidasi data permintaan
            $request->validate([
                'name' => 'required',
                'password' => 'required',
                'email' => 'required|email',
            ]);

            // Mengambil semua data permintaan
            $data = $request->all();
            // Mengenkripsi password
            $data['password'] = Hash::make($data['password']);
            // Mengambil user berdasarkan ID dari sesi
            $user = UserModel::findOrFail($request->session()->get('id'));
            // Memperbarui data user
            $user->update($data);

            // Mengarahkan kembali dengan pesan sukses
            return redirect()->back()->with('success', 'User updated successfully.');
        }
        // Menampilkan tampilan pengaturan user
        return view('userviews/user_setting', ['title' => 'User Settings']);
    }

    // Pendaftaran Pengguna
    public function register(Request $request)
    {
        // Memeriksa apakah metode permintaan adalah POST
        if ($request->isMethod('post')) {
            // Memeriksa apakah password dan konfirmasi password sama
            if ($request->input('password') != $request->input('password_confirm')) {
                // Mengarahkan kembali dengan pesan kesalahan jika password tidak sama
                return redirect()->back()->withInput()->withErrors('Password dan Konfirmasi Password tidak sama.');
            } else {
                // Memvalidasi data permintaan
                $request->validate([
                    'name' => 'required',
                    'username' => 'required|unique:user',
                    'email' => 'required|email|unique:user',
                    'password' => 'required|min:6',
                ]);
            }
            // Mengambil semua data permintaan
            $data = $request->all();
            // Mengenkripsi password
            $data['password'] = Hash::make($data['password']);
            // Mengatur field tambahan
            $data['flag'] = 0;
            $data['status'] = 'User';

            // Membuat user baru
            UserModel::create($data);

            // Mengarahkan ke halaman login dengan pesan sukses
            return redirect('/login')->with('success', 'User registered successfully.');
        }
        // Menampilkan tampilan pendaftaran pengguna
        return view('user_register');
    }

    // Menampilkan tampilan dashboard admin
    public function adminDashboard()
    {
        return view('adminviews/admin_dashboard', ['title' => 'Admin Dashboard']);
    }

    // Mengambil dan menampilkan tampilan daftar pengguna
    public function userlist()
    {
        $users = UserModel::all();
        return view('adminviews/users/user_list', ['title' => 'User List'], compact('users'));
    }

    // Menampilkan formulir pembuatan pengguna
    public function create()
    {
        return view('adminviews/users/user_create', ['title' => 'User Create']);
    }

    // Menyimpan pengguna baru ke database
    public function store(Request $request)
    {
        // Memvalidasi data permintaan
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Mengambil semua data permintaan
        $data = $request->all();
        // Memeriksa apakah field password tidak kosong
        if (!empty($data['password'])) {
            // Mengenkripsi password
            $data['password'] = Hash::make($data['password']);
        }

        // Membuat pengguna baru
        UserModel::create($data);
        // Mengarahkan ke daftar pengguna dengan pesan sukses
        return redirect()->route('users.userlist')->with('success', 'User created successfully.');
    }

    // Menampilkan formulir pengeditan pengguna
    public function edit($id)
    {
        // Mengambil pengguna berdasarkan ID
        $user = UserModel::findOrFail($id);
        return view('adminviews/users/user_edit', ['title' => 'Edit User'], compact('user'));
    }

    // Memperbarui data pengguna di database
    public function update(Request $request, $id)
    {
        // Memvalidasi data permintaan
        $request->validate([
            'name' => 'required',
            'username' => 'required',
        ]);

        // Mengambil pengguna berdasarkan ID
        $user = UserModel::findOrFail($id);
        // Mengambil semua data permintaan
        $userData = $request->all();

        // Memeriksa apakah field password tidak kosong
        if (!empty($userData['password'])) {
            // Mengenkripsi password
            $userData['password'] = Hash::make($userData['password']);
        }

        // Memperbarui data pengguna
        $user->update($userData);

        // Mengarahkan ke daftar pengguna dengan pesan sukses
        return redirect()->route('users.userlist')->with('success', 'User updated successfully.');
    }

    // Menghapus pengguna dari database
    public function destroy($id)
    {
        // Mengambil pengguna berdasarkan ID
        $user = UserModel::findOrFail($id);
        // Menghapus pengguna
        $user->delete();

        // Mengarahkan ke daftar pengguna dengan pesan sukses
        return redirect()->route('users.userlist')->with('success', 'User deleted successfully.');
    }
}
