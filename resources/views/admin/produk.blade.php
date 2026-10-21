<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Spark Admin - Produk</title>
  <link rel="icon" type="image/png" href="{{ asset('admin-assets/assets/images/favicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/libs/apexcharts/apexcharts.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/assets/libs/flatpickr/flatpickr.min.css') }}">
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
            <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link" id="menu-overview" title="Overview">
              <i class="bi bi-grid-fill"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.produk') }}" class="sidebar-menu-link active" id="menu-produk" title="Produk">
              <i class="bi bi-box-seam"></i>
              <span>Produk</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="sidebar-profile">
      <img src="{{ asset('admin-assets/assets/images/avatar.png') }}" alt="Administrator" class="sidebar-profile-img">
      <div class="sidebar-profile-info">
        <div class="sidebar-profile-name">Daimatul Maghvira</div>
        <div class="sidebar-profile-email">daimatulmaghvira23@gmail.com</div>
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
      <div class="navbar-actions">
        <div class="dropdown ms-2">
          <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="profile-dropdown">
            <img src="{{ asset('admin-assets/assets/images/avatar.png') }}" alt="Profile Image" class="navbar-profile-img">
            <span class="navbar-profile-name d-none d-md-inline">Daimatul Maghvira</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile-dropdown">
            <li><a class="dropdown-item text-danger" href="{{ route('login') }}"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          </ul>
        </div>
      </div>
    </header>

    <div class="page-header">
      <div>
        <h1 class="page-title">Produk</h1>
        <p class="page-subtitle">Daftar produk furniture dari database.</p>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Daftar Produk Furniture</h2>
            <a href="{{ route('admin.produk.create') }}" class="btn btn-dark">
              <i class="bi bi-plus-lg"></i> Tambah Produk
            </a>
          </div>

          @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($produk as $index => $item)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>
                    @if ($item->image)
                      <img src="{{ asset('storage/' . $item->image) }}" width="80" class="rounded" alt="{{ $item->nama_produk }}">
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>{{ $item->nama_produk }}</td>
                  <td>{{ $item->deskripsi }}</td>
                  <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                  <td>{{ $item->stok }}</td>
                  <td>
                    <a href="{{ route('admin.produk.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.produk.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus produk ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7">Belum ada data produk.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <script src="{{ asset('admin-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>