@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Admin Users')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Admin Users</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Admin Users
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="h4 text">List User Admin</h4>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('admin.user.add-admin') }}" class="btn btn-primary btn-sm" type="button">
                                <i class="fa fa-plus"></i> Add Admin
                            </a>
                        </div>
                    </div>
                    <div class="pb-20 mt-30">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus">Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($admins as $item)
                                    <tr>
                                        <td class="table-plus">{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->handphone }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    href="#" role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item text-primary"
                                                        href="{{ route('admin.user.edit-admin', ['id' => $item->id]) }}"><i
                                                            class="dw dw-edit2"></i> Edit </a>
                                                    <a type="button" class="dropdown-item text-danger"
                                                        onclick="confirmDelete({{ $item->id }})">
                                                        <i class="dw dw-delete-3"></i> Delete
                                                    </a>
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('admin.user.delete-admin', ['id' => $item->id]) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <span class="text-danger">Tidak Ada Akun Admin</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah yakin untuk menghapus?',
                text: "Akun tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
