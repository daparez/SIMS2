@extends('layouts.app')

@section('title', 'Kelola Hak Akses User - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Admin /</span> Pengaturan Hak Akses User</h4>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center row gy-2">
        <div class="col-md-6">
            <h5 class="mb-0 fw-bold">Daftar Pengguna (Guru & Siswa)</h5>
            <small class="text-muted">Berikan akses fitur khusus seperti Scan QR Absensi kepada user pilihan.</small>
        </div>
        <div class="col-md-6">
            <form action="{{ route('admin.hak-akses.index') }}" method="GET">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama / Username..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit"><i class="bx bx-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Role</th>
                    <th>Hak Akses Tambahan Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $u)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td>
                        <strong class="text-dark">{{ $u->name }}</strong>
                        <br><small class="text-muted">{{ $u->username }}</small>
                    </td>
                    <td>
                        @if($u->role == 'guru')
                            <span class="badge bg-label-info">Guru</span>
                        @else
                            <span class="badge bg-label-success">Siswa</span>
                        @endif
                    </td>
                    <td>
                        @forelse($u->permissions as $p)
                            <span class="badge bg-primary mb-1">{{ $availablePermissions[$p->permission] ?? $p->permission }}</span>
                        @empty
                            <span class="text-muted small">Standard (Tidak Ada Akses Tambahan)</span>
                        @endforelse
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAkses{{ $u->id }}">
                            <i class="bx bx-key me-1"></i> Atur Hak Akses
                        </button>
                    </td>
                </tr>

                <!-- MODAL POPUP SETTING HAK AKSES -->
                <div class="modal fade" id="modalAkses{{ $u->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-bottom">
                                <h5 class="modal-title fw-bold"><i class="bx bx-shield-quarter text-primary me-2"></i>Hak Akses: {{ $u->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.hak-akses.update', $u->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <p class="text-muted small mb-3">Centang fitur di bawah ini untuk mengizinkan menu tersebut muncul di sidebar & dapat diakses oleh {{ $u->name }}:</p>

                                    @foreach($availablePermissions as $key => $label)
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $key }}" id="perm_{{ $u->id }}_{{ $key }}" {{ $u->hasPermission($key) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="perm_{{ $u->id }}_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="modal-footer border-top">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Akses</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Data pengguna tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection