<x-app-layout>
  <x-slot name="header"><span class="font-extrabold text-xl">Owner</span></x-slot>

  @php
    // ambil parameter pencarian & filter
    $q           = request('q');
    $periode     = request('periode');
    $statusFilter = request('status');

    $today     = \Carbon\Carbon::today();
    $warnDays  = 30;  // N hari ke depan dianggap "Hampir Kadaluwarsa"
    $lowStock  = 10;  // ambang "Hampir Habis"

    $fmtTanggal = function($tgl) {
      if (!$tgl) return '-';
      try { return \Carbon\Carbon::parse($tgl)->format('d/m/Y'); } catch (\Throwable $e) { return '-'; }
    };

    $statusBarang = function($b) use ($today, $warnDays, $lowStock) {
      $exp = $b->tanggal_kedaluwarsa ? \Carbon\Carbon::parse($b->tanggal_kedaluwarsa) : null;

      if ($exp && $exp->lt($today)) return 'Kadaluwarsa';
      if ($exp && $exp->between($today, $today->copy()->addDays($warnDays))) return 'Hampir Kadaluwarsa';
      if ((int)($b->stok_barang ?? 0) <= $lowStock) return 'Hampir Habis';
      return 'Aman';
    };
  @endphp

  <style>
    * {
      transition: all 0.2s ease;
    }

    .page{
      max-width:1100px;
      margin:0 auto;
      animation: fadeIn 0.4s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .title-wrap{
      display:flex;
      justify-content:center;
      margin-bottom:24px;
    }

    .title{
      font:800 32px/1.2 'Poppins',system-ui;
      text-align:center;
      margin:0;
      padding:0;
      max-width:700px;
      width:100%;
      background: linear-gradient(135deg, #111827 0%, #374151 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .search-wrap{
      display:flex;
      justify-content:center;
      margin-bottom:18px;
    }

    /* baris search + filter */
    .search-row{
      display:flex;
      align-items:center;
      gap:12px;
      width:100%;
      max-width:700px;
    }

    .search{
      position:relative;
      flex:1;
    }

    .search input{
      width:100%;
      height:40px;
      border:1px solid #e5e7eb;
      border-radius:999px;
      padding:0 14px 0 44px;
      background:#fff;
      font:500 14px/40px 'Poppins',system-ui;
      transition: all 0.3s ease;
    }

    .search input:focus{
      outline:none;
      border-color:#2563eb;
      box-shadow:0 0 0 3px rgba(37,99,235,0.1);
      transform: translateY(-1px);
    }

    .search input:hover{
      border-color:#cbd5e1;
    }

    /* tombol ikon submit */
    .search .btn-search{
      position:absolute;
      left:8px;
      top:50%;
      transform:translateY(-50%);
      width:28px;
      height:28px;
      border:0;
      background:transparent;
      color:#111827;
      display:grid;
      place-items:center;
      cursor:pointer;
      transition: color 0.2s ease, transform 0.2s ease;
      z-index:5;
    }

    .search .btn-search svg{
      display:block;
      pointer-events:none;
      transition: none;
    }

    .search .btn-search:hover{
      color:#2563eb;
      transform: translateY(-50%) scale(1.1);
    }

    .search .btn-search:active{
      transform: translateY(-50%) scale(0.98);
    }

    .search .btn-search:focus{
      outline:2px solid #2563eb;
      outline-offset:2px;
      border-radius:4px;
    }

    .search .btn-search:focus:not(:focus-visible){
      outline:none;
    }

    /* FILTER BUTTON + PANEL */
    details.filter{
      position:relative;
    }

    details.filter summary{
      list-style:none;
    }

    details.filter summary::-webkit-details-marker{
      display:none;
    }

    .filter-toggle{
      display:inline-flex;
      align-items:center;
      gap:6px;
      padding:0 14px;
      height:40px;
      border-radius:999px;
      border:1px solid #e5e7eb;
      background:#fff;
      font:600 13px 'Poppins',system-ui;
      color:#111827;
      cursor:pointer;
      box-shadow:0 4px 10px rgba(15,23,42,.07);
      transition: all 0.3s ease;
    }

    .filter-toggle:hover{
      background:#f9fafb;
      border-color:#2563eb;
      box-shadow:0 6px 15px rgba(15,23,42,.12);
      transform: translateY(-2px);
    }

    .filter-toggle:active{
      transform: translateY(0);
    }

    details.filter[open] .filter-toggle{
      background:#eff6ff;
      border-color:#2563eb;
      color:#2563eb;
    }

    .filter-toggle svg{
      width:16px;
      height:16px;
      color:#000;
      stroke-width:2;
      transition: transform 0.3s ease;
    }

    details.filter[open] .filter-toggle svg{
      color:#2563eb;
      transform: rotate(180deg);
    }

    .filter-panel{
      position:absolute;
      right:0;
      margin-top:8px;
      width:260px;
      background:#fff;
      border-radius:14px;
      box-shadow:0 12px 30px rgba(15,23,42,.18);
      padding:14px;
      z-index:20;
      animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .filter-panel h4{
      font:700 13px 'Poppins',system-ui;
      margin:0 0 10px;
      color:#111827;
    }

    .filter-group{
      margin-bottom:10px;
    }

    .filter-label{
      display:block;
      font:600 11px 'Poppins',system-ui;
      color:#6b7280;
      margin-bottom:4px;
    }

    .filter-panel select{
      width:100%;
      border:1px solid #e5e7eb;
      border-radius:10px;
      padding:6px 10px;
      font:500 12px 'Poppins',system-ui;
      background:#fff;
      cursor:pointer;
      transition: all 0.2s ease;
    }

    .filter-panel select:hover{
      border-color:#2563eb;
    }

    .filter-panel select:focus{
      outline:none;
      border-color:#2563eb;
      box-shadow:0 0 0 3px rgba(37,99,235,0.1);
    }

    .filter-actions{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-top:6px;
    }

    .btn-reset-filter{
      font:500 11px 'Poppins',system-ui;
      color:#6b7280;
      text-decoration:none;
      padding:4px 8px;
      border-radius:6px;
      transition: all 0.2s ease;
    }

    .btn-reset-filter:hover{
      color:#111827;
      background:#f3f4f6;
    }

    .btn-apply-filter{
      border:0;
      border-radius:10px;
      padding:6px 14px;
      font:600 11px 'Poppins',system-ui;
      background:#2563eb;
      color:#fff;
      cursor:pointer;
      transition: all 0.2s ease;
    }

    .btn-apply-filter:hover{
      background:#1d4ed8;
      transform: translateY(-1px);
      box-shadow:0 4px 12px rgba(37,99,235,0.3);
    }

    .btn-apply-filter:active{
      transform: translateY(0);
    }

    .card{
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:14px;
      box-shadow:0 10px 25px rgba(0,0,0,.06);
      transition: all 0.3s ease;
      overflow:hidden;
    }

    .card:hover{
      box-shadow:0 15px 35px rgba(0,0,0,.1);
      transform: translateY(-2px);
    }

    .card-title{
      padding:14px 18px;
      font:700 14px 'Poppins',system-ui;
      background:linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
      border-bottom:1px solid #e5e7eb;
    }

    table{
      width:100%;
      border-collapse:collapse;
      font:500 13px 'Poppins',system-ui;
    }

    thead th{
      background:#f3f4f6;
      text-align:left;
      padding:10px 14px;
      border-bottom:2px solid #e5e7eb;
      font-weight:600;
      color:#374151;
      position:sticky;
      top:0;
      z-index:10;
    }

    tbody td{
      padding:10px 14px;
      border-top:1px solid #f0f0f0;
      transition: all 0.2s ease;
    }

    tbody tr{
      transition: all 0.2s ease;
    }

    tbody tr:nth-child(odd){
      background:#fcfcfc;
    }

    tbody tr:hover{
      background:#f0f9ff !important;
      transform: scale(1.01);
      box-shadow:0 2px 8px rgba(0,0,0,.05);
    }

    tbody tr:hover td{
      color:#111827;
    }

    .status{
      font-weight:700;
      font-size:12px;
      padding:4px 8px;
      border-radius:6px;
      display:inline-block;
      transition: all 0.2s ease;
    }

    .s-aman{
      color:#16a34a;
      background:#dcfce7;
    }

    .s-hh{
      color:#f59e0b;
      background:#fef3c7;
    }

    .s-hk{
      color:#ef4444;
      background:#fee2e2;
    }

    .s-kadaluarsa{
      color:#b91c1c;
      background:#fee2e2;
    }

    tbody tr:hover .status{
      transform: scale(1.05);
    }

    .pagination{
      display:flex;
      justify-content:center;
      gap:6px;
      padding:12px 16px;
      background:#fafafa;
    }

    .page-btn{
      min-width:28px;
      height:28px;
      padding:0 8px;
      border:1px solid #e5e7eb;
      background:#fff;
      border-radius:6px;
      font:600 12px/28px 'Poppins',system-ui;
      text-align:center;
      color:#111;
      text-decoration:none;
      transition: all 0.2s ease;
      display:inline-flex;
      align-items:center;
      justify-content:center;
    }

    .page-btn:hover:not(.is-active):not([href="#"]){
      background:#f3f4f6;
      border-color:#2563eb;
      color:#2563eb;
      transform: translateY(-2px);
      box-shadow:0 2px 8px rgba(0,0,0,.1);
    }

    .page-btn.is-active{
      background:#2563eb;
      color:#fff;
      border-color:#2563eb;
      box-shadow:0 2px 8px rgba(37,99,235,0.3);
    }

    .page-btn[href="#"]{
      opacity:0.4;
      cursor:not-allowed;
    }

    .page-btn.icon{
      font-weight:600;
    }
  </style>

  <div class="page">
    <div class="title-wrap">
      <h1 class="title">Cari barang yang ingin dicatat</h1>
    </div>

    <div class="search-wrap">
      <form class="search-row" method="get" action="{{ route('ui.laporan-barang') }}">
        {{-- SEARCH INPUT --}}
        <div class="search">
          <button type="submit" class="btn-search" title="Cari" aria-label="Cari">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M21.7961 20.2042L17.3439 15.7501C18.6788 14.0106 19.302 11.8284 19.0871 9.64618C18.8723 7.46401 17.8354 5.44527 16.1868 3.99948C14.5383 2.55369 12.4015 1.7891 10.21 1.86083C8.01841 1.93255 5.9362 2.83521 4.3857 4.3857C2.83521 5.9362 1.93255 8.01841 1.86083 10.21C1.7891 12.4015 2.55369 14.5383 3.99948 16.1868C5.44527 17.8354 7.46401 18.8723 9.64618 19.0871C11.8284 19.302 14.0106 18.6788 15.7501 17.3439L20.2061 21.8007C20.3107 21.9054 20.4349 21.9884 20.5717 22.045C20.7084 22.1017 20.8549 22.1308 21.0029 22.1308C21.1509 22.1308 21.2975 22.1017 21.4342 22.045C21.5709 21.9884 21.6952 21.9054 21.7998 21.8007C21.9044 21.6961 21.9875 21.5719 22.0441 21.4351C22.1007 21.2984 22.1299 21.1519 22.1299 21.0039C22.1299 20.8559 22.1007 20.7093 22.0441 20.5726C21.9875 20.4359 21.9044 20.3116 21.7998 20.207L21.7961 20.2042ZM4.12512 10.5001C4.12512 9.23926 4.499 8.00672 5.1995 6.95836C5.89999 5.90999 6.89563 5.09289 8.06051 4.61038C9.22539 4.12788 10.5072 4.00163 11.7438 4.24761C12.9804 4.49359 14.1164 5.10075 15.0079 5.99231C15.8995 6.88387 16.5066 8.01979 16.7526 9.25642C16.9986 10.493 16.8724 11.7748 16.3898 12.9397C15.9073 14.1046 15.0902 15.1002 14.0419 15.8007C12.9935 16.5012 11.761 16.8751 10.5001 16.8751C8.80989 16.8734 7.1894 16.2012 5.99423 15.006C4.79906 13.8108 4.12685 12.1903 4.12512 10.5001Z" fill="currentColor"/>
            </svg>
          </button>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Barang...">
        </div>

        {{-- FILTER KADALUWARSA --}}
        <details class="filter">
          <summary class="filter-toggle">
            {{-- icon filter seperti gambar --}}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4h18l-7 8v6l-4 2v-8L3 4z" />
            </svg>
            <span>Filter</span>
          </summary>

          <div class="filter-panel">
            <h4>Filter Barang</h4>

            <div class="filter-group">
              <label class="filter-label">Status</label>
              <select name="status">
                <option value="">Semua Status</option>
                <option value="Kadaluwarsa" {{ request('status') == 'Kadaluwarsa' ? 'selected' : '' }}>Kadaluwarsa</option>
                <option value="Hampir Kadaluwarsa" {{ request('status') == 'Hampir Kadaluwarsa' ? 'selected' : '' }}>Hampir Kadaluwarsa</option>
                <option value="Hampir Habis" {{ request('status') == 'Hampir Habis' ? 'selected' : '' }}>Hampir Habis</option>
                <option value="Aman" {{ request('status') == 'Aman' ? 'selected' : '' }}>Aman</option>
              </select>
            </div>

            <div class="filter-group">
              <label class="filter-label">Rentang Periode Kadaluwarsa</label>
              <select name="periode">
                <option value="">Semua Periode</option>
                <option value="harian" {{ request('periode') == 'harian' ? 'selected' : '' }}>Harian (Hari Ini)</option>
                <option value="mingguan" {{ request('periode') == 'mingguan' ? 'selected' : '' }}>Mingguan (7 Hari)</option>
                <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Bulanan (30 Hari)</option>
                <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan (365 Hari)</option>
              </select>
            </div>

            <div class="filter-actions">
              <a href="{{ route('ui.laporan-barang') }}" class="btn-reset-filter">
                Reset
              </a>
              <button type="submit" class="btn-apply-filter">
                Terapkan
              </button>
            </div>
          </div>
        </details>
      </form>
    </div>

    <div class="card">
      <div class="card-title">Table Barang</div>
      <div style="overflow-x:auto">
        <table>
          <thead>
            <tr>
              <th style="width:80px">Id Barang</th>
              <th>Nama Barang</th>
              <th style="width:200px">Tanggal Kadaluwarsa</th>
              <th style="width:120px">Stok Barang</th>
              <th style="width:150px">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($barangs as $b)
              @php
                $st = $statusBarang($b);
                $cls = match ($st) {
                  'Kadaluwarsa' => 's-kadaluarsa',
                  'Hampir Kadaluwarsa' => 's-hk',
                  'Hampir Habis' => 's-hh',
                  default => 's-aman',
                };
              @endphp
              <tr>
                <td>{{ $b->id_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $fmtTanggal($b->tanggal_kedaluwarsa ?? null) }}</td>
                <td>{{ (int) $b->stok_barang }}</td>
                <td class="status {{ $cls }}">{{ $st }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align:center;color:#6b7280;padding:16px">Tidak ada data.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @php
        $current = $barangs->currentPage();
        $last    = $barangs->lastPage();
      @endphp
      <div class="pagination">
        <a class="page-btn icon" href="{{ $barangs->previousPageUrl() ?? '#' }}">«</a>
        @for($i = max(1, $current-2); $i <= min($last, $current+2); $i++)
          <a class="page-btn {{ $i==$current ? 'is-active' : '' }}" href="{{ $barangs->url($i) }}">{{ $i }}</a>
        @endfor
        <a class="page-btn icon" href="{{ $barangs->nextPageUrl() ?? '#' }}">»</a>
      </div>
    </div>
  </div>
</x-app-layout>
