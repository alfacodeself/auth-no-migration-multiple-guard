<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function authenticate(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5'
        ], [
            'email.required' => 'Email harus di isi!',
            'password.required' => 'Password harus di isi!',
            'password.min' => 'Password minimal 5 karakter!',
            'email.email' => 'Format email salah!',
        ]);
        // Proses login
        try {
            // Kalau berhasil attempt ke guard pelanggan
            if (Auth::attempt($credential)) {
                // return redirect()->route('pelanggan.list')
                return ['data' => Auth::user()->nama, 'status' =>'pelanggan'];
            }
            // Kalau berhasil attempt ke guard pelapak
            else if (Auth::guard('pelapak')->attempt($credential)) {
                return ['data' => Auth::guard('pelapak')->user()->nama, 'status' =>'pelapak'];
            }
            // Kalau gagal semua
            else {
                return redirect()->route('login')->with('error', 'Login Salah');
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
