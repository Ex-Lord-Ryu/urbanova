@extends('layouts.app')

@section('title', 'Pengaturan')

@push('css')
    <style>
        .main-content {
            margin-left: 150px;
            width: calc(100% - 150px);
            transition: all 0.3s ease;
        }

        .sidebar-mini .main-content {
            margin-left: 20px;
            width: calc(100% - 20px);
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Pengaturan</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pengaturan Tampilan</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label class="form-label">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="show_featured_badge" id="show_featured_badge"
                                                class="custom-control-input" onchange="this.form.submit()" value="1"
                                                {{ \App\Models\Setting::get('show_featured_badge', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="show_featured_badge">
                                                Tampilkan Badge "Unggulan" pada Produk di Halaman Shop
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Aktifkan opsi ini untuk menampilkan badge "Unggulan" pada produk unggulan di
                                            halaman shop.
                                        </small>
                                    </label>
                                </div>

                                <div class="form-group d-none">
                                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Toggle settings via AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSwitch = document.getElementById('show_featured_badge');
            if (toggleSwitch) {
                toggleSwitch.addEventListener('change', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const formData = new FormData(form);

                    fetch('{{ route('settings.toggle', 'show_featured_badge') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                value: this.checked ? 1 : 0
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                iziToast.success({
                                    title: 'Berhasil!',
                                    message: 'Pengaturan berhasil diperbarui',
                                    position: 'topRight'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            iziToast.error({
                                title: 'Error!',
                                message: 'Terjadi kesalahan saat memperbarui pengaturan',
                                position: 'topRight'
                            });
                            // Revert the switch state
                            this.checked = !this.checked;
                        });
                });
            }
        });
    </script>
@endpush
