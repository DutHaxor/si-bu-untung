<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Transaksi — Toko Kelontong Bu Untung</title>
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
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            color: #000000;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .back-link:hover {
            transform: translateX(-4px);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
        }

        /* Transaction List */
        .transaction-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .transaction-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .transaction-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e5e5;
        }

        .transaction-id {
            font-size: 16px;
            font-weight: 600;
            color: #000000;
        }

        .transaction-date {
            font-size: 14px;
            color: #666666;
        }

        .transaction-status {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-dibayar {
            background: #d4edda;
            color: #155724;
        }

        .status-dalam-pengiriman {
            background: #fff3cd;
            color: #856404;
        }

        .status-dikirim {
            background: #cce5ff;
            color: #004085;
        }

        .status-terkirim {
            background: #d4edda;
            color: #155724;
        }

        .transaction-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 13px;
            color: #666666;
            font-weight: 500;
        }

        .detail-value {
            font-size: 15px;
            color: #000000;
            font-weight: 600;
        }

        .transaction-items {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e5e5;
        }

        .items-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #000000;
        }

        .item-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
        }

        .item-name {
            color: #333333;
        }

        .item-qty {
            color: #666666;
        }

        .item-price {
            color: #000000;
            font-weight: 600;
        }

        .transaction-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid #e5e5e5;
        }

        .total-label {
            font-size: 16px;
            font-weight: 700;
            color: #000000;
        }

        .total-value {
            font-size: 20px;
            font-weight: 700;
            color: #ff5722;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
        }

        .empty-state-title {
            font-size: 20px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
        }

        .empty-state-text {
            font-size: 14px;
            color: #666666;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 40px;
        }

        .pagination-link {
            padding: 8px 16px;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            color: #000000;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination-link:hover {
            background: #f5f5f5;
            border-color: #ff5722;
        }

        .pagination-link.active {
            background: #ff5722;
            color: #fff;
            border-color: #ff5722;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .container {
                padding: 20px;
            }

            .transaction-details {
                grid-template-columns: 1fr;
            }

            .transaction-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-title">Transaksi Saya</div>
        <a href="{{ route('customer.home') }}" class="back-link">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Container -->
    <div class="container">
        @if($transaksis->count() > 0)
            <div class="transaction-list">
                @foreach($transaksis as $transaksi)
                    <div class="transaction-card">
                        <div class="transaction-header">
                            <div>
                                <div class="transaction-id">ID: {{ $transaksi->id_transaksi }}</div>
                                <div class="transaction-date">
                                    @if($transaksi->tanggal_transaksi)
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    @else
                                        {{ $transaksi->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    @endif
                                </div>
                            </div>
                            <div class="transaction-status status-{{ str_replace('_', '-', strtolower($transaksi->status_transaksi)) }}">
                                @if($transaksi->status_transaksi === 'dalam_pengiriman')
                                    Dalam Pengiriman
                                @elseif($transaksi->status_transaksi === 'terkirim')
                                    Terkirim
                                @else
                                    {{ ucfirst($transaksi->status_transaksi) }}
                                @endif
                            </div>
                        </div>

                        <div class="transaction-details">
                            <div class="detail-item">
                                <div class="detail-label">Tanggal</div>
                                <div class="detail-value">
                                    @if($transaksi->tanggal_transaksi)
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    @else
                                        {{ $transaksi->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    @endif
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Status</div>
                                <div class="detail-value">
                                    @if($transaksi->status_transaksi === 'dalam_pengiriman')
                                        Dalam Pengiriman
                                    @elseif($transaksi->status_transaksi === 'terkirim')
                                        Terkirim
                                    @else
                                        {{ ucfirst($transaksi->status_transaksi) }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($transaksi->detailTransaksis->count() > 0)
                            <div class="transaction-items">
                                <div class="items-title">Item Pesanan:</div>
                                <div class="item-list">
                                    @foreach($transaksi->detailTransaksis as $detail)
                                        <div class="item-row">
                                            <div class="item-name">{{ $detail->barang->nama_barang ?? 'Produk' }}</div>
                                            <div class="item-qty">x{{ $detail->jumlah_pesanan }}</div>
                                            <div class="item-price">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="transaction-total">
                            <div class="total-label">Total</div>
                            <div class="total-value">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($transaksis->hasPages())
                <div class="pagination">
                    @if($transaksis->onFirstPage())
                        <span class="pagination-link" style="opacity: 0.5; cursor: not-allowed;">Previous</span>
                    @else
                        <a href="{{ $transaksis->previousPageUrl() }}" class="pagination-link">Previous</a>
                    @endif

                    @foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                        @if($page == $transaksis->currentPage())
                            <span class="pagination-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($transaksis->hasMorePages())
                        <a href="{{ $transaksis->nextPageUrl() }}" class="pagination-link">Next</a>
                    @else
                        <span class="pagination-link" style="opacity: 0.5; cursor: not-allowed;">Next</span>
                    @endif
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-title">Belum ada transaksi</div>
                <div class="empty-state-text">Mulai belanja untuk melihat riwayat transaksi Anda</div>
            </div>
        @endif
    </div>
</body>
</html>

