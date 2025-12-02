<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Akun Saya — Toko Kelontong Bu Untung</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }

        /* Form Card */
        .form-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .form-title {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
        }

        .form-label .required {
            color: #ff5722;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: #000000;
            background: #fff;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #ff5722;
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
        }

        .form-input::placeholder {
            color: #999;
        }

        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: #000000;
            background: #fff;
            resize: vertical;
            min-height: 100px;
            transition: all 0.3s ease;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #ff5722;
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
        }

        .form-help {
            font-size: 12px;
            color: #666666;
            margin-top: 4px;
        }

        .form-divider {
            height: 1px;
            background: #e5e5e5;
            margin: 32px 0;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 32px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary {
            background: #f5f5f5;
            color: #000000;
            border: 1px solid #e5e5e5;
        }

        .btn-secondary:hover {
            background: #e5e5e5;
        }

        .btn-primary {
            background: #ff5722;
            color: #fff;
            box-shadow: 0 2px 8px rgba(255, 87, 34, 0.2);
        }

        .btn-primary:hover {
            background: #e64a19;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .error-message {
            color: #ff5722;
            font-size: 12px;
            margin-top: 4px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .container {
                padding: 20px;
            }

            .form-card {
                padding: 24px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-title">Akun Saya</div>
        <a href="{{ route('customer.home') }}" class="back-link">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Container -->
    <div class="container">
        <div class="form-card">
            <h1 class="form-title">Edit Profil</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customer.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <!-- Nama Pelanggan -->
                <div class="form-group">
                    <label class="form-label" for="nama_pelanggan">
                        Nama Pelanggan <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="nama_pelanggan" 
                           name="nama_pelanggan" 
                           class="form-input" 
                           value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" 
                           required>
                    @error('nama_pelanggan')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label class="form-label" for="username">
                        Username <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-input" 
                           value="{{ old('username', $pelanggan->username) }}" 
                           required>
                    @error('username')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-input" 
                           value="{{ old('email', $pelanggan->email) }}">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- No. HP -->
                <div class="form-group">
                    <label class="form-label" for="no_hp">
                        No. Telepon
                    </label>
                    <input type="text" 
                           id="no_hp" 
                           name="no_hp" 
                           class="form-input" 
                           value="{{ old('no_hp', $pelanggan->no_hp) }}"
                           placeholder="08xxxxxxxxxx">
                    @error('no_hp')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="form-group">
                    <label class="form-label" for="alamat">
                        Alamat
                    </label>
                    <textarea id="alamat" 
                              name="alamat" 
                              class="form-textarea" 
                              placeholder="Masukkan alamat lengkap">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                    @error('alamat')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-divider"></div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">
                        Password Baru
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input" 
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    <div class="form-help">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-input" 
                           placeholder="Konfirmasi password baru">
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('customer.home') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

