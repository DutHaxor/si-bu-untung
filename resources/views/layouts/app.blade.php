{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">

  <style>
    /* ========= THEME (netral, tanpa kebiruan) ========= */
    :root{
      --sb-accent:#7c5cff;           /* boleh tetap, jarang dipakai */
      --sb-accent-2:#5aa3ff;
      --sb-bg:#f7f8fc;               /* body bg TERANG */
      --sb-content:#ffffff;          /* konten bg PUTIH */
      --sb-text:#0f1421;             /* teks utama */
      --sb-muted:#7a839a;            /* teks sekunder */
      --sb-side:#121212;             /* sidebar netral gelap */
      --sb-side-2:#121212;           /* tanpa gradasi biru */
      --sb-hover:#1e1e1e;            /* hover netral */
      --sb-ring:#1f2023;             /* border halus */
      --sb-soft:rgba(124,92,255,.12);
      --sb-radius:16px;
      --sb-shadow:0 20px 40px rgba(5,10,25,.08);
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial;
      background:var(--sb-bg);       /* <— terang/putih, bukan biru gelap */
      color:var(--sb-text);
    }

    /* ========= LAYOUT & COLLAPSE (no JS) ========= */
    .sb-nav-toggle{display:none}
    .sb-layout{
      display:grid;grid-template-columns:300px 1fr;min-height:100vh;
      transition:grid-template-columns .22s ease;
    }
    .sb-nav-toggle:checked + .sb-layout{grid-template-columns:96px 1fr}

    /* ========= SIDEBAR ========= */
    .sb-sidebar{
      position:sticky;top:0;height:100vh;overflow:auto;
      background:var(--sb-side);           /* <— solid, tanpa radial/linear gradient */
      border-right:1px solid var(--sb-ring);
      box-shadow:inset 0 1px 0 rgba(255,255,255,.04);
      color:#e9ebf6;padding:18px 0 12px;
      display:flex;flex-direction:column;
    }
    .sb-sidebar-content{flex:1;overflow-y:auto}
    .sb-sidebar::-webkit-scrollbar{width:8px}
    .sb-sidebar::-webkit-scrollbar-thumb{background:#2a2a2a;border-radius:10px}

    /* Brand */
    .sb-brand{
      display:flex;align-items:center;justify-content:space-between;
      gap:10px;padding:0 16px 16px;margin-bottom:12px;
      border-bottom:1px solid rgba(255,255,255,.06)
    }
    .sb-brand-left{display:flex;align-items:center;gap:12px}
    .sb-dot{
      width:12px;height:12px;border-radius:4px;
      background:#9aa0a6;            /* <— netral (hilangkan aksen ungu) */
      box-shadow:none;
    }
    .sb-brand strong{letter-spacing:.2px}

    /* Toggle button */
    .sb-toggle{
      appearance:none;border:1px solid #2a2a2a;background:#1a1a1a;
      width:44px;height:34px;border-radius:12px;display:grid;place-items:center;cursor:pointer;
      transition:transform .15s ease, background .15s ease;
    }
    .sb-toggle:hover{background:#202020;transform:translateY(-1px)}
    .sb-toggle svg{opacity:.92}

    /* Section label */
    .sb-section{font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:#a6afc8;margin:10px 22px 8px}

    /* Menu */
    .sb-menu{list-style:none;margin:0;padding:8px 10px}
    .sb-menu > li{margin:6px 0}

    /* Pill link */
    .sb-pill{
      position:relative;display:flex;align-items:center;gap:12px;
      padding:12px 14px;border-radius:14px;text-decoration:none;color:#dfe3f7;
      transition:.22s ease;border:1px solid rgba(255,255,255,.03);
      background:linear-gradient(180deg,rgba(255,255,255,.02),rgba(255,255,255,.01));
      box-shadow:inset 0 -1px 0 rgba(255,255,255,.04);
      overflow:hidden;
    }
    .sb-pill:hover{background:var(--sb-hover);border-color:#2a2a2a;translate:0 -1px}
    .sb-icon{
      width:36px;height:36px;border-radius:12px;display:grid;place-items:center;flex:0 0 36px;
      background:#1f1f1f;             /* <— bukan #12183a (biru) */
      border:1px solid #2a2a2a;        /* <— bukan #243063 (biru) */
      box-shadow:inset 0 0 0 1px rgba(255,255,255,.04)
    }
    .sb-label{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .sb-pill.active{
      background:linear-gradient(180deg,rgba(255,255,255,.06),rgba(255,255,255,.04));
      border-color:#2a2a2a;color:#fff;
      box-shadow:inset 0 0 0 1px rgba(255,255,255,.06);
    }
    .sb-pill.active .sb-icon{background:#222;border-color:#2a2a2a}
    .sb-pill.active::before{
      content:"";position:absolute;left:-10px;top:8px;bottom:8px;width:4px;border-radius:8px;
      background:#3a3a3a;box-shadow:0 0 0 6px rgba(255,255,255,.02)
    }

    /* details/summary submenu */
    details.sb-acc{border-radius:14px}
    details.sb-acc > summary{
      list-style:none;cursor:pointer;display:flex;align-items:center;gap:12px;
      padding:12px 14px;border-radius:14px;color:#e1e6ff;border:1px solid rgba(255,255,255,.04);
      background:linear-gradient(180deg,rgba(255,255,255,.02),rgba(255,255,255,.01));
      transition:.22s ease
    }
    details.sb-acc > summary:hover{background:var(--sb-hover);border-color:#2a2a2a}
    details.sb-acc[open] > summary{background:#1a1a1a;border-color:#2a2a2a}
    .sb-caret{margin-left:auto;transition:transform .2s ease;opacity:.92}
    details.sb-acc[open] .sb-caret{transform:rotate(180deg)}

    .sb-submenu{padding:6px 0 12px 58px}
    .sb-submenu a{
      display:flex;align-items:center;gap:10px;text-decoration:none;color:#cfd5f1;padding:9px 0;border-radius:10px;
    }
    .sb-submenu a:hover{color:#fff}
    .sb-submenu a.active{color:#fff;font-weight:600}
    .sb-dotmini{width:6px;height:6px;border-radius:50%;background:#3a3a3a}
    .sb-submenu a:hover .sb-dotmini{background:#9aa0a6}

    /* ========= PROFILE DROPDOWN ========= */
    .sb-profile{
      margin-top:auto;padding:12px 16px;border-top:1px solid rgba(255,255,255,.06);
      position:relative;
    }
    .sb-profile-trigger{
      display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:12px;
      cursor:pointer;transition:.22s ease;border:1px solid rgba(255,255,255,.03);
      background:linear-gradient(180deg,rgba(255,255,255,.02),rgba(255,255,255,.01));
      text-decoration:none;color:#dfe3f7;
    }
    .sb-profile-trigger:hover{background:var(--sb-hover);border-color:#2a2a2a}
    .sb-profile-avatar{
      width:36px;height:36px;border-radius:10px;display:grid;place-items:center;
      background:#1f1f1f;border:1px solid #2a2a2a;flex:0 0 36px;
    }
    .sb-profile-info{flex:1;min-width:0}
    .sb-profile-name{font-weight:600;font-size:13px;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .sb-profile-role{font-size:11px;color:#a6afc8;text-transform:capitalize}
    .sb-profile-dropdown{
      position:absolute;bottom:100%;left:16px;right:16px;margin-bottom:8px;
      background:#1a1a1a;border:1px solid #2a2a2a;border-radius:12px;
      box-shadow:0 8px 24px rgba(0,0,0,.3);opacity:0;visibility:hidden;transform:translateY(8px);
      transition:all .22s ease;z-index:100;overflow:hidden;
    }
    .sb-profile:hover .sb-profile-dropdown{opacity:1;visibility:visible;transform:translateY(0)}
    .sb-profile-dropdown a{
      display:flex;align-items:center;gap:10px;padding:12px 16px;color:#dfe3f7;
      text-decoration:none;transition:.15s ease;border-bottom:1px solid rgba(255,255,255,.03);
    }
    .sb-profile-dropdown a:last-child{border-bottom:none}
    .sb-profile-dropdown a:hover{background:#222;color:#fff}
    .sb-profile-dropdown a svg{width:16px;height:16px;opacity:.8}
    .sb-nav-toggle:checked + .sb-layout .sb-profile-info,
    .sb-nav-toggle:checked + .sb-layout .sb-profile-name,
    .sb-nav-toggle:checked + .sb-layout .sb-profile-role{display:none}
    .sb-nav-toggle:checked + .sb-layout .sb-profile-trigger{justify-content:center;padding:10px}
    .sb-nav-toggle:checked + .sb-layout .sb-profile-dropdown{left:8px;right:8px}

    /* ========= CONTENT (PUTIH solid) ========= */
    .sb-content{
      padding:28px 34px;
      background:var(--sb-content);   /* <— putih solid */
    }
    .sb-card{
      background:#fff;border:1px solid #e8eaf5;border-radius:var(--sb-radius);
      padding:18px;box-shadow:var(--sb-shadow)
    }

    /* ========= COLLAPSE STATES ========= */
    .sb-nav-toggle:checked + .sb-layout .sb-brand strong,
    .sb-nav-toggle:checked + .sb-layout .sb-section,
    .sb-nav-toggle:checked + .sb-layout .sb-label,
    .sb-nav-toggle:checked + .sb-layout .sb-badge{display:none}

    .sb-nav-toggle:checked + .sb-layout details.sb-acc > summary{
      justify-content:center;padding:12px 10px;gap:0
    }
    .sb-nav-toggle:checked + .sb-layout .sb-caret,
    .sb-nav-toggle:checked + .sb-layout .sb-submenu{display:none}
    .sb-nav-toggle:checked + .sb-layout .sb-submenu{padding-left:18px}
    .sb-nav-toggle:checked + .sb-layout .sb-pill{
      justify-content:center;gap:0;padding:12px 10px
    }
    .sb-nav-toggle:checked + .sb-layout .sb-pill .sb-icon,
    .sb-nav-toggle:checked + .sb-layout details.sb-acc > summary .sb-icon{margin:0}
    .sb-nav-toggle:checked + .sb-layout .sb-pill.active::before{display:none}

    /* ========= RESPONSIVE ========= */
    @media (max-width: 1024px){
      .sb-layout{grid-template-columns:96px 1fr}
      .sb-brand strong,.sb-section,.sb-label,.sb-badge{display:none}
      .sb-submenu{padding-left:18px}
      .sb-pill{justify-content:center}
    }
  </style>
</head>
<body>

<input type="checkbox" id="sb-collapse" class="sb-nav-toggle">
<div class="sb-layout">
  <!-- SIDEBAR -->
  <aside class="sb-sidebar">
    <div class="sb-sidebar-content">
    <div class="sb-brand">
      <div class="sb-brand-left">
        <span class="sb-dot"></span>
        <strong>Toko Kelontong Bu Untung</strong>
      </div>
      <label class="sb-toggle" for="sb-collapse" title="Collapse / Expand">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#cfd3ff"><path d="M4 7h16v2H4V7Zm0 4h10v2H4v-2Zm0 4h16v2H4v-2Z"/></svg>
      </label>
    </div>

    @php
      $user = auth('staff')->user() ?? auth('pelanggan')->user();
      $staffRole = auth('staff')->user()?->role ?? null;
      $isDashboard = request()->routeIs('dashboard');
      $isTambah = request()->routeIs('ui.tambah');
      $isEdit = request()->routeIs('ui.edit');
      $isHapus = request()->routeIs('ui.hapus');
      $kelolaCrud = request()->routeIs('barang.*');
      $openKelola = $isTambah || $isEdit || $isHapus || $kelolaCrud;
      $isLaporanBarang = request()->routeIs('ui.laporan-barang', 'ui.laporan-barang.alias');
      $isLaporanPenjualan = request()->routeIs('ui.laporan-penjualan', 'ui.laporan-penjualan.alias');
      $isPengantaran = request()->routeIs('pengantaran.*');
      
      // Role-based menu visibility
      $canAccessLaporanPenjualan = $staffRole === 'owner';
      $canAccessPengantaran = $staffRole === 'karyawan'; // Hanya karyawan yang bisa akses pengantaran
    @endphp

    <div class="sb-section">Menu</div>
    <ul class="sb-menu">
      <li>
        <a href="{{ route('dashboard') }}" class="sb-pill {{ $isDashboard ? 'active' : '' }}">
          <span class="sb-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 3H3v7h7V3Zm11 0h-7v7h7V3ZM10 14H3v7h7v-7Zm11 0h-7v7h7v-7Z"/></svg>
          </span>
          <span class="sb-label">Dashboard</span>
        </a>
      </li>

      <li>
        <details class="sb-acc" {{ $openKelola ? 'open' : '' }}>
          <summary>
            <span class="sb-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20 6H4V4h16v2Zm0 5H4V9h16v2Zm0 5H4v-2h16v2Z"/></svg>
            </span>
            <span class="sb-label">Kelola Barang</span>
            <svg class="sb-caret" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
          </summary>
          <div class="sb-submenu">
            <a href="{{ route('ui.tambah') }}" class="{{ $isTambah ? 'active' : '' }}">
              <span class="sb-dotmini"></span> Tambah Barang
            </a>
            <a href="{{ route('ui.edit') }}" class="{{ $isEdit ? 'active' : '' }}">
              <span class="sb-dotmini"></span> Edit Barang
            </a>
            <a href="{{ route('ui.hapus') }}" class="{{ $isHapus ? 'active' : '' }}">
              <span class="sb-dotmini"></span> Hapus Barang
            </a>
          </div>
        </details>
      </li>

      <li>
        <a href="{{ route('ui.laporan-barang') }}" class="sb-pill {{ $isLaporanBarang ? 'active' : '' }}">
          <span class="sb-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z"/>
            </svg>
          </span>
          <span class="sb-label">Laporan Barang</span>
        </a>
      </li>

      @if($canAccessLaporanPenjualan)
      <li>
        <a href="{{ route('ui.laporan-penjualan') }}" class="sb-pill {{ $isLaporanPenjualan ? 'active' : '' }}">
          <span class="sb-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h2v18H3V3Zm16 0h2v18h-2V3ZM9 7h6v2H9V7Zm0 4h8v2H9v-2Zm0 4h5v2H9v-2Z"/></svg>
          </span>
          <span class="sb-label">Laporan Penjualan</span>
        </a>
      </li>
      @endif

      @if($canAccessPengantaran)
      <li>
        <a href="{{ route('pengantaran.index') }}" class="sb-pill {{ $isPengantaran ? 'active' : '' }}">
          <span class="sb-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
          </span>
          <span class="sb-label">Pengantaran</span>
        </a>
      </li>
      @endif
    </ul>
    </div>

    {{-- PROFILE DROPDOWN --}}
    @if($staffRole)
    <div class="sb-profile">
      <a href="#" class="sb-profile-trigger">
        <div class="sb-profile-avatar">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12Zm0 2.4c-3.3 0-9.9 1.7-9.9 5v1.9h19.8v-2c0-3.2-6.6-4.9-9.9-4.9Z"/>
          </svg>
        </div>
        <div class="sb-profile-info">
          <div class="sb-profile-name">{{ auth('staff')->user()->username ?? 'Staff' }}</div>
          <div class="sb-profile-role">{{ $staffRole }}</div>
        </div>
      </a>
      <div class="sb-profile-dropdown">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 17v-3H9v-4h7V7l5 5-5 5zM14 2a2 2 0 0 1 2 2v2h-2V4H5v16h9v-2h2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9z"/>
            </svg>
            <span>Logout</span>
          </a>
        </form>
      </div>
    </div>
    @endif
  </aside>

  <!-- MAIN CONTENT -->
  <main class="sb-content">
    {{-- Jika kamu pakai header dinamis --}}
    @php $isDashboard = $isDashboard ?? request()->is('dashboard'); @endphp
    @if($isDashboard && isset($header))
      <div class="sb-card mb-3 text-xl font-semibold">{{ $header }}</div>
    @endif

    {{-- Tempatkan konten halaman --}}
    {{ $slot ?? '' }}
    @yield('content')
  </main>
</div>
<script>
  (function(){
    function persistSidebarPreference(){
      try{
        var key='sb-collapse';
        var toggle=document.getElementById('sb-collapse');
        if(!toggle) return;
        var saved=window.localStorage.getItem(key);
        if(saved==='1'){toggle.checked=true;}
        toggle.addEventListener('change',function(){
          window.localStorage.setItem(key,this.checked?'1':'0');
        });
      }catch(err){
        console.warn('Sidebar toggle persistence failed',err);
      }
    }
    if(document.readyState==='loading'){
      document.addEventListener('DOMContentLoaded', persistSidebarPreference);
    }else{
      persistSidebarPreference();
    }
  })();
</script>
</body>
</html>
