@extends('layouts.auth')

@section('title', 'Login - SIM Sekolah')

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Logo / Brand -->
        <div class="app-brand justify-content-center mb-4">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <span class="app-brand-text demo text-body fw-bolder text-uppercase">SIM SEKOLAH</span>
            </a>
        </div>

        <h4 class="mb-2">Selamat Datang! 👋</h4>
        <p class="mb-4">Silakan login menggunakan NISN, NIP, atau Username Anda</p>

        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
            @csrf
            
            <!-- Input Username (NISN / NIP / Admin) -->
            <div class="mb-3">
                <label for="username" class="form-label">NISN / NIP / Username</label>
                <input 
                    type="text" 
                    class="form-control @error('username') is-invalid @enderror" 
                    id="username" 
                    name="username" 
                    value="{{ old('username') }}" 
                    placeholder="Masukkan NISN / NIP / Username" 
                    autofocus 
                    required 
                />
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Input Password -->
            <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <a href="#">
                        <small>Lupa Password?</small>
                    </a>
                </div>
                <div class="input-group input-group-merge">
                    <input 
                        type="password" 
                        id="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        name="password" 
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" 
                        required 
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                    <label class="form-check-label" for="remember-me"> Ingat Saya </label>
                </div>
            </div>

            <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
            </div>
        </form>

        <p class="text-center text-muted small">
            Hubungi pihak Administrasi Sekolah jika mengalami kendala akun.
        </p>
    </div>
</div>
@endsection