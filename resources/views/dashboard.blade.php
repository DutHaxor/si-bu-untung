{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
  <style>
    .dash-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;gap:16px}
    .dash-topbar-title{font-size:28px;font-weight:800;letter-spacing:.3px;margin:0;color:#0f1421}
    .dash-divider{height:1px;background:#e6e8f0;margin:10px 0 28px}
    .dash-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:22px}
    .dash-card{background:#fff;border-radius:18px;border:1px solid #eceef6;box-shadow:0 18px 32px rgba(15,20,33,.08);padding:22px;animation:fadeIn .4s ease}
    .dash-metric{display:flex;align-items:flex-start;gap:14px}
    .dash-icon{width:48px;height:48px;border-radius:16px;display:grid;place-items:center;font-size:0}
    .dash-icon.bag{background:#fff2ec;color:#f0592b}
    .dash-icon.people{background:#ecfff4;color:#1f9d55}
    .dash-icon.calendar{background:#fff3f3;color:#e63939}
    .dash-icon.warning{background:#fff8e6;color:#f59e0b}
    .dash-metric h4{margin:0;font-weight:600;color:#6b7280;font-size:.95rem}
    .dash-value{margin:6px 0 0;font-size:1.45rem;font-weight:800;letter-spacing:.3px;color:#111}
    @keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
    @media (max-width:640px){
      .dash-topbar{flex-direction:column;align-items:flex-start}
    }
  </style>

  <div class="dash-topbar">
    <div>
      <p class="dash-topbar-title">Owner</p>
      <p style="margin:4px 0 0;color:#6b7280;font-weight:500;font-size:.95rem">Ringkasan performa toko</p>
    </div>
  </div>

  <div class="dash-divider"></div>

  {{-- GRID METRICS --}}
  @php
    $formatRupiah = fn($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $totalPenjualan = $totalPenjualan ?? 0;
    $totalPengunjung = $totalPengunjung ?? 0;
    $hampirExpire = $hampirExpire ?? 0;
    $hampirHabis = $hampirHabis ?? 0;
  @endphp

  <div class="dash-grid">
    <div class="dash-card">
      <div class="dash-metric">
        <div class="dash-icon bag">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path d="M6 7V6a6 6 0 1 1 12 0v1h2v14H4V7h2Zm2 0h8V6a4 4 0 1 0-8 0v1Z"/>
          </svg>
        </div>
        <div>
          <h4>Total Penjualan</h4>
          <div class="dash-value">{{ $formatRupiah($totalPenjualan) }}</div>
        </div>
      </div>
    </div>

    <div class="dash-card">
      <div class="dash-metric">
        <div class="dash-icon people">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path d="M16 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm-8 1a3 3 0 1 0-3-3 3 3 0 0 0 3 3Zm8 2c-3.3 0-10 1.7-10 5v2h20v-2c0-3.3-6.7-5-10-5Zm-8 0C4.7 14 0 15.7 0 19v2h6v-2c0-2 .9-3.1 2-3.9Z"/>
          </svg>
        </div>
        <div>
          <h4>Total Pengunjung</h4>
          <div class="dash-value">{{ $totalPengunjung }}</div>
        </div>
      </div>
    </div>

    <div class="dash-card">
      <div class="dash-metric">
        <div class="dash-icon calendar">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2Zm13 7H4v11h16V9Z"/>
          </svg>
        </div>
        <div>
          <h4>Total Barang Hampir Kadaluwarsa</h4>
          <div class="dash-value">{{ $hampirExpire }}</div>
        </div>
      </div>
    </div>

    <div class="dash-card">
      <div class="dash-metric">
        <div class="dash-icon warning">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path d="M1 21h22L12 2 1 21Zm12-3h-2v-2h2v2Zm0-4h-2v-4h2v4Z"/>
          </svg>
        </div>
        <div>
          <h4>Total Barang Hampir Habis</h4>
          <div class="dash-value">{{ $hampirHabis }}</div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
