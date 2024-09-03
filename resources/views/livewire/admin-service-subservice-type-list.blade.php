<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text">Service Type Foto Studio</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('admin.service.add-service') }}" class="btn btn-primary btn-sm" type="button">
                            <i class="fa fa-plus"></i> Add Service
                        </a>
                    </div>
                </div>
                <div class="pb-20 mt-30">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th class="table-plus">#</th>
                                <th>Nama Tipe Layanan</th>
                                <th class="datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($service_types as $item)
                                <tr>
                                    <td class="table-plus">{{ $loop->iteration }}</td>
                                    <td>{{ $item->service_name }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item text-primary"
                                                    href="{{ route('admin.service.edit-service', ['id' => $item->id]) }}"><i
                                                        class="dw dw-edit2"></i> Edit </a>
                                                <a type="button" class="dropdown-item text-danger"
                                                    onclick="confirmDelete({{ $item->id }})">
                                                    <i class="dw dw-delete-3"></i> Delete
                                                </a>
                                                <form id="delete-form-{{ $item->id }}"
                                                    action="{{ route('admin.service.delete-service', ['id' => $item->id]) }}"
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
                                        <span class="text-danger">Tidak Ada Akun Customer</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
