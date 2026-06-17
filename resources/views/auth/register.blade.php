@extends('layouts.visitor')

@section('title', 'Daftar')

@section('content')
<div class="mx-auto mt-4 max-w-md overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm shadow-gray-200 sm:mt-8">
    <div class="p-6 sm:p-8">
        <div class="space-y-2 text-center">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-600">Daftar Akun</p>
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Buat akun booking kos</h1>
            <p class="text-sm text-gray-600">Daftar sekali dan lanjutkan ke tampilan visitor untuk booking.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="mt-8 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-700">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" 
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                @error('username')<p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">No. WhatsApp</label>
                <input type="text" name="no_wa" value="{{ old('no_wa') }}" 
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                @error('no_wa')<p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Password</label>
                <input type="password" name="password" 
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                @error('password')<p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="mt-2 w-full rounded-full bg-blue-600 px-5 py-3.5 text-sm font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">
                Daftar
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-blue-600 transition-colors hover:text-blue-700 hover:underline">Login sekarang</a>
        </p>
    </div>
</div>
@endsection