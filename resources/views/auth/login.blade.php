@extends('layouts.visitor')

@section('title', 'Login')

@section('content')
<div class="mx-auto mt-4 max-w-md overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm shadow-gray-200 sm:mt-10">
    <div class="p-6 sm:p-8">
        <div class="space-y-2 text-center">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-600">Masuk</p>
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Selamat datang kembali</h1>
            <p class="text-sm text-gray-600">Silakan login jika sudah punya akun untuk melanjutkan booking.</p>
        </div>

        @if(session('error'))
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-700">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" 
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                @error('username')<p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Password</label>
                <input type="password" name="password" 
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                @error('password')<p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="mt-2 w-full rounded-full bg-blue-600 px-5 py-3.5 text-sm font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">
                Masuk
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-blue-600 transition-colors hover:text-blue-700 hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection