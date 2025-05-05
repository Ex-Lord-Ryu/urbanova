@extends('layouts.app')

@section('title', 'Hak Akses')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin/hakases/hakases.css') }}">
    <style>
        .table th,
        .table td {
            vertical-align: middle;
        }

        .table th:nth-child(1),
        .table td:nth-child(1) {
            /* ID column */
            width: 10%;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            /* Nama column */
            width: 20%;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            /* Email column */
            width: 30%;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            /* Role column */
            width: 12%;
            text-align: center;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            /* Action column */
            width: 30%;
            text-align: center;
        }

        .btn-sm {
            margin: 2px;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Hak Akses</h1>
        </div>
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="section-body">
            <!-- Admin Table -->
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Admin</h4>
                    <div class="card-header-form d-flex">
                        <a href="{{ route('hakakses.create') }}" class="btn btn-primary mr-2">
                            <i class="fas fa-plus"></i> Tambah Admin
                        </a>
                        <form>
                            <div class="input-group">
                                <input type="text" id="searchAdminInput" class="form-control"
                                    placeholder="Cari Admin...">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-wrapper">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-admin">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $adminCounter = 1; @endphp
                                    @foreach ($hakakses as $item)
                                        @if ($item->role == 'admin')
                                            <tr>
                                                <td>{{ $adminCounter++ }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-primary">{{ $item->role }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)"
                                                        onclick="confirmEdit('{{ route('hakakses.edit', $item->id) }}')"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('hakakses.delete', $item->id) }}"
                                                        method="POST" class="d-inline" onsubmit="confirmDelete(event)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Table -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Daftar User</h4>
                    <div class="card-header-form">
                        <div class="input-group">
                            <input type="text" id="searchUserInput" class="form-control" placeholder="Cari User...">
                            <div class="input-group-btn">
                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-wrapper">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-user">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $userCounter = 1; @endphp
                                    @foreach ($hakakses as $item)
                                        @if ($item->role == 'user')
                                            <tr>
                                                <td>{{ $userCounter++ }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-info">{{ $item->role }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)"
                                                        onclick="confirmEdit('{{ route('hakakses.edit', $item->id) }}')"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('hakakses.delete', $item->id) }}"
                                                        method="POST" class="d-inline" onsubmit="confirmDelete(event)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- JS Libraries -->

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            // Add search functionality for admin table
            $("#searchAdminInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#table-admin tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Add search functionality for user table
            $("#searchUserInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#table-user tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Confirm edit function
            window.confirmEdit = function(url) {
                window.location.href = url;
            };

            // Confirm delete function
            window.confirmDelete = function(event) {
                if (!confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                    event.preventDefault();
                }
            };
        });
    </script>
@endpush
