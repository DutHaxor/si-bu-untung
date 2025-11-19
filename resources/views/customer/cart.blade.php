<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja — Toko Kelontong Bu Untung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            color: #000000;
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
            -webkit-tap-highlight-color: transparent;
            user-select: none;
            text-decoration: none;
            outline: none;
        }

        .icon-wrapper::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(255, 87, 34, 0.1);
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .icon-wrapper:hover {
            transform: translateY(-2px) scale(1.1);
            background: rgba(255, 87, 34, 0.05);
        }

        .icon-wrapper:hover::before {
            opacity: 1;
            transform: scale(1);
        }

        .icon-wrapper:active {
            transform: translateY(0) scale(0.95);
            transition: all 0.15s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .icon-wrapper:active::before {
            transform: scale(1.2);
            opacity: 0.3;
        }

        .icon {
            width: 45px;
            height: 45px;
            position: relative;
            z-index: 1;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
        }

        .icon-wrapper:hover .icon {
            transform: scale(1.1) rotate(5deg);
        }

        .icon-wrapper:active .icon {
            transform: scale(0.9) rotate(-5deg);
        }

        /* Animasi pulse untuk cart icon saat hover */
        .icon-wrapper.cart-icon:hover::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: #ff5722;
            border-radius: 50%;
            border: 2px solid white;
            opacity: 1;
            transform: scale(1);
            animation: pulseCart 1.5s ease-in-out infinite;
            z-index: 2;
        }

        @keyframes pulseCart {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.3);
                opacity: 0.8;
            }
        }

        /* Animasi bounce untuk profile icon saat hover */
        @keyframes bounceProfile {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-3px);
            }
        }

        .icon-wrapper.profile-icon:hover .icon {
            animation: bounceProfile 0.6s ease-in-out;
        }

        /* Style untuk button sebagai icon-wrapper */
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
            z-index: 1001;
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
            position: relative;
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
            background: #fff;
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

        .profile-menu.active .icon-wrapper {
            background: rgba(255, 87, 34, 0.1);
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 22px;
            height: 22px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ff5722;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 600;
            line-height: 1;
            box-shadow: 0 2px 8px rgba(255, 87, 34, 0.4);
            z-index: 3;
        }

        /* Back Button */
        .back-button {
            padding: 20px 40px;
            background-color: #ffffff;
        }

        .back-arrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 18px;
            cursor: pointer;
            color: #000000;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .back-arrow:hover {
            transform: translateX(-4px);
        }

        .back-arrow img {
            width: 24px;
            height: 24px;
        }

        /* Main Container */
        .container {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            padding: 30px 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Left Column - Cart Items */
        .cart-section {
            width: 100%;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .select-all-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 12px 20px;
            background-color: #f9f9f9;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
        }

        .select-all-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #ff5722;
            transform: scale(1);
        }

        .select-all-label {
            font-size: 14px;
            font-weight: 500;
            color: #000000;
            cursor: pointer;
            user-select: none;
        }

        .cart-title {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
        }

        .delete-all {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            color: #000000;
            font-size: 14px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: color 0.2s;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .delete-all:hover {
            color: #ff5722;
            background-color: #fff5f5;
        }

        .delete-all img {
            width: 20px;
            height: 20px;
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 20px;
        }

        .cart-item {
            display: grid;
            grid-template-columns: auto minmax(0, 2fr) auto auto;
            gap: 20px;
            padding: 20px;
            background-color: #F0EEED;
            border-radius: 20px;
            border: 1px solid #e2e2e2;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s;
        }

        .cart-item:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            background: #fff;
        }

        .item-checkbox-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #ff5722;
            transform: scale(1);
            transition: transform 0.2s ease;
        }

        .item-checkbox:hover {
            transform: scale(1.1);
        }

        .cart-item.selected {
            border-color: #ff5722;
            background-color: #fff5f5;
        }

        .item-image-wrapper {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 16px;
        }

        .cart-item .product-image {
            width: 100%;
            height: auto;
            object-fit: contain;
            max-width: 140px;
            max-height: 140px;
            border-radius: 12px;
            transition: transform 0.25s ease;
        }

        .cart-item:hover .product-image {
            transform: scale(1.15);
        }

        .item-name {
            font-size: 14px;
            font-weight: 500;
            color: #000000;
            line-height: 1.4;
            text-align: left;
            max-width: 260px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .item-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-right: 80px;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-circle {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 10px;
            background: #fff;
            display: grid;
            place-items: center;
            cursor: pointer;
            padding: 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .06);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .btn-circle::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.06);
            border-radius: 10px;
            transform: scale(0);
            transition: transform .3s ease;
        }

        .btn-circle:hover {
            transform: translateY(-1px) scale(1.06);
            box-shadow: 0 8px 18px rgba(0, 0, 0, .14);
        }

        .btn-circle:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-circle:active::before {
            transform: scale(1.8);
        }

        .btn-circle img {
            width: 30px;
            height: 30px;
            display: block;
            transition: transform .18s ease;
        }

        .btn-circle:hover img {
            transform: translateY(-1px) scale(1.04);
        }

        .btn-circle:active img {
            transform: translateY(0) scale(.99);
        }

        .btn-circle:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .btn-circle:disabled:hover {
            transform: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .06);
        }

        .quantity-value {
            font-size: 18px;
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 90px;
        }

        .item-price {
            font-size: 18px;
            font-weight: 700;
            color: #ff5722;
            text-align: center;
            padding: 6px 14px;
            border: 1px solid #ff5722;
            border-radius: 999px;
            background: rgba(255, 87, 34, 0.06);
            box-shadow: 0 2px 4px rgba(255, 87, 34, 0.15);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .item-actions:hover .item-price {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(255, 87, 34, 0.25);
        }

        .remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 6px;
            transition: background-color 0.2s, transform 0.2s;
        }

        .remove-btn:hover {
            background-color: #fff5f5;
            transform: scale(1.1);
        }

        .remove-btn img {
            width: 24px;
            height: 24px;
        }

        /* Empty Cart State */
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #666666;
        }

        .empty-cart img {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-cart h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #000000;
        }

        .empty-cart p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .empty-cart a {
            display: inline-block;
            padding: 12px 30px;
            background-color: #ff5722;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .empty-cart a:hover {
            background-color: #e64a19;
        }

        /* Right Column - Order Summary */
        .summary-section {
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .order-summary {
            background-color: #f5f5f5;
            border-radius: 12px;
            padding: 30px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #000000;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .summary-row:hover {
            transform: scale(1.02);
        }

        .summary-label {
            color: #666666;
        }

        .summary-value {
            font-weight: 600;
            color: #ff5722;
            transition: transform 0.3s ease;
        }

        .summary-divider {
            height: 1px;
            background: #d5d5d5;
            margin: 20px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .total-row:hover {
            transform: scale(1.02);
        }

        .total-label {
            font-weight: 700;
            color: #000000;
        }

        .total-value {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            transition: transform 0.3s ease;
        }

        .checkout-btn {
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

        .checkout-btn:hover:not(:disabled) {
            background: #e64a19;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.3);
        }

        .checkout-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        /* Loading Spinner */
        .loading {
            pointer-events: none;
            opacity: 0.6;
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 24px;
            height: 24px;
            margin: -12px 0 0 -12px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #ff5722;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #000000;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .toast.success {
            background-color: #4caf50;
        }

        .toast.error {
            background-color: #ff5722;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateY(0);
                opacity: 1;
            }
            to {
                transform: translateY(-20px);
                opacity: 0;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: scale(1);
            }
            to {
                opacity: 0;
                transform: scale(0.8);
            }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease-out;
        }

        .modal.active {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: scaleIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-content h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #000000;
        }

        .modal-content p {
            font-size: 14px;
            color: #666666;
            margin-bottom: 25px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
        }

        .modal-btn.confirm {
            background-color: #ff5722;
            color: white;
        }

        .modal-btn.confirm:hover {
            background-color: #e64a19;
            transform: translateY(-2px);
        }

        .modal-btn.cancel {
            background-color: #f5f5f5;
            color: #000000;
        }

        .modal-btn.cancel:hover {
            background-color: #e5e5e5;
        }

        /* Footer */
        .footer {
            background-color: #000000;
            color: #ffffff;
            padding: 60px 40px;
            margin-top: 60px;
        }

        .footer-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-subtitle {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            margin-top: 40px;
        }

        .footer-text {
            font-size: 14px;
            line-height: 1.8;
            color: #cccccc;
            max-width: 900px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
            }

            .summary-section {
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

            .back-button {
                padding: 15px 20px;
            }

            .container {
                padding: 20px;
            }

            .cart-item {
                grid-template-columns: auto 1fr auto;
                grid-template-rows: auto auto;
                align-items: flex-start;
            }

            .item-checkbox-wrapper {
                grid-row: 1 / 3;
            }

            .item-image-wrapper {
                grid-column: 2 / 4;
            }

            .item-controls {
                justify-content: flex-start;
            }

            .item-actions {
                align-items: flex-end;
            }

            .remove-btn {
                padding: 14px;
                min-width: 48px;
                min-height: 48px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @php
            $u = Auth::guard('pelanggan')->user();
            $nama = optional($u)->username ?? optional($u)->name ?? 'Sahabat';
            $jam  = now('Asia/Jakarta')->format('H');
            $waktu = $jam < 11 ? 'pagi' : ($jam < 15 ? 'siang' : ($jam < 18 ? 'sore' : 'malam'));
        @endphp
        <div class="user-name">Selamat {{ $waktu }}, {{ $nama }}!</div>
        <div class="header-icons">
            <div class="profile-menu" id="profileMenu">
                <button type="button" class="icon-wrapper profile-icon" aria-label="Profile" onclick="toggleProfileMenu(event)">
                    <img src="{{ asset('assets/profile-icon.svg') }}" alt="User profile avatar icon in circular frame" class="icon" />
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
            <a href="{{ route('cart.index') }}" class="icon-wrapper cart-icon" aria-label="Shopping cart">
                <img src="{{ asset('assets/cart-icon.svg') }}" alt="Shopping cart icon showing items in basket" class="icon" />
                @php
                    $unique = (int) session('cart_unique_count', 0);
                    $badge  = $unique > 99 ? '99+' : $unique;
                @endphp
                @if($unique > 0)
                    <span class="cart-count" aria-live="polite" aria-atomic="true">{{ $badge }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- Back Button -->
    <div class="back-button">
        <a href="/home" class="back-arrow">
            <img src="{{ asset('assets/back-arrow.png') }}" alt="Back arrow icon pointing left for navigation" />
            Kembali
        </a>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Left Column - Cart Items -->
        <div class="cart-section">
            <div class="cart-header">
                <h2 class="cart-title">Keranjang</h2>
                <button class="delete-all" onclick="confirmClearCart()" {{ empty($cart) || count($cart) === 0 ? 'style="display:none;"' : '' }}>
    <img src="{{ asset('assets/trash-icon.png') }}" alt="Trash bin icon for deleting all items" />
    Hapus Semua
</button>

            </div>

            @if(!empty($cart) && count($cart) > 0)
                <div class="select-all-wrapper">
                    <input type="checkbox" id="selectAllCheckbox" class="select-all-checkbox" onchange="toggleSelectAll()" checked>
                    <label for="selectAllCheckbox" class="select-all-label">Pilih Semua</label>
                </div>
            @endif

            <div class="cart-items" id="cartItems">
                @if(empty($cart) || count($cart) === 0)
                    <div class="empty-cart">
                        <img src="{{ asset('assets/empty-cart.png') }}" alt="Empty shopping cart icon with simple outline design showing no items inside" />
                        <h3>Keranjang Kosong</h3>
                        <p>Belum ada produk di keranjang belanja Anda</p>
                        <a href="/home">Mulai Belanja</a>
                    </div>
                @else
                    @foreach ($cart as $id_barang => $qty)
                        @php
                            $product = $products->firstWhere('id_barang', $id_barang);
                        @endphp
                        @if($product)
                        <div class="cart-item"
                             data-product-id="{{ $id_barang }}"
                             data-remove-url="{{ route('cart.remove', $id_barang) }}">
                            <!-- CHECKBOX: pilih item -->
                            <div class="item-checkbox-wrapper">
                                <input type="checkbox" 
                                       class="item-checkbox" 
                                       id="item-{{ $id_barang }}"
                                       data-product-id="{{ $id_barang }}"
                                       onchange="handleItemSelect('{{ $id_barang }}')"
                                       checked>
                            </div>
                            <!-- KIRI: gambar + nama di samping -->
                            <div class="item-image-wrapper">
                                <img src="{{ $product->gambar_url }}" alt="Product image of {{ $product->nama_barang }} displayed in shopping cart" class="product-image" />
                                <div class="item-name">{{ $product->nama_barang }}</div>
                            </div>

                            <!-- TENGAH: kontrol qty saja -->
                            <div class="item-controls">
                                <div class="item-quantity">
                                    <button class="btn-circle" 
                                            onclick="updateQuantity('{{ $id_barang }}', -1)" 
                                            aria-label="Decrease quantity of {{ $product->nama_barang }}"
                                            {{ $qty <= 1 ? 'disabled' : '' }}>
                                        <img src="{{ $qty > 1 ? asset('assets/btn-minus-active.svg') : asset('assets/btn-minus-disabled.svg') }}" alt="Minus button icon for decreasing item quantity" />
                                    </button>
                                    <span class="quantity-value" data-quantity="{{ $qty }}">{{ $qty }}</span>
                                    <button class="btn-circle" onclick="updateQuantity('{{ $id_barang }}', 1)" aria-label="Increase quantity of {{ $product->nama_barang }}">
                                        <img src="{{ asset('assets/btn-plus.svg') }}" alt="Plus button icon for increasing item quantity" />
                                    </button>
                                </div>
                            </div>

                            <!-- KANAN: trash di atas, harga di bawah -->
                            <div class="item-actions">
                                <button class="remove-btn" onclick="confirmRemoveItem('{{ $id_barang }}')" aria-label="Remove {{ $product->nama_barang }} from cart">
                                    <img src="{{ asset('assets/trash-icon.png') }}" alt="Red trash bin icon for removing single item from shopping cart" />
                                </button>

                                <div class="item-price" data-price="{{ $product->harga_satuan }}">
                                    Rp {{ number_format($product->harga_satuan * $qty, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right Column - Order Summary -->
        <div class="summary-section">
            <div class="order-summary">
                <h3 class="summary-title">Ringkasan Pesanan</h3>

                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value" id="subtotalValue">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Diskon</span>
                    <span class="summary-value">Rp 0</span>
                </div>

                <div class="summary-divider"></div>

                <div class="total-row">
                    <span class="total-label">Total Belanja</span>
                    <span class="total-value" id="totalValue">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                </div>

                <button type="button"
                        id="checkoutBtn"
                        class="checkout-btn {{ empty($cart) || count($cart) === 0 ? 'disabled' : '' }}"
                        onclick="goToCheckout()"
                        {{ empty($cart) || count($cart) === 0 ? 'disabled' : '' }}>
                    Beli Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <h3 id="modalTitle">Konfirmasi</h3>
            <p id="modalMessage">Apakah Anda yakin?</p>
            <div class="modal-actions">
                <button class="modal-btn cancel" onclick="closeModal()">Batal</button>
                <button class="modal-btn confirm" id="modalConfirmBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Profile Overlay -->
    <div class="profile-overlay" id="profileOverlay" onclick="closeProfileMenu()"></div>

    <!-- Footer -->
    <div class="footer">
        <h2 class="footer-title">Toko Kelontong Bu Untung, Belanja menjadi mudah</h2>
        <p class="footer-text">
            Toko Kelontong Bu Untung adalah usaha ritel di Kebumen yang menyediakan berbagai kebutuhan pokok seperti beras, gula, minyak goreng, makanan ringan, serta barang rumah tangga lainnya. Toko ini melayani warga sekitar dan menjadi tempat belanja yang praktis serta terjangkau.
        </p>

        <h3 class="footer-subtitle">Cara Belanja di Toko Kelontong Bu Untung</h3>
        <p class="footer-text">
            Belanja kebutuhan harian kini jadi lebih praktis dan hemat waktu. Tanpa perlu keluar rumah atau menghadapi kemacetan, kamu bisa memenuhi semua kebutuhan hanya melalui website Toko Kelontong Bu Untung.
        </p>
        <p class="footer-text">
            Cukup buka website ini, pilih produk yang kamu butuhkan, lalu selesaikan pembayaran dengan mudah menggunakan QRIS. Tidak perlu install aplikasi, tidak ada biaya tambahan—semua pesanan dikirim langsung tanpa ribet! Belanja jadi lebih santai, cepat, dan menyenangkan, langsung dari HP atau laptop, kapan pun kamu butuh. Toko Kelontong Bu Untung siap melayani kamu dari berbagai penjuru Indonesia, dengan produk lengkap dan harga terjangkau.
        </p>
    </div>

    <script>
        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show toast notification
        function showToast(message, type = 'success') {
            // Remove existing toasts
            const existingToasts = document.querySelectorAll('.toast');
            existingToasts.forEach(toast => toast.remove());

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 2700);
        }

        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        // Update cart totals (only for selected items)
        function updateCartTotals() {
            let subtotal = 0;
            const items = document.querySelectorAll('.cart-item');
            let selectedCount = 0;
            
            items.forEach(item => {
                const checkbox = item.querySelector('.item-checkbox');
                if (checkbox && checkbox.checked) {
                    const quantity = parseInt(item.querySelector('.quantity-value').getAttribute('data-quantity'));
                    const price = parseInt(item.querySelector('.item-price').getAttribute('data-price'));
                    subtotal += quantity * price;
                    selectedCount++;
                    
                    // Add selected class for visual feedback
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });

            document.getElementById('subtotalValue').textContent = formatCurrency(subtotal);
            document.getElementById('totalValue').textContent = formatCurrency(subtotal);

            // Enable/disable checkout button based on selected items
            const checkoutBtn = document.getElementById('checkoutBtn');
            if (checkoutBtn) {
                checkoutBtn.disabled = selectedCount === 0 || items.length === 0;
                if (selectedCount === 0 || items.length === 0) {
                    checkoutBtn.classList.add('disabled');
                } else {
                    checkoutBtn.classList.remove('disabled');
                }
            }

            // Show/hide delete all button
            const deleteAllBtn = document.querySelector('.delete-all');
            if (items.length === 0) {
                deleteAllBtn.style.display = 'none';
            } else {
                deleteAllBtn.style.display = 'flex';
            }

            // Update select all checkbox state
            updateSelectAllCheckbox();
        }

        // Handle item selection
        function handleItemSelect(id_barang) {
            const checkbox = document.getElementById(`item-${id_barang}`);
            const item = checkbox.closest('.cart-item');
            
            if (checkbox.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
            
            updateCartTotals();
        }

        // Toggle select all
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const isChecked = selectAllCheckbox.checked;
            
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                const item = checkbox.closest('.cart-item');
                if (isChecked) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });
            
            updateCartTotals();
        }

        // Update select all checkbox state based on individual selections
        function updateSelectAllCheckbox() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            if (!selectAllCheckbox) return;
            
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const checkedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
            
            if (checkedCount === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedCount === itemCheckboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }

        // Go to checkout with selected items
        function goToCheckout() {
            const selectedItems = [];
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            
            if (checkboxes.length === 0) {
                showToast('Pilih minimal satu barang untuk dibeli', 'error');
                return;
            }
            
            checkboxes.forEach(checkbox => {
                const id_barang = checkbox.getAttribute('data-product-id');
                selectedItems.push(id_barang);
            });
            
            // Create form to submit selected items
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("checkout.index") }}';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Add selected items
            selectedItems.forEach((id_barang, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `selected_items[${index}]`;
                input.value = id_barang;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }

        // Update quantity
        async function updateQuantity(id_barang, change) {
            const item = document.querySelector(`[data-product-id="${id_barang}"]`);
            if (!item) return;

            const jumlahEl = item.querySelector('.quantity-value');
            const currentJumlah = parseInt(jumlahEl.getAttribute('data-quantity'));
            const newJumlah = currentJumlah + change;

            if (newJumlah < 1) {
                confirmRemoveItem(id_barang);
                return;
            }

            item.classList.add('loading');

            // Update minus button icon and disabled state based on new quantity
            const minusBtn = item.querySelector('.btn-circle:first-child');
            const minusBtnImg = minusBtn.querySelector('img');
            if (newJumlah === 1) {
                minusBtn.disabled = true;
                minusBtnImg.src = '{{ asset('assets/btn-minus-disabled.svg') }}';
            } else {
                minusBtn.disabled = false;
                minusBtnImg.src = '{{ asset('assets/btn-minus-active.svg') }}';
            }

            try {
                const response = await fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id_barang: id_barang,
                        jumlah_pesanan: newJumlah
                    })
                });

                const data = await response.json();
                if (data.success) {
                    jumlahEl.textContent = newJumlah;
                    jumlahEl.setAttribute('data-quantity', newJumlah);

                    const price = parseInt(item.querySelector('.item-price').getAttribute('data-price'));
                    item.querySelector('.item-price').textContent = formatCurrency(price * newJumlah);
                    
                    // Only update totals if item is selected
                    const checkbox = item.querySelector('.item-checkbox');
                    if (checkbox && checkbox.checked) {
                        updateCartTotals();
                    }
                    showToast('Jumlah produk diperbarui', 'success');
                } else {
                    showToast(data.message || 'Gagal memperbarui jumlah produk', 'error');
                }
            } catch (error) {
                console.error('Error updating jumlah_pesanan:', error);
                showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
            } finally {
                item.classList.remove('loading');
            }
        }

        // Remove single item
        async function removeItem(id_barang) {
            const item = document.querySelector(`[data-product-id="${id_barang}"]`);
            if (!item) return;

            item.classList.add('loading');

            try {
                const url = item.getAttribute('data-remove-url');

                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ _method: 'DELETE' })
                });

                const data = await res.json();

                if (data.success) {
                    item.style.animation = 'fadeOut .3s ease-out';
                    setTimeout(() => {
                        item.remove();
                        updateCartTotals();
                        // Update select all checkbox state after removal
                        updateSelectAllCheckbox();
                    }, 300);
                    showToast('Produk berhasil dihapus', 'success');
                } else {
                    showToast(data.message || 'Gagal menghapus produk', 'error');
                    item.classList.remove('loading');
                }
            } catch (e) {
                console.error(e);
                showToast('Terjadi kesalahan. Coba lagi.', 'error');
                item.classList.remove('loading');
            }
        }

        // Clear entire cart
        async function clearCart() {
            const cartSection = document.querySelector('.cart-items');
            cartSection.classList.add('loading');

            try {
                const response = await fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Keranjang berhasil dikosongkan', 'success');
                    
                    // Clear select all checkbox wrapper
                    const selectAllWrapper = document.querySelector('.select-all-wrapper');
                    if (selectAllWrapper) {
                        selectAllWrapper.style.display = 'none';
                    }
                    
                    // Reload page after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    cartSection.classList.remove('loading');
                    showToast(data.message || 'Gagal mengosongkan keranjang', 'error');
                }
            } catch (error) {
                console.error('Error clearing cart:', error);
                cartSection.classList.remove('loading');
                showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        }

        // Modal functions
        let modalConfirmCallback = null;

        function showModal(title, message, confirmCallback) {
            const modal = document.getElementById('confirmModal');
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalMessage').textContent = message;
            
            modalConfirmCallback = confirmCallback;
            
            modal.classList.add('active');
        }

        function closeModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('active');
            modalConfirmCallback = null;
        }

        function confirmRemoveItem(id_barang) {
            showModal(
                'Hapus Produk',
                'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
                () => removeItem(id_barang)
            );
        }

        function confirmClearCart() {
            showModal(
                'Hapus Semua Produk',
                'Apakah Anda yakin ingin mengosongkan seluruh keranjang belanja?',
                () => clearCart()
            );
        }

        // Modal confirm button handler
        document.getElementById('modalConfirmBtn').addEventListener('click', function() {
            if (modalConfirmCallback) {
                modalConfirmCallback();
            }
            closeModal();
        });

        // Close modal when clicking outside
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('confirmModal');
                if (modal.classList.contains('active')) {
                    closeModal();
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all items as selected by default
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                const item = checkbox.closest('.cart-item');
                if (item) {
                    item.classList.add('selected');
                }
            });
            updateCartTotals();
        });

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
            
            // Jika dropdown tidak aktif, tidak perlu melakukan apa-apa
            if (!dropdown || !dropdown.classList.contains('active')) {
                return;
            }
            
            // Jika klik pada overlay, tutup dropdown
            if (event.target === overlay) {
                closeProfileMenu();
                return;
            }
            
            // Jika klik di luar profile menu, tutup dropdown
            if (profileMenu && !profileMenu.contains(event.target)) {
                closeProfileMenu();
            }
        });
    </script>
</body>
</html>