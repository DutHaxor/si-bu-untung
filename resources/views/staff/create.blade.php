<x-app-layout>
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

    .form-container {
      max-width: 500px;
      margin: 40px auto;
      animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .form-card {
      background: var(--bg-card);
      border-radius: 24px;
      border: 1px solid var(--border);
      padding: 40px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
    }

    .form-header {
      text-align: center;
      margin-bottom: 32px;
    }

    .form-title {
      font-size: 28px;
      font-weight: 800;
      color: var(--text-main);
      margin: 0 0 8px;
      letter-spacing: -0.5px;
    }

    .form-subtitle {
      color: var(--text-muted);
      font-size: 0.95rem;
    }

    .form-group {
      margin-bottom: 24px;
      position: relative;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      font-size: 0.9rem;
      color: var(--text-main);
      transition: color 0.2s;
    }

    .form-input-wrapper {
      position: relative;
    }

    .form-input {
      width: 100%;
      padding: 14px 16px;
      border-radius: 14px;
      border: 2px solid #f3f4f6;
      background: #f9fafb;
      font-size: 1rem;
      color: var(--text-main);
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-input:hover {
      background: #fff;
      border-color: #e5e7eb;
    }

    .form-input:focus {
      outline: none;
      background: #fff;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px var(--focus-ring);
    }

    .form-group:focus-within .form-label {
      color: var(--primary);
    }

    /* Select styling */
    select.form-input {
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg width='12' height='12' viewBox='0 0 12 12' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M2.5 4.5L6 8L9.5 4.5' stroke='%23111827' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 16px center;
      padding-right: 40px;
    }

    .btn-submit {
      width: 100%;
      padding: 16px;
      border-radius: 14px;
      background: var(--primary);
      color: #fff;
      font-weight: 600;
      font-size: 1rem;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 12px;
    }

    .btn-submit:hover {
      background: var(--primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .error-box {
      background: #fef2f2;
      border: 1px solid #fee2e2;
      color: #991b1b;
      padding: 16px;
      border-radius: 12px;
      margin-bottom: 24px;
      font-size: 0.9rem;
      animation: shake 0.4s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes shake {
      10%, 90% { transform: translate3d(-1px, 0, 0); }
      20%, 80% { transform: translate3d(2px, 0, 0); }
      30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
      40%, 60% { transform: translate3d(4px, 0, 0); }
    }
  </style>

  <div class="form-container">
    <div class="form-card">
      <div class="form-header">
        <h1 class="form-title">Tambah Staff</h1>
        <p class="form-subtitle">Buat akun baru untuk staff toko</p>
      </div>

      @if ($errors->any())
        <div class="error-box">
          <ul style="margin:0;padding-left:20px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('staff.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
          <label class="form-label" for="username">Username</label>
          <div class="form-input-wrapper">
            <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}" placeholder="Contoh: budisusanto" required autofocus>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <div class="form-input-wrapper">
            <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="nama@email.com" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="form-input-wrapper">
            <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="role">Role / Jabatan</label>
          <div class="form-input-wrapper">
            <select id="role" name="role" class="form-input" required>
              <option value="" disabled selected>Pilih Role</option>
              <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
              <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
              <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
            </select>
          </div>
        </div>

        <button type="submit" class="btn-submit">
          <span>Simpan Staff</span>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </button>
      </form>
    </div>
  </div>
</x-app-layout>
