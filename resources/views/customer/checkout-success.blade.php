@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 text-center">
        {{-- Success Icon --}}
        <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full mx-auto mb-6 flex items-center justify-center animate-bounce">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        {{-- Success Message --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-3">Pesanan Berhasil!</h1>

        @if(session('order_id'))
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <p class="text-sm text-gray-600 mb-1">Nomor Pesanan</p>
                <p class="text-lg font-bold text-blue-600">{{ session('order_id') }}</p>
            </div>
        @endif

        <p class="text-gray-600 mb-8 leading-relaxed">
            Terima kasih telah berbelanja di Toko Kelontong Bu Untung! 
            Pesanan Anda sedang kami proses dan akan segera dikirim sesuai jadwal yang dipilih.
        </p>

        {{-- Action Buttons --}}
        <div class="space-y-3">
            <a href="{{ route('customer.home') }}" 
               class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                Kembali ke Beranda
            </a>
            
            <a href="{{ route('cart.index') }}" 
               class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-300">
                Lihat Keranjang
            </a>
        </div>
    </div>
</div>
@endsection