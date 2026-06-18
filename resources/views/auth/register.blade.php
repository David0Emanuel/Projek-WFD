@extends('layouts.visitor')

@section('title', 'Daftar')

@section('content')
<div class="mx-auto mt-4 max-w-md overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm shadow-gray-200 sm:mt-8">
    <div class="p-6 sm:p-8">
        <div class="space-y-2 text-center">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-600">Daftar Akun</p>
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Buat akun booking kos</h1>
            <p class="text-sm text-gray-600">Daftar sekali dan langsung bisa booking kamar.</p>
        </div>

        {{-- Error Card --}}
        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-red-700">Pendaftaran gagal</p>
                        <ul class="mt-1 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li class="text-xs text-red-600">• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST" class="mt-8 space-y-5">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       placeholder="Masukkan nama lengkap"
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 @error('nama') border-red-400 bg-red-50 @enderror" required>
            </div>

            {{-- Username --}}
            <div>
                <label class="block text-sm font-bold text-gray-700">Username</label>
                <input type="text" name="username" value="{{ old('username') }}"
                       placeholder="Masukkan username"
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 @error('username') border-red-400 bg-red-50 @enderror" required>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-bold text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="contoh@email.com"
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 @error('email') border-red-400 bg-red-50 @enderror" required>
            </div>

            {{-- No. WhatsApp --}}
            <div>
                <label class="block text-sm font-bold text-gray-700">No. WhatsApp</label>
                <input type="text" name="no_wa" value="{{ old('no_wa') }}"
                       placeholder="08xxxxxxxxxx"
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 @error('no_wa') border-red-400 bg-red-50 @enderror" required>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-bold text-gray-700">Password</label>
                <input type="password" name="password"
                       placeholder="Minimal 6 karakter"
                       class="mt-2 w-full rounded-2xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                <p class="mt-1.5 text-xs text-gray-400">Minimal 6 karakter</p>
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