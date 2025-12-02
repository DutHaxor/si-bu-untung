<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="Pembayaran berhasil. Pesanan Anda di Toko Kelontong Bu Untung sedang diproses dan akan segera dikirim.">
    <title>Pembayaran Berhasil — SI Bu Untung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Quicksand:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root{ 
            --black:#000; 
            --muted:#8a8a8a; 
            --panel:#F0F0F0; 
            --card:#F0EEED; 
            --orange:#F25019;
            --green:#10b981;
            --green-dark:#059669;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            background: #fff;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        
        body {
            font-family: Poppins, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Animasi Checklist */
        @keyframes checkmark-draw {
            0% {
                stroke-dashoffset: 100;
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                stroke-dashoffset: 0;
                opacity: 1;
            }
        }

        @keyframes circle-scale {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .container {
            max-width: 420px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 24px 20px;
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(242, 80, 25, 0.05),
                transparent
            );
            animation: shimmer 3s infinite;
        }

        .success-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 12px;
            position: relative;
            animation: circle-scale 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .success-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
            animation: pulse 3s ease-in-out 2;
        }

        .success-circle::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
            opacity: 0.3;
            z-index: -1;
            animation: pulse 3s ease-in-out 2;
            animation-delay: 0.3s;
        }

        .checkmark {
            width: 35px;
            height: 35px;
            stroke: #fff;
            stroke-width: 3.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark-draw 0.8s ease-out 0.4s forwards;
        }

        .title {
            font-size: 22px;
            font-weight: 800;
            color: var(--black);
            margin-bottom: 4px;
            animation: fadeInUp 0.6s ease-out 0.6s both;
        }

        .subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 12px;
            line-height: 1.3;
            animation: fadeInUp 0.6s ease-out 0.8s both;
        }

        .order-info {
            background: var(--panel);
            border: 2px solid var(--orange);
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 10px;
            animation: fadeInUp 0.6s ease-out 1s both;
        }

        .order-label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 4px;
            font-weight: 500;
        }

        .order-id {
            font-size: 18px;
            font-weight: 700;
            color: var(--orange);
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }

        .order-summary {
            background: var(--card);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 12px;
            text-align: left;
            animation: fadeInUp 0.6s ease-out 1.1s both;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 500;
        }

        .summary-value {
            font-size: 13px;
            color: var(--black);
            font-weight: 600;
        }

        .summary-value.amount {
            color: var(--orange);
            font-size: 16px;
            font-weight: 700;
        }

        .message {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.4;
            margin-bottom: 16px;
            animation: fadeInUp 0.6s ease-out 1.3s both;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            animation: fadeInUp 0.6s ease-out 1.4s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: none;
            cursor: pointer;
            font-family: inherit;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: var(--black);
            color: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            background: var(--orange);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(242, 80, 25, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: #fff;
            color: var(--black);
            border: 2px solid var(--black);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            background: var(--black);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary:active {
            transform: translateY(0);
        }

        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: var(--orange);
            z-index: 50;
            animation: confetti-fall 3s linear forwards;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding: 24px 18px;
                max-width: 100%;
            }

            .title {
                font-size: 22px;
            }

            .subtitle {
                font-size: 13px;
            }

            .success-icon {
                width: 70px;
                height: 70px;
                margin-bottom: 14px;
            }

            .success-circle {
                width: 70px;
                height: 70px;
            }

            .checkmark {
                width: 35px;
                height: 35px;
            }

            .order-info {
                padding: 10px 14px;
                margin-bottom: 12px;
            }

            .order-id {
                font-size: 16px;
            }

            .order-summary {
                padding: 12px 14px;
                margin-bottom: 12px;
            }

            .summary-value.amount {
                font-size: 15px;
            }

            .message {
                font-size: 12px;
                margin-bottom: 18px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 13px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon" role="status" aria-label="Pembayaran berhasil">
            <div class="success-circle">
                <svg class="checkmark" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <h1 class="title">Pembayaran Berhasil!</h1>
        <p class="subtitle">
            Hai, {{ $user_name ?? 'Pelanggan' }}! Pesanan Anda telah berhasil dibuat.
        </p>

        @if(!empty($order_id))
            <div class="order-info">
                <div class="order-label">Nomor Pesanan</div>
                <div class="order-id">{{ $order_id }}</div>
            </div>
        @endif

        @if(isset($total) || isset($payment_method) || isset($transaction_date))
            <div class="order-summary">
                @if(isset($total))
                    <div class="summary-row">
                        <span class="summary-label">Total Pembayaran</span>
                        <span class="summary-value amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if(isset($payment_method))
                    <div class="summary-row">
                        <span class="summary-label">Metode Pembayaran</span>
                        <span class="summary-value">{{ $payment_method }}</span>
                    </div>
                @endif
                @if(isset($transaction_date) || isset($transaction_time))
                    <div class="summary-row">
                        <span class="summary-label">Tanggal & Waktu</span>
                        <span class="summary-value">
                            {{ isset($transaction_date) ? \Carbon\Carbon::parse($transaction_date)->format('d M Y') : '' }}
                            @if(isset($transaction_time))
                                , {{ \Carbon\Carbon::parse($transaction_time)->format('H:i') }} WIB
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        @endif

        <p class="message">
            Terima kasih telah berbelanja di Toko Kelontong Bu Untung! Pesanan Anda sudah masuk dan sedang kami proses dan akan segera kami kirimkan.
        </p>

        <div class="button-group">
            <a href="{{ route('customer.transaksi') }}" class="btn btn-primary">
                Cek Transaksi
            </a>
            <a href="{{ route('customer.home') }}" class="btn btn-secondary">
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        // Create confetti effect (reduced count for performance)
        function createConfetti() {
            const colors = ['#F25019', '#ff6b3d', '#ff8c66', '#ffaa8f'];
            const confettiCount = 20; // Reduced from 30 for better performance
            
            for (let i = 0; i < confettiCount; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 0.5 + 's';
                    confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 4000);
                }, i * 50);
            }
        }

        // Trigger confetti on page load
        window.addEventListener('load', () => {
            setTimeout(createConfetti, 500);
        });

        // Optional: Auto-redirect after 10 seconds (can be disabled)
        // Uncomment below if you want auto-redirect
        /*
        let redirectTimer = setTimeout(() => {
            window.location.href = "{{ route('customer.transaksi') }}";
        }, 10000);

        // Cancel auto-redirect if user interacts with page
        document.addEventListener('click', () => {
            clearTimeout(redirectTimer);
        });
        */
    </script>
</body>
</html>
