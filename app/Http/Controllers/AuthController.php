<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
                if (Auth::user()->email_verify == null) {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Anda harus verifikasi email!');
                }
                return ['data' => Auth::user()->email_verify, 'status' =>'pelanggan', 'message' => 'Login pelanggan berhasil!'];
            }
            // Kalau berhasil attempt ke guard pelapak
            else if (Auth::guard('pelapak')->attempt($credential)) {
                if (Auth::guard('pelapak')->user()->email_verify == null) {
                    Auth::guard('pelapak')->logout();
                    return redirect()->route('login')->with('error', 'Anda harus verifikasi email!');
                }
                return ['data' => Auth::guard('pelapak')->user()->email_verify, 'status' =>'pelapak', 'message' => 'Login pelapak berhasil!'];
            }
            else {
                return redirect()->route('login')->with('error', 'Login Salah');
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function register()
    {
        return view('register');
    }
    public function register_user(Request $request)
    {
        $valid = $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:3',
            'tipe' => 'required'
        ]);
        // dd($valid);
        try {
            if ($valid['tipe'] == 'lapak') {
                $id = DB::table('pelapak')->insertGetId([
                    'nama' => $valid['nama'],
                    'email' => $valid['email'],
                    'password' => bcrypt($valid['password'])
                ]);
                DB::table('verify_email')->insert([
                    'token' => Str::random(60),
                    'id_pemohon' => $id,
                    'status' => 'pelapak'
                ]);
                $user = DB::table('pelapak')->where('id', $id)->first();
                $token = DB::table('verify_email')->where('id_pemohon', $user->id)->where('status', 'pelapak')->first();
                Mail::to($user->email)->send(new EmailVerification($user, $token));
                return redirect()->route('login')->with('success', 'Berhasil mendaftar pelapak! Harap melakukan verifikasi email!');
            }elseif ($valid['tipe'] == 'user') {
                $id = DB::table('pelanggan')->insertGetId([
                    'nama' => $valid['nama'],
                    'email' => $valid['email'],
                    'password' => bcrypt($valid['password'])
                ]);
                DB::table('verify_email')->insert([
                    'token' => Str::random(60),
                    'id_pemohon' => $id,
                    'status' => 'pelanggan'
                ]);
                $user = DB::table('pelanggan')->where('id', $id)->first();
                $token = DB::table('verify_email')->where('id_pemohon', $user->id)->where('status', 'pelanggan')->first();
                Mail::to($user->email)->send(new EmailVerification($user, $token));
                return redirect()->route('login')->with('success', 'Berhasil mendaftar pelanggan! Harap melakukan verifikasi email!');
            }
            else {
                return back()->with('error', 'Jenis user salah!');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function email_verify($token)
    {
        try {
            $verify = DB::table('verify_email')->where('token', $token)->first();
            if (isset($verify)) {
                $user = DB::table($verify->status)->where('id', $verify->id_pemohon)->first();
                if ($user->email_verify == null) {
                    $user->email_verify = Carbon::now();
                    DB::table($verify->status)->where('id', $verify->id_pemohon)->update(['email_verify' => Carbon::now()]);
                    return redirect()->route('login')->with('success', 'Berhasil memverifikasi email! Silakan login.');
                }else {
                    return back()->with('error', 'Email anda telah diverifikasi!');
                }
            }else {
                return back()->with('error', 'Something went wrong!');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
