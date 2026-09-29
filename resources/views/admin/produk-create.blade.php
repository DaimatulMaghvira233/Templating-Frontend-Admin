<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Spark Admin - Tambah Produk</title>
  <link rel="icon" type="image/png" href="{{ asset('admin-assets/assets/images/favicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/css/main.css') }}">
</head>

<body>
  <div class="sidebar-wrapper" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
      <i class="bi bi-asterisk"></i>
      <span>Spark Admin</span>
    </a>
    <div class="flex-grow-1 overflow-y-auto">
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Menu</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link" title="Overview">
              <i class="bi bi-grid-fill"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.produk') }}" class="sidebar-menu-link active" title="Produk">
              <i class="bi bi-box-seam"></i>
              <span>Produk</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-wrapper">
    <header class="navbar-custom">
      <div class="navbar-left">
        <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
          <i class="bi bi-list"></i>
        </button>
      </div>
    </header>

    <div class="page-header">
      <div>
        <h1 class="page-title">Tambah Produk</h1>
        <p class="page-subtitle">Isi form untuk menambah produk furniture baru.</p>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-8">
        <div class="card">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('admin.produk.store') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label class="form-label">Nama Produk</label>
              <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Harga</label>
              <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Stok</label>
              <input type="number" name="stok" class="form-control" value="{{ old('stok') }}" required>
            </div>

            <button type="submit" class="btn btn-dark">Simpan</button>
            <a href="{{ route('admin.produk') }}" class="btn btn-outline-secondary">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('admin-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>