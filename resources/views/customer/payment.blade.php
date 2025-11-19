<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran — Toko Kelontong Bu Untung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            color: #000000;
            min-height: 100vh;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: #ffffff;
            border-bottom: 1px solid #e5e5e5;
        }

        .user-name {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
        }

        .header-icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .icon-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .icon-wrapper:hover {
            transform: translateY(-2px) scale(1.1);
            background: rgba(255, 87, 34, 0.05);
        }

        .icon {
            width: 45px;
            height: 45px;
            position: relative;
            z-index: 1;
        }

        /* Profile Menu & Dropdown */
        .profile-menu {
            position: relative;
            display: inline-block;
        }

        .profile-dropdown {
            position: absolute;
            top: 55px;
            right: 0;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 1001;
            display: none;
            animation: fadeInDown 0.3s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-dropdown.active {
            display: block;
        }

        .profile-dropdown-item {
            padding: 14px 20px;
            color: #333;
            text-decoration: none;
            display: block;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-dropdown-item:last-child {
            border-bottom: none;
        }

        .profile-dropdown-item:hover {
            background: #f5f5f5;
            color: #000;
        }

        .profile-dropdown-item.logout {
            border-top: 1px solid #e5e5e5;
            color: #ff5722;
            border: 1px solid #ff5722;
            border-radius: 8px;
            margin: 8px;
            text-align: center;
            font-weight: 600;
        }

        .profile-dropdown-item.logout:hover {
            background: #ff5722;
            color: #fff;
        }

        .profile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }

        .profile-overlay.active {
            display: block;
        }

        button.icon-wrapper {
            width: 45px;
            height: 45px;
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 0;
            margin: 0;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Container */
        .payment-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 40px;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        /* Left Column - Payment Details */
        .left-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .payment-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .payment-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #000000;
        }

        .order-id {
            font-size: 14px;
            color: #666666;
            margin-bottom: 24px;
        }

        .order-id strong {
            color: #ff5722;
            font-weight: 600;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #000000;
        }

        /* QRIS Section */
        .qris-section {
            text-align: center;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .qris-code {
            width: 280px;
            height: 280px;
            background: #ffffff;
            border: 2px solid #e5e5e5;
            border-radius: 12px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .qris-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .qris-text {
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
        }

        .qris-subtext {
            font-size: 14px;
            color: #666666;
            line-height: 1.6;
        }

        .amount-text {
            font-size: 32px;
            font-weight: 700;
            color: #ff5722;
            margin: 20px 0;
        }

        /* Payment Instructions */
        .instructions {
            background: #fff5f0;
            border: 1px solid #ffccbc;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .instructions-title {
            font-size: 16px;
            font-weight: 600;
            color: #d84315;
            margin-bottom: 12px;
        }

        .instructions-list {
            list-style: none;
            padding: 0;
        }

        .instructions-list li {
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
            padding-left: 24px;
            position: relative;
            line-height: 1.6;
        }

        .instructions-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #ff5722;
            font-weight: 700;
        }

        /* Transaction Details */
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 14px;
            color: #666666;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #000000;
        }

        .product-list {
            margin-top: 20px;
        }

        .product-item {
            display: flex;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 4px;
        }

        .product-qty {
            font-size: 13px;
            color: #666666;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 16px;
            font-weight: 700;
            color: #ff5722;
        }

        /* Right Column - Summary */
        .right-column {
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .summary-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .summary-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #000000;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .summary-label {
            color: #666666;
        }

        .summary-value {
            font-weight: 600;
            color: #000000;
        }

        .summary-divider {
            height: 1px;
            background: #e5e5e5;
            margin: 20px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 18px;
        }

        .total-label {
            font-weight: 700;
            color: #000000;
        }

        .total-value {
            font-size: 24px;
            font-weight: 700;
            color: #ff5722;
        }

        .confirm-btn {
            width: 100%;
            padding: 16px;
            background: #ff5722;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        .confirm-btn:hover:not(:disabled) {
            background: #e64a19;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.3);
        }

        .confirm-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #666666;
            text-decoration: none;
            margin-top: 16px;
            transition: color 0.2s;
        }

        .back-btn:hover {
            color: #ff5722;
        }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #68d391;
        }

        .alert-info {
            background: #bee3f8;
            color: #1a365d;
            border: 1px solid #90cdf4;
        }


        /* Responsive */
        @media (max-width: 1024px) {
            .payment-container {
                grid-template-columns: 1fr;
            }

            .right-column {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .user-name {
                font-size: 20px;
            }

            .payment-container {
                padding: 0 20px;
                margin: 20px auto;
            }

            .qris-code {
                width: 240px;
                height: 240px;
            }

            .amount-text {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @php
            $u = Auth::guard('pelanggan')->user();
            $nama = $u->username ?? 'Sahabat';
            $jam  = now('Asia/Jakarta')->format('H');
            $waktu = $jam < 11 ? 'pagi' : ($jam < 15 ? 'siang' : ($jam < 18 ? 'sore' : 'malam'));
        @endphp
        <div class="user-name">Pembayaran Pesanan</div>
        <div class="header-icons">
            <div class="profile-menu" id="profileMenu">
                <button type="button" class="icon-wrapper profile-icon" aria-label="Profile" onclick="toggleProfileMenu(event)">
                    <img src="{{ asset('assets/profile-icon.svg') }}" alt="User profile avatar icon" class="icon" />
                </button>
                <div class="profile-dropdown" id="profileDropdown">
                    <a href="{{ route('customer.transaksi') }}" class="profile-dropdown-item">Transaksi</a>
                    <a href="{{ route('customer.profile.edit') }}" class="profile-dropdown-item">Akun Saya</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="profile-dropdown-item logout" style="width: calc(100% - 16px); border: none; background: none; font-family: inherit; font-size: inherit; cursor: pointer;">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="payment-container">
        <!-- Left Column - Payment Details -->
        <div class="left-column">
            <div class="payment-card">
                <h1 class="payment-title">Selesaikan Pembayaran</h1>
                <div class="order-id">
                    Nomor Pesanan: <strong>{{ $transaksi->id_transaksi }}</strong>
                </div>

                <!-- Midtrans Snap Payment -->
                <div class="qris-section">
                    <div class="qris-text">Total Pembayaran</div>
                    <div class="amount-text">
                        Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}
                    </div>
                    <div class="qris-subtext" style="margin-top: 20px; margin-bottom: 20px;">
                        Klik tombol di bawah untuk memilih metode pembayaran
                    </div>
                    
                    <!-- Payment Button -->
                    <button id="pay-button" class="confirm-btn" style="max-width: 400px; margin: 20px auto; display: block;" disabled>
                        Pilih Metode Pembayaran
                    </button>
                    
                    <!-- Loading Indicator -->
                    <div id="loading-indicator" style="display: none; text-align: center; padding: 20px;">
                        <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #ff5722; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                        <p style="margin-top: 10px; color: #666;">Memuat halaman pembayaran...</p>
                    </div>
                    
                    <!-- Error Message -->
                    <div id="error-message" style="display: none; padding: 20px; background: #ffebee; border: 1px solid #ef5350; border-radius: 12px; color: #c62828; margin-top: 20px; text-align: center;">
                        <p id="error-text"></p>
                        <button onclick="location.reload()" style="margin-top: 10px; padding: 10px 20px; background: #ff5722; color: white; border: none; border-radius: 8px; cursor: pointer;">
                            Coba Lagi
                        </button>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="instructions">
                    <div class="instructions-title">Cara Pembayaran:</div>
                    <ul class="instructions-list">
                        <li><strong>QRIS</strong> - Pilih QRIS untuk scan dengan aplikasi e-wallet atau bank Anda (GoPay, OVO, DANA, LinkAja, atau aplikasi bank)</li>
                        <li>Klik tombol "Pilih Metode Pembayaran" di atas untuk membuka halaman pembayaran</li>
                        <li>Pilih metode pembayaran yang diinginkan (QRIS direkomendasikan)</li>
                        <li>Ikuti instruksi pembayaran pada halaman yang muncul</li>
                        <li>Lengkapi proses pembayaran sesuai metode yang dipilih</li>
                        <li>Pembayaran akan otomatis terverifikasi oleh sistem</li>
                        <li>Anda akan diarahkan ke halaman sukses setelah pembayaran berhasil</li>
                    </ul>
                </div>

                <!-- Transaction Details -->
                <div style="margin-top: 30px;">
                    <h2 class="section-title">Detail Pesanan</h2>
                    
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Pembayaran</span>
                        <span class="detail-value">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Waktu Pembayaran</span>
                        <span class="detail-value">{{ now()->locale('id')->isoFormat('HH:mm:ss') }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Nama Penerima</span>
                        <span class="detail-value">{{ $transaksi->nama_penerima ?? '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Telepon</span>
                        <span class="detail-value">{{ $transaksi->telepon_penerima ?? '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Alamat Pengiriman</span>
                        <span class="detail-value" style="text-align: right; max-width: 60%;">{{ $transaksi->alamat_pengiriman ?? '-' }}</span>
                    </div>

                </div>

                <!-- Product List -->
                <div class="product-list">
                    <h2 class="section-title" style="margin-top: 30px;">Barang yang Dibeli</h2>
                    @foreach($transaksi->detailTransaksis as $detail)
                        <div class="product-item">
                            <img src="{{ $detail->barang->gambar_url ?? 'https://placehold.co/100x100' }}" 
                                 alt="{{ $detail->barang->nama_barang }}" 
                                 class="product-image">
                            <div class="product-info">
                                <div class="product-name">{{ $detail->barang->nama_barang }}</div>
                                <div class="product-qty">Jumlah: {{ $detail->jumlah_pesanan }} item</div>
                                <div class="product-price">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column - Summary -->
        <div class="right-column">
            <div class="summary-card">
                <h2 class="summary-title">Ringkasan Pembayaran</h2>

                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Diskon</span>
                    <span class="summary-value">Rp 0</span>
                </div>

                <div class="summary-divider"></div>

                <div class="total-row">
                    <span class="total-label">Total Pembayaran</span>
                    <span class="total-value">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</span>
                </div>


                <a href="{{ route('customer.transaksi') }}" class="back-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Lihat Transaksi Saya
                </a>
            </div>

        </div>
    </div>

    <!-- Profile Overlay -->
    <div class="profile-overlay" id="profileOverlay" onclick="closeProfileMenu()"></div>

    <!-- Midtrans Snap JS -->
    @php
        $isProduction = config('services.midtrans.is_production', false);
        $snapUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script type="text/javascript" src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const snapToken = '{{ $snapToken }}';
        const orderId = '{{ $transaksi->id_transaksi }}';
        const payButton = document.getElementById('pay-button');
        const loadingIndicator = document.getElementById('loading-indicator');
        const errorMessage = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');

        // Check if Snap is loaded
        let snapCheckAttempts = 0;
        const maxSnapCheckAttempts = 50; // 5 seconds max wait

        function checkSnapLoaded() {
            if (typeof snap !== 'undefined') {
                // Snap is loaded, enable button
                console.log('Midtrans Snap library loaded successfully');
                payButton.disabled = false;
                payButton.textContent = 'Pilih Metode Pembayaran';
                return;
            }
            
            snapCheckAttempts++;
            if (snapCheckAttempts < maxSnapCheckAttempts) {
                setTimeout(checkSnapLoaded, 100);
            } else {
                // Snap failed to load
                console.error('Midtrans Snap library failed to load after', maxSnapCheckAttempts * 100, 'ms');
                payButton.disabled = true;
                showError('Gagal memuat library pembayaran. Silakan refresh halaman atau hubungi customer service.');
            }
        }

        // Handle network errors and API failures
        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);
            if (event.reason && typeof event.reason === 'object') {
                const errorMsg = event.reason.message || event.reason.toString();
                if (errorMsg.toLowerCase().includes('failed to process') || 
                    errorMsg.toLowerCase().includes('transaction failed') ||
                    errorMsg.toLowerCase().includes('network error')) {
                    showError('Gagal memproses transaksi. Silakan refresh halaman dan coba lagi.');
                    event.preventDefault(); // Prevent default error handling
                }
            }
        });

        // Initialize on page load
        window.addEventListener('load', function() {
            console.log('Page loaded, checking Snap token and library...');
            console.log('Snap Token exists:', !!snapToken);
            console.log('Snap Token length:', snapToken ? snapToken.length : 0);
            console.log('Client Key:', '{{ $clientKey ? "Set" : "Not set" }}');
            
            if (snapToken) {
                checkSnapLoaded();
            } else {
                console.error('Snap token is missing!');
                showError('Token pembayaran tidak tersedia. Silakan hubungi customer service.');
            }
        });

        // Handle script loading errors
        window.addEventListener('error', function(e) {
            // Ignore New Relic and other third-party script errors
            if (e.filename && (
                e.filename.includes('newrelic') || 
                e.filename.includes('nr-spa') ||
                e.filename.includes('analytics')
            )) {
                console.warn('Third-party script error (ignored):', e.filename);
                return;
            }
            
            // Ignore postMessage origin mismatch warnings (common in localhost development)
            if (e.message && e.message.includes('postMessage') && e.message.includes('origin')) {
                console.warn('postMessage origin warning (safe to ignore in localhost):', e.message);
                return;
            }
            
            // Log other errors
            if (e.filename && e.filename.includes('midtrans')) {
                console.error('Midtrans script error:', e.message, e.filename);
                showError('Terjadi kesalahan saat memuat halaman pembayaran. Silakan refresh halaman.');
            }
        }, true);
        
        // Suppress console errors for postMessage origin mismatch (development only)
        const originalError = console.error;
        console.error = function(...args) {
            const message = args.join(' ');
            // Filter out postMessage origin warnings (common in localhost)
            if (message.includes('postMessage') && message.includes('origin') && message.includes('midtrans')) {
                console.warn('⚠️ postMessage origin warning (safe to ignore in localhost development)');
                return;
            }
            originalError.apply(console, args);
        };

        // Payment button click handler
        payButton.addEventListener('click', function() {
            if (!snapToken) {
                showError('Token pembayaran tidak tersedia. Silakan refresh halaman.');
                return;
            }

            if (typeof snap === 'undefined') {
                showError('Library pembayaran belum dimuat. Silakan refresh halaman.');
                return;
            }

            // Show loading
            payButton.style.display = 'none';
            loadingIndicator.style.display = 'block';
            errorMessage.style.display = 'none';

            // Initialize Midtrans Snap
            console.log('Initializing Midtrans Snap payment...');
            console.log('Order ID:', orderId);
            console.log('Snap Token:', snapToken ? snapToken.substring(0, 20) + '...' : 'MISSING');
            console.log('Snap object available:', typeof snap !== 'undefined');
            console.log('Snap.pay function available:', typeof snap.pay === 'function');
            
            try {
                // Configure Snap to prioritize QRIS
                // The enabled_payments array already has 'qris' as first item from backend
                console.log('Opening Midtrans Snap payment popup...');
                
                // Suppress postMessage warnings for localhost (common in development)
                const originalPostMessage = window.postMessage;
                window.addEventListener('message', function(event) {
                    // Allow messages from Midtrans sandbox
                    if (event.origin.includes('midtrans.com') || event.origin.includes('sandbox.midtrans.com')) {
                        console.log('Message received from Midtrans:', event.origin);
                    }
                }, false);
                
                snap.pay(snapToken, {
                    // Optional: Callback functions
                    onSuccess: function(result) {
                        // Payment successful - redirect immediately
                        console.log('Payment success callback triggered:', result);
                        console.log('Redirecting to payment success page...');
                        // Use window.location.replace to prevent back button issues
                        window.location.replace(`{{ route('payment.success') }}?order_id=${orderId}`);
                    },
                    onPending: function(result) {
                        // Payment pending - start aggressive polling
                        console.log('Payment pending callback triggered:', result);
                        loadingIndicator.style.display = 'none';
                        payButton.style.display = 'block';
                        // Start polling more aggressively for pending payments
                        if (!pollingInterval) {
                            console.log('Starting aggressive polling for pending payment...');
                            pollingInterval = setInterval(checkPaymentStatus, 2000); // Check every 2 seconds
                        }
                    },
                    onError: function(result) {
                        // Payment error
                        console.error('Payment error callback triggered:', result);
                        console.error('Full error object:', JSON.stringify(result, null, 2));
                        loadingIndicator.style.display = 'none';
                        payButton.style.display = 'block';
                        
                        let errorMsg = 'Terjadi kesalahan saat memproses pembayaran.';
                        
                        // Extract error message from result
                        if (result && typeof result === 'object') {
                            errorMsg = result.status_message || 
                                      result.message || 
                                      result.error_message ||
                                      result.reason ||
                                      'Kami mengalami kendala teknis. Silakan coba lagi.';
                        } else if (typeof result === 'string') {
                            errorMsg = result;
                        }
                        
                        // Translate common Midtrans error messages to Indonesian
                        const errorLower = errorMsg.toLowerCase();
                        if (errorLower.includes('failed to process') || 
                            errorLower.includes('transaction failed') ||
                            errorLower.includes('kendala teknis') ||
                            errorLower.includes('technical issue')) {
                            errorMsg = 'Kami mengalami kendala teknis. Silakan refresh halaman dan coba lagi. Jika masalah berlanjut, silakan hubungi customer service.';
                        }
                        
                        console.error('Payment error details:', errorMsg);
                        showError(errorMsg);
                    },
                    onClose: function() {
                        // User closed payment popup - continue polling to check status
                        console.log('Payment popup closed by user');
                        loadingIndicator.style.display = 'none';
                        payButton.style.display = 'block';
                        // Continue polling in case payment was completed
                        if (!pollingInterval) {
                            console.log('Starting polling after popup closed...');
                            pollingInterval = setInterval(checkPaymentStatus, 2000); // Check every 2 seconds
                        }
                    }
                });
                console.log('Midtrans Snap payment popup opened successfully');
                console.log('QRIS should be available in the payment methods list');
                console.log('Note: postMessage warnings are normal in localhost development and can be safely ignored');
            } catch (error) {
                console.error('Error initializing Midtrans Snap:', error);
                loadingIndicator.style.display = 'none';
                payButton.style.display = 'block';
                
                let errorMsg = error.message || 'Gagal memproses pembayaran.';
                
                // Translate common error messages
                if (errorMsg.toLowerCase().includes('failed to process') || 
                    errorMsg.toLowerCase().includes('transaction failed')) {
                    errorMsg = 'Gagal memproses transaksi. Silakan refresh halaman dan coba lagi.';
                }
                
                showError(errorMsg);
            }
        });

        function showError(message) {
            errorText.textContent = message;
            errorMessage.style.display = 'block';
            loadingIndicator.style.display = 'none';
            payButton.style.display = 'block';
        }

        // Add spin animation for loading
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);


        // Profile Menu Functions
        function toggleProfileMenu(event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            const dropdown = document.getElementById('profileDropdown');
            const overlay = document.getElementById('profileOverlay');
            const profileMenu = document.getElementById('profileMenu');
            
            const isActive = dropdown.classList.contains('active');
            if (isActive) {
                dropdown.classList.remove('active');
                overlay.classList.remove('active');
                if (profileMenu) {
                    profileMenu.classList.remove('active');
                }
            } else {
                dropdown.classList.add('active');
                overlay.classList.add('active');
                if (profileMenu) {
                    profileMenu.classList.add('active');
                }
            }
        }

        function closeProfileMenu() {
            const dropdown = document.getElementById('profileDropdown');
            const overlay = document.getElementById('profileOverlay');
            const profileMenu = document.getElementById('profileMenu');
            
            if (dropdown) dropdown.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
            if (profileMenu) profileMenu.classList.remove('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profileMenu = document.getElementById('profileMenu');
            const dropdown = document.getElementById('profileDropdown');
            const overlay = document.getElementById('profileOverlay');
            
            if (!dropdown || !dropdown.classList.contains('active')) {
                return;
            }
            
            if (event.target === overlay) {
                closeProfileMenu();
                return;
            }
            
            if (profileMenu && !profileMenu.contains(event.target)) {
                closeProfileMenu();
            }
        });

        // Payment status polling - check if payment is successful
        let pollingInterval = null;
        let pollingAttempts = 0;
        const maxPollingAttempts = 150; // Poll for 5 minutes (150 * 2 seconds)

        function checkPaymentStatus() {
            if (pollingAttempts >= maxPollingAttempts) {
                clearInterval(pollingInterval);
                pollingInterval = null;
                return;
            }

            pollingAttempts++;

            fetch(`{{ route('checkout.payment.status', ['id' => $transaksi->id_transaksi]) }}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                cache: 'no-cache'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Payment status check:', data);
                if (data.is_paid) {
                    // Payment successful - redirect to success page
                    console.log('Payment is paid! Redirecting to success page...');
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                    // Use window.location.replace to prevent back button issues
                    window.location.replace(`{{ route('payment.success') }}?order_id=${orderId}`);
                } else {
                    console.log('Payment status:', data.status, '- Still waiting...');
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
        }

        // Start polling after page load (check every 3 seconds initially)
        // Only poll if transaction is still pending
        @if($transaksi->status_transaksi === 'pending')
            // Start polling after a short delay to allow payment to process
            setTimeout(function() {
                if (!pollingInterval) {
                    pollingInterval = setInterval(checkPaymentStatus, 3000); // Check every 3 seconds
                }
            }, 2000); // Wait 2 seconds before starting to poll
        @endif
    </script>
</body>
</html>

