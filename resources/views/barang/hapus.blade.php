<x-app-layout>
  <x-slot name="header"><span class="font-extrabold text-xl">Owner</span></x-slot>

  <style>
    /* Animasi Keyframes */
    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideDownFadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
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

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    /* Styling dengan animasi */
    .h1-title{
      font-weight:800;font-size:32px;line-height:1.2;text-align:center;margin:0 0 18px;
      animation: fadeInUp 0.5s ease-out 0.2s backwards;
    }
    
    .search-wrap{
      display:flex;justify-content:center;margin-bottom:22px;
      animation: fadeInUp 0.5s ease-out 0.3s backwards;
    }
    
    .search{position:relative;width:100%;max-width:560px}

    .search input{
      width:100%;height:40px;border:1px solid #e5e7eb;border-radius:999px;
      padding:0 14px 0 44px; /* ruang kiri untuk tombol ikon */
      background:#fff;font:500 14px/40px 'Poppins',system-ui;
      transition: all 0.3s ease;
      position: relative;
      z-index: 1;
    }
    .search input:focus {
      outline: none;
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
      transform: scale(1.02);
    }

    /* tombol ikon search yang bisa di-klik (submit) */
    .search .btn-search{
      position:absolute;left:8px;top:50%;transform:translateY(-50%);
      width:28px;height:28px;border:0;background:transparent;color:#9ca3af;
      display:grid;place-items:center;cursor:pointer;
      transition: all 0.3s ease;
      z-index: 10;
      pointer-events: auto;
    }
    .search .btn-search:hover{
      color:#2563eb;
      transform: translateY(-50%) scale(1.15);
    }
    .search .btn-search:active {
      transform: translateY(-50%) scale(0.95);
    }
    .search .btn-search:focus{
      outline:2px solid #2563eb;outline-offset:2px;
      color:#2563eb;
    }
    .search:focus-within .btn-search {
      color: #2563eb;
    }
    .search .btn-search svg{
      width:18px;height:18px;
      transition: transform 0.3s ease;
      pointer-events: none;
      display: block;
    }
    .search .btn-search:hover svg {
      transform: rotate(15deg);
    }

    .tbl-card{
      background:#fff;border:1px solid #e5e7eb;border-radius:14px;
      box-shadow:0 10px 25px rgba(0,0,0,.06);
      animation: fadeInUp 0.5s ease-out 0.4s backwards;
    }
    
    .tbl-title{
      padding:16px 20px;font:600 14px 'Poppins',system-ui;
    }
    
    table{
      width:100%;border-collapse:collapse;font:500 13px 'Poppins',system-ui
    }
    
    thead th{
      background:#f3f4f6;text-align:left;padding:10px 14px;border-bottom:1px solid #e5e7eb
    }
    
    tbody td{
      padding:10px 14px;border-top:1px solid #f0f0f0
    }
    
    tbody tr:nth-child(odd){
      background:#fcfcfc
    }
    
    tbody tr {
      animation: slideInLeft 0.4s ease-out forwards;
      opacity: 0;
    }
    tbody tr:nth-child(1) { animation-delay: 0.5s; }
    tbody tr:nth-child(2) { animation-delay: 0.55s; }
    tbody tr:nth-child(3) { animation-delay: 0.6s; }
    tbody tr:nth-child(4) { animation-delay: 0.65s; }
    tbody tr:nth-child(5) { animation-delay: 0.7s; }
    tbody tr:nth-child(6) { animation-delay: 0.75s; }
    tbody tr:nth-child(7) { animation-delay: 0.8s; }
    tbody tr:nth-child(8) { animation-delay: 0.85s; }
    tbody tr:nth-child(n+9) { animation-delay: 0.9s; }

    .btn-del{
      width:26px;height:26px;border-radius:999px;border:0;background:#3b82f6;
      display:inline-grid;place-items:center;color:#fff;cursor:pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
    }
    .btn-del:hover{
      filter:brightness(.95);
      transform: scale(1.2) rotate(5deg);
      background: #ef4444;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
      animation: shake 0.5s ease-in-out;
    }
    .btn-del:active {
      transform: scale(0.9) rotate(-5deg);
    }
    .btn-del svg{
      width:14px;height:14px;
      transition: transform 0.3s ease;
    }
    .btn-del:hover svg {
      transform: scale(1.2) rotate(10deg);
    }

    .pagination{
      display:flex;justify-content:center;gap:6px;padding:12px 16px;
      animation: fadeIn 0.5s ease-out 0.6s backwards;
    }
    
    .page-btn{
      min-width:28px;height:28px;padding:0 8px;border:1px solid #e5e7eb;background:#fff;
      border-radius:6px;font:600 12px/28px 'Poppins',system-ui;text-align:center;color:#111;
      transition: all 0.2s ease;
      text-decoration: none;
      display: inline-block;
    }
    .page-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      background: #f3f4f6;
    }
    .page-btn.is-active{
      background:#2563eb;color:#fff;border-color:#2563eb;
      animation: pulse 2s ease-in-out infinite;
    }
    .page-btn.icon{font-weight:600}

    .alert{
      margin:10px 0 18px;padding:10px 12px;border-radius:10px;
      font:600 13px 'Poppins',system-ui;
      animation: slideDownFadeIn 0.5s ease-out;
    }
    .alert-success{
      background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;
    }

    .page-container{
      max-width:1100px;margin:0 auto;
      animation: fadeIn 0.6s ease-out;
    }

    /* tambahan untuk kolom gambar & harga */
    .thumb{
      width:44px;height:44px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }
    .thumb:hover {
      transform: scale(1.3);
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      z-index: 10;
      position: relative;
    }
    .price{white-space:nowrap}
  </style>

  <div class="page-container">
    <h1 class="h1-title">Cari barang yang ingin di hapus</h1>

    {{-- Flash sukses --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <div class="search-wrap">
      <form class="search" method="get" action="{{ route('ui.hapus') }}">
        <button type="submit" class="btn-search" title="Cari" aria-label="Cari">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M15.5 14h-.79l-.28-.27A6.5 6.5 0 1 0 14 15.5l.27.28h.79l5 5 1.5-1.5-5-5ZM10 15.5A5.5 5.5 0 1 1 10 4.5a5.5 5.5 0 0 1 0 11Z"/>
          </svg>
        </button>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Barang...">
      </form>
    </div>

    {{-- Tabel + pagination --}}
    <div class="tbl-card">
      <div class="tbl-title">Table Barang</div>
      <div style="overflow-x:auto">
        <table>
          <thead>
            <tr>
              <th style="width:120px">Id Barang</th>
              <th>Nama Barang</th>
              <th style="width:140px">Harga Satuan</th>
              <th style="width:90px">Gambar</th>
              <th style="width:200px">Tanggal Kadaluwarsa</th>
              <th style="width:120px">Stok Barang</th>
              <th style="width:70px;text-align:center"> </th>
            </tr>
          </thead>
          <tbody>
            @forelse(($barangs ?? []) as $b)
              <tr>
                <td>{{ $b->id_barang }}</td>
                <td>{{ $b->nama_barang }}</td>

                {{-- HARGA SATUAN --}}
                <td class="price">
                  @php
                    $harga = is_numeric($b->harga_satuan ?? null) ? (float)$b->harga_satuan : null;
                  @endphp
                  {{ $harga !== null ? 'Rp '.number_format($harga, 0, ',', '.') : '-' }}
                </td>

                {{-- GAMBAR --}}
                <td>
                  @php
                    $src = $b->gambar_url ?: null; // pakai URL dari DB
                  @endphp

                  @if ($src)
                    <img src="{{ $src }}" alt="{{ $b->nama_barang }}" class="thumb">
                  @else
                    <span style="color:#9ca3af">-</span>
                  @endif
                </td>

                {{-- TANGGAL KADALUWARSA --}}
                <td>
                  {{ ($b->tanggal_kedaluwarsa ?? null)
                      ? \Carbon\Carbon::parse($b->tanggal_kedaluwarsa)->format('d/m/Y')
                      : '-' }}
                </td>

                <td>{{ $b->stok_barang }}</td>

                <td style="text-align:center">
                  <form method="POST"
                        action="{{ route('barang.destroy', $b) }}"
                        onsubmit="return confirm('Hapus barang {{ $b->nama_barang }} ({{ $b->id_barang }})?')">
                    @csrf
                    @method('DELETE')
                    {{-- Pastikan kembali ke halaman & query yang sama setelah hapus --}}
                    <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                    <button type="submit" class="btn-del" title="Hapus" aria-label="Hapus {{ $b->nama_barang }}">
                      <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M9 3h6l1 2h5v2H3V5h5l1-2Zm1 6h2v9h-2V9Zm4 0h2v9h-2V9ZM7 9h2v9H7V9Z"/>
                      </svg>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align:center;color:#6b7280;padding:16px">
                  Tidak ada data.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @isset($barangs)
        @php
          $current = $barangs->currentPage();
          $last    = $barangs->lastPage();
        @endphp
        <div class="pagination">
          <a class="page-btn icon" href="{{ $barangs->previousPageUrl() ?? '#' }}">«</a>
          @for($i = max(1, $current-2); $i <= min($last, $current+2); $i++)
            <a class="page-btn {{ $i==$current ? 'is-active' : '' }}"
               href="{{ $barangs->url($i) }}">{{ $i }}</a>
          @endfor
          <a class="page-btn icon" href="{{ $barangs->nextPageUrl() ?? '#' }}">»</a>
        </div>
      @endisset
    </div>
  </div>
</x-app-layout>