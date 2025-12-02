<x-app-layout>
  <x-slot name="header"><span class="font-extrabold text-xl">Pengantaran</span></x-slot>

  <style>
    .h1-title{font-weight:800;font-size:32px;line-height:1.2;text-align:center;margin:0 0 18px}
    
    .filters-wrap{display:flex;gap:12px;justify-content:center;margin-bottom:18px;flex-wrap:wrap}
    .filter-btn{
      padding:8px 16px;border:1px solid #e5e7eb;background:#fff;border-radius:8px;
      font:500 13px 'Poppins',system-ui;color:#111;cursor:pointer;text-decoration:none
    }
    .filter-btn:hover{background:#f3f4f6}
    .filter-btn.active{background:#2563eb;color:#fff;border-color:#2563eb}

    .search-wrap{display:flex;justify-content:center;margin-bottom:22px}
    .search{position:relative;width:100%;max-width:560px}
    .search input{
      width:100%;height:40px;border:1px solid #e5e7eb;border-radius:999px;
      padding:0 14px 0 36px;background:#fff;font:500 14px/40px 'Poppins',system-ui
    }
    .search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af}

    .tbl-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.06)}
    .tbl-title{padding:16px 20px;font:600 14px 'Poppins',system-ui;border-bottom:1px solid #e5e7eb}
    table{width:100%;border-collapse:collapse;font:500 13px 'Poppins',system-ui}
    thead th{background:#f3f4f6;text-align:left;padding:12px 14px;border-bottom:1px solid #e5e7eb;font-weight:600}
    tbody td{padding:12px 14px;border-top:1px solid #f0f0f0}
    tbody tr:nth-child(odd){background:#fcfcfc}
    tbody tr:hover{background:#f9fafb}

    .badge{
      padding:4px 10px;border-radius:6px;font:600 11px 'Poppins',system-ui;text-transform:uppercase
    }
    .badge-success{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0}
    .badge-warning{background:#fef3c7;color:#92400e;border:1px solid #fde68a}
    .badge-info{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
    .badge-primary{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}

    .btn-action{
      padding:6px 12px;border-radius:6px;border:0;font:600 11px 'Poppins',system-ui;
      cursor:pointer;text-decoration:none;display:inline-block;margin:2px
    }
    .btn-success{background:#10b981;color:#fff}
    .btn-success:hover{background:#059669}
    .btn-primary{background:#3b82f6;color:#fff}
    .btn-primary:hover{background:#2563eb}

    .pagination{display:flex;justify-content:center;gap:6px;padding:12px 16px}
    .page-btn{
      min-width:28px;height:28px;padding:0 8px;border:1px solid #e5e7eb;background:#fff;
      border-radius:6px;font:600 12px/28px 'Poppins',system-ui;text-align:center;color:#111;text-decoration:none
    }
    .page-btn:hover{background:#f3f4f6}
    .page-btn.is-active{background:#2563eb;color:#fff;border-color:#2563eb}
    .page-btn.icon{font-weight:600}

    .alert{margin:10px 0 18px;padding:12px 16px;border-radius:10px;font:600 13px 'Poppins',system-ui}
    .alert-success{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0}
    .alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}

    .page-container{max-width:1400px;margin:0 auto}

    .modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
    .modal.active{display:flex}
    .modal-content{background:#fff;border-radius:12px;padding:24px;max-width:500px;width:90%;max-height:90vh;overflow-y:auto}
    .modal-header{font:700 18px 'Poppins',system-ui;margin-bottom:12px}
    .modal-body{margin-bottom:20px}
    .modal-footer{display:flex;gap:8px;justify-content:flex-end}

    .form-group{margin-bottom:16px}
    .form-label{display:block;font:600 13px 'Poppins',system-ui;margin-bottom:6px;color:#111}
    .form-input{width:100%;padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font:500 13px 'Poppins',system-ui}
    .form-input:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
  </style>

  <div class="page-container">
    <h1 class="h1-title">Daftar Pesanan Pengantaran</h1>

    {{-- Flash messages --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- Filters --}}
    <div class="filters-wrap">
      <a href="{{ route('pengantaran.index') }}" 
         class="filter-btn {{ !request('status') ? 'active' : '' }}">
        Semua
      </a>
      <a href="{{ route('pengantaran.index', ['status' => 'dibayar']) }}" 
         class="filter-btn {{ request('status') == 'dibayar' ? 'active' : '' }}">
        Dibayar
      </a>
      <a href="{{ route('pengantaran.index', ['status' => 'dalam_pengiriman']) }}" 
         class="filter-btn {{ request('status') == 'dalam_pengiriman' ? 'active' : '' }}">
        Dalam Pengiriman
      </a>
    </div>

    {{-- Search --}}
    <div class="search-wrap">
      <form class="search" method="get" action="{{ route('pengantaran.index') }}">
        @if(request('status'))
          <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
          <path d="M15.5 14h-.79l-.28-.27A6.5 6.5 0 1 0 14 15.5l.27.28h.79l5 5 1.5-1.5-5-5ZM10 15.5A5.5 5.5 0 1 1 10 4.5a5.5 5.5 0 0 1 0 11Z"/>
        </svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ID Pesanan, Nama Penerima, Alamat, atau Pelanggan...">
      </form>
    </div>

    {{-- Table --}}
    <div class="tbl-card">
      <div class="tbl-title">Daftar Pesanan</div>
      <div style="overflow-x:auto">
        <table>
          <thead>
            <tr>
              <th style="width:140px">ID Pesanan</th>
              <th style="width:100px">Tanggal</th>
              <th style="width:120px">Pelanggan</th>
              <th style="width:120px">Nama Penerima</th>
              <th>Alamat</th>
              <th style="width:120px">Status</th>
              <th style="width:130px">Staff Pengantar</th>
              <th style="width:120px;text-align:right">Total</th>
              <th style="width:180px;text-align:center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transaksis as $t)
              <tr>
                <td>{{ $t->id_transaksi }}</td>
                <td>{{ $t->tanggal_transaksi->format('d/m/Y') }}</td>
                <td>{{ $t->pelanggan->nama_pelanggan ?? '-' }}</td>
                <td>{{ $t->nama_penerima }}</td>
                <td>
                  <span title="{{ $t->alamat_pengiriman }}">
                    {{ Str::limit($t->alamat_pengiriman, 40) }}
                  </span>
                </td>
                <td>
                  @php
                    $badgeClass = match($t->status_transaksi) {
                      'dibayar' => 'badge-success',
                      'dalam_pengiriman' => 'badge-info',
                      'terkirim' => 'badge-primary',
                      default => 'badge-warning'
                    };
                    $statusLabel = match($t->status_transaksi) {
                      'pending' => 'Pending',
                      'dibayar' => 'Dibayar',
                      'dalam_pengiriman' => 'Dalam Pengiriman',
                      'terkirim' => 'Terkirim',
                      default => $t->status_transaksi
                    };
                  @endphp
                  <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
                <td>{{ $t->staff->username ?? 'Belum ditugaskan' }}</td>
                <td style="text-align:right">Rp {{ number_format($t->total_transaksi, 0, ',', '.') }}</td>
                <td style="text-align:center">
                  <div style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap">
                    <button type="button" class="btn-action" style="background:#6b7280;color:#fff" 
                            onclick="openBarangModal('{{ $t->id_transaksi }}')">
                      Lihat Barang
                    </button>
                    @if($t->status_transaksi === 'dibayar')
                      <form method="POST" action="{{ route('pengantaran.ambil', $t->id_transaksi) }}" 
                            onsubmit="return confirm('Ambil pesanan {{ $t->id_transaksi }} untuk dikirim?')" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-action btn-success">Ambil Pesanan</button>
                      </form>
                    @elseif($t->status_transaksi === 'dalam_pengiriman' && $t->id_staff === auth('staff')->user()?->id_staff)
                      <button type="button" class="btn-action btn-primary" 
                              onclick="openUploadModal('{{ $t->id_transaksi }}')">
                        Upload Bukti
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" style="text-align:center;color:#6b7280;padding:24px">
                  Tidak ada data pesanan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($transaksis->hasPages())
        @php
          $current = $transaksis->currentPage();
          $last = $transaksis->lastPage();
        @endphp
        <div class="pagination">
          <a class="page-btn icon" href="{{ $transaksis->previousPageUrl() ?? '#' }}">«</a>
          @for($i = max(1, $current-2); $i <= min($last, $current+2); $i++)
            <a class="page-btn {{ $i==$current ? 'is-active' : '' }}"
               href="{{ $transaksis->url($i) }}{{ request()->has('status') ? '&status='.request('status') : '' }}{{ request('q') ? '&q='.request('q') : '' }}">
              {{ $i }}
            </a>
          @endfor
          <a class="page-btn icon" href="{{ $transaksis->nextPageUrl() ?? '#' }}">»</a>
        </div>
      @endif
    </div>
  </div>

  {{-- Modal Lihat Barang --}}
  <div id="barangModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">Daftar Barang - <span id="barangModalIdTransaksi"></span></div>
      <div class="modal-body">
        <div id="barangModalList" style="max-height:400px;overflow-y:auto">
          <!-- Data akan diisi oleh JavaScript -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-action" style="background:#6b7280" onclick="closeBarangModal()">Tutup</button>
      </div>
    </div>
  </div>

  {{-- Modal Upload Bukti --}}
  <div id="uploadModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">Upload Bukti Pengiriman</div>
      <form id="uploadForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label" for="bukti_pengiriman">Foto Bukti Pengiriman</label>
            <input type="file" 
                   id="bukti_pengiriman" 
                   name="bukti_pengiriman" 
                   class="form-input" 
                   accept="image/*" 
                   required>
            <small style="color:#6b7280;font-size:11px">Format: JPG, PNG. Maksimal 5MB</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-action" style="background:#6b7280" onclick="closeUploadModal()">Batal</button>
          <button type="submit" class="btn-action btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Data transaksi untuk modal
    const transaksiData = {
      @foreach($transaksis as $t)
        '{{ $t->id_transaksi }}': {
          id: '{{ $t->id_transaksi }}',
          barang: [
            @foreach($t->detailTransaksis as $detail)
            {
              id_barang: '{{ $detail->id_barang }}',
              nama: '{{ addslashes($detail->barang->nama_barang ?? '-') }}',
              qty: {{ $detail->jumlah_pesanan }}
            }@if(!$loop->last),@endif
            @endforeach
          ]
        }@if(!$loop->last),@endif
      @endforeach
    };

    function openBarangModal(idTransaksi) {
      const data = transaksiData[idTransaksi];
      if (!data) {
        alert('Data transaksi tidak ditemukan');
        return;
      }

      document.getElementById('barangModalIdTransaksi').textContent = data.id;
      
      const listContainer = document.getElementById('barangModalList');
      
      if (data.barang.length === 0) {
        listContainer.innerHTML = '<p style="text-align:center;color:#6b7280;padding:20px">Tidak ada barang</p>';
      } else {
        let html = '<table style="width:100%;border-collapse:collapse">';
        html += '<thead><tr style="background:#f3f4f6;border-bottom:2px solid #e5e7eb">';
        html += '<th style="padding:10px;text-align:left;font-weight:600;font-size:12px">ID Barang</th>';
        html += '<th style="padding:10px;text-align:left;font-weight:600;font-size:12px">Nama Barang</th>';
        html += '<th style="padding:10px;text-align:center;font-weight:600;font-size:12px">Jumlah</th>';
        html += '</tr></thead><tbody>';
        
        data.barang.forEach(item => {
          html += '<tr style="border-bottom:1px solid #f0f0f0">';
          html += `<td style="padding:10px;font-weight:600;font-size:13px">${item.id_barang}</td>`;
          html += `<td style="padding:10px;font-size:13px">${item.nama}</td>`;
          html += `<td style="padding:10px;text-align:center;font-size:13px">${item.qty}</td>`;
          html += '</tr>';
        });
        
        html += '</tbody></table>';
        listContainer.innerHTML = html;
      }
      
      document.getElementById('barangModal').classList.add('active');
    }

    function closeBarangModal() {
      document.getElementById('barangModal').classList.remove('active');
    }

    function openUploadModal(idTransaksi) {
      const form = document.getElementById('uploadForm');
      form.action = `{{ url('/pengantaran') }}/${idTransaksi}/upload-bukti`;
      document.getElementById('uploadModal').classList.add('active');
    }

    function closeUploadModal() {
      document.getElementById('uploadModal').classList.remove('active');
      document.getElementById('uploadForm').reset();
    }

    // Close modal on background click
    document.getElementById('barangModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeBarangModal();
      }
    });

    document.getElementById('uploadModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeUploadModal();
      }
    });
  </script>
</x-app-layout>

