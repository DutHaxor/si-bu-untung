<x-app-layout>
  <x-slot name="header"><span class="font-extrabold text-xl">Owner</span></x-slot>

  @php
    $period = ($period ?? 'harian'); // harian|mingguan|bulanan|tahunan
    $date   = ($date ?? now());
    $rows   = ($rows ?? []);
    $total  = collect($rows)->sum(fn($r) => (float)($r['subtotal'] ?? 0));
    $rupiah = fn($n) => 'Rp '.number_format((float)$n, 0, ',', '.');

    // label & nilai input dinamis
    $labelMap = [
      'harian'   => 'Pilih Tanggal (Harian):',
      'mingguan' => 'Pilih Minggu (Mingguan):',
      'bulanan'  => 'Pilih Bulan (Bulanan):',
      'tahunan'  => 'Pilih Tahun (Tahunan):',
    ];
    $labelText = $labelMap[$period] ?? $labelMap['harian'];

    $dateValue = match ($period) {
      'mingguan' => $date->format('o-\WW'),   // 2025-W46
      'bulanan'  => $date->format('Y-m'),     // 2025-11
      'tahunan'  => $date->format('Y'),       // 2025
      default    => $date->toDateString(),    // 2025-11-20
    };
  @endphp

  <style>
    /* Modern Black & White Theme */
    :root {
      --primary: #121212;
      --primary-hover: #2a2a2a;
      --bg-card: #ffffff;
      --border: #e5e7eb;
      --text-main: #111827;
      --text-muted: #6b7280;
      --focus-ring: rgba(18, 18, 18, 0.1);
    }

    /* Animasi Keyframes */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
      from { opacity: 0; transform: translateX(-30px); }
      to { opacity: 1; transform: translateX(0); }
    }

    .page-container{
      max-width:1100px;
      margin:0 auto;
      animation: fadeIn 0.6s ease-out;
    }

    .section-title{
      font:800 26px/1.2 'Poppins',system-ui;
      margin:0 0 18px;
      color: var(--text-main);
      animation: fadeInUp 0.5s ease-out 0.2s backwards;
    }

    .filters{
      display:flex;
      gap:18px;
      flex-wrap:wrap;
      align-items:flex-end;
      margin-bottom:24px;
      animation: fadeInUp 0.5s ease-out 0.3s backwards;
    }

    .field{
      display:flex;
      flex-direction:column;
      gap:8px;
    }

    .field label{
      font:600 13px 'Poppins',system-ui;
      color: var(--text-main);
      transition: color 0.2s ease;
    }

    .field:focus-within label {
      color: var(--primary);
    }

    .select,.input{
      height:42px;
      border:1px solid #e5e7eb;
      border-radius:12px;
      padding:0 14px;
      background:#fff;
      font:500 13px/42px 'Poppins',system-ui;
      color: var(--text-main);
      min-width:180px;
      transition: all 0.2s ease;
    }

    .select:hover,
    .input:hover {
      border-color: #cbd5e1;
    }

    .select:focus,
    .input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--focus-ring);
    }

    .btn-primary{
      height:42px;
      padding:0 24px;
      border:0;
      border-radius:12px;
      background: var(--primary);
      color:#fff;
      font:600 13px/42px 'Poppins',system-ui;
      cursor:pointer;
      transition: all 0.2s ease;
      white-space: nowrap;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary:hover{
      background: var(--primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .card{
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:18px;
      box-shadow:0 10px 30px rgba(0,0,0,.04);
      transition: all 0.3s ease;
      animation: fadeInUp 0.5s ease-out 0.4s backwards;
      overflow: hidden;
    }

    .card:hover {
      box-shadow: 0 20px 40px rgba(0,0,0,.08);
      transform: translateY(-2px);
    }

    .card-hd{
      padding:18px 24px;
      font:700 15px 'Poppins',system-ui;
      border-bottom:1px solid #e5e7eb;
      background: #fafafa;
      color: var(--text-main);
    }

    table{
      width:100%;
      border-collapse:collapse;
      font:500 13px 'Poppins',system-ui;
    }

    thead th{
      background:#fafafa;
      text-align:left;
      padding:14px 24px;
      border-bottom:1px solid #e5e7eb;
      font-weight:600;
      color: var(--text-muted);
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 0.5px;
    }

    tbody td{
      padding:14px 24px;
      border-top:1px solid #f3f4f6;
      color: var(--text-main);
      transition: all 0.2s ease;
    }

    tbody tr{
      transition: all 0.2s ease;
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

    tbody tr:hover {
      background: #f9fafb !important;
    }

    tfoot td{
      padding:16px 24px;
      border-top:2px solid #e5e7eb;
      font-weight:700;
      background: #fafafa;
      color: var(--text-main);
      font-size: 14px;
    }

    .placeholder{
      margin:24px;
      animation: fadeInUp 0.5s ease-out 0.5s backwards;
    }

    .placeholder input{
      width:100%;
      height:42px;
      border:1px dashed #e5e7eb;
      border-radius:12px;
      padding:0 16px;
      background:#f9fafb;
      font:500 13px/42px 'Poppins',system-ui;
      color: var(--text-muted);
      transition: all 0.3s ease;
    }

    .placeholder:hover input {
      border-color: #cbd5e1;
      background: #f3f4f6;
    }
  </style>

  <div class="page-container">
    <h2 class="section-title">Laporan Penjualan</h2>

    {{-- FILTER FORM --}}
    <form method="get" action="{{ url()->current() }}" class="filters" id="filter-form">
      <div class="field">
        <label>Periode Laporan:</label>
        <select name="period" class="select" id="period-select">
          <option value="harian"   {{ $period==='harian'?'selected':'' }}>Harian</option>
          <option value="mingguan" {{ $period==='mingguan'?'selected':'' }}>Mingguan</option>
          <option value="bulanan"  {{ $period==='bulanan'?'selected':'' }}>Bulanan</option>
          <option value="tahunan"  {{ $period==='tahunan'?'selected':'' }}>Tahunan</option>
        </select>
      </div>

      <div class="field" style="min-width:240px">
        <label id="date-label">{{ $labelText }}</label>

        @php
          $type = match ($period) {
            'mingguan' => 'week',
            'bulanan'  => 'month',
            'tahunan'  => 'number',
            default    => 'date',
          };
        @endphp

        @if($type === 'number')
          <input class="input" id="date-input" type="number" name="date"
                 min="2000" max="{{ now()->addYears(5)->format('Y') }}"
                 value="{{ $dateValue }}" placeholder="YYYY">
        @else
          <input class="input" id="date-input" type="{{ $type }}" name="date" value="{{ $dateValue }}">
        @endif
      </div>

      <button type="submit" class="btn-primary">Tampilkan</button>
    </form>

    {{-- HASIL --}}
    <div class="card">
      <div class="card-hd">Laporan Penjualan</div>

      @if(empty($rows))
        <div class="placeholder">
          <input type="text" disabled value="Silahkan pilih Periode untuk menampilkan laporan penjualan">
        </div>
      @else
        <div style="overflow-x:auto">
          <table>
            <thead>
              <tr>
                <th style="width:50px">No</th>
                <th style="width:130px">Tanggal</th>
                <th>Nama Barang</th>
                <th style="width:90px">Qty</th>
                <th style="width:140px">Harga Satuan</th>
                <th style="width:150px">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $i => $r)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ \Carbon\Carbon::parse($r['tanggal'] ?? $date)->format('d/m/Y') }}</td>
                  <td>{{ $r['nama_barang'] ?? '-' }}</td>
                  <td>{{ $r['qty'] ?? 0 }}</td>
                  <td>{{ isset($r['harga_satuan']) ? $rupiah($r['harga_satuan']) : '-' }}</td>
                  <td>{{ isset($r['subtotal']) ? $rupiah($r['subtotal']) : '-' }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="5" style="text-align:right">Total</td>
                <td>{{ $rupiah($total) }}</td>
              </tr>
            </tfoot>
          </table>
        </div>
      @endif
    </div>
  </div>

  {{-- JS kecil untuk mengubah label & tipe input tanpa reload penuh --}}
  <script>
    (function(){
      const periodSel = document.getElementById('period-select');
      const dateLabel = document.getElementById('date-label');
      const dateInput = document.getElementById('date-input');

      function isoWeekString(d) {
        // menghasilkan "YYYY-Www"
        const target = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
        const dayNr = (target.getUTCDay() + 6) % 7; // Senin=0
        target.setUTCDate(target.getUTCDate() - dayNr + 3);
        const firstThursday = new Date(Date.UTC(target.getUTCFullYear(),0,4));
        const week = 1 + Math.round(((target - firstThursday) / 86400000 - 3 + ((firstThursday.getUTCDay()+6)%7)) / 7);
        const year = target.getUTCFullYear();
        return year + '-W' + String(week).padStart(2,'0');
      }

      function apply() {
        const p = periodSel.value;
        if (p === 'bulanan') {
          dateLabel.textContent = 'Pilih Bulan (Bulanan):';
          dateInput.setAttribute('type', 'month');
          if (!/^\d{4}-\d{2}$/.test(dateInput.value)) {
            const d = new Date();
            const mm = String(d.getMonth()+1).padStart(2,'0');
            dateInput.value = d.getFullYear() + '-' + mm;
          }
        } else if (p === 'tahunan') {
          dateLabel.textContent = 'Pilih Tahun (Tahunan):';
          dateInput.setAttribute('type', 'number');
          dateInput.setAttribute('min', '2000');
          dateInput.setAttribute('max', String(new Date().getFullYear()+5));
          if (!/^\d{4}$/.test(dateInput.value)) {
            dateInput.value = String(new Date().getFullYear());
          }
        } else if (p === 'mingguan') {
          dateLabel.textContent = 'Pilih Minggu (Mingguan):';
          dateInput.setAttribute('type', 'week');
          if (!/^\d{4}-W\d{2}$/.test(dateInput.value)) {
            dateInput.value = isoWeekString(new Date());
          }
        } else { // harian
          dateLabel.textContent = 'Pilih Tanggal (Harian):';
          dateInput.setAttribute('type', 'date');
          if (!/^\d{4}-\d{2}-\d{2}$/.test(dateInput.value)) {
            const d = new Date();
            const mm = String(d.getMonth()+1).padStart(2,'0');
            const dd = String(d.getDate()).padStart(2,'0');
            dateInput.value = d.getFullYear() + '-' + mm + '-' + dd;
          }
        }
      }

      periodSel.addEventListener('change', apply);
      apply();
    })();
  </script>
</x-app-layout>
