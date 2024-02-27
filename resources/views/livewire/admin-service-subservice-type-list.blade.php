<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text">Tipe Layanan Foto Studio</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('admin.service.add-service') }}" class="btn btn-primary btn-sm" type="button">
                            <i class="fa fa-plus"></i> Add Service
                        </a>
                    </div>
                </div>
                <div class="table-responsive mt-4">

                    <table class="table table-borderless table-striped">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Service Type Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="sortable_services">
                            @forelse ($service_types as $item)
                                <tr data-index="{{ $item->id }}" data-ordering="{{ $item->ordering }}">
                                    <td>{{ $item->service_name }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('admin.service.edit-service', ['id' => $item->id]) }}"
                                                class="text-primary">
                                                <i class="dw dw-edit2"></i>
                                            </a>
                                            <a type="button" class="text-danger" data-toggle="modal" data-target="#exampleModal{{$item->id}}">
                                                <i class="dw dw-delete-3"></i>
                                              </a>
                                            <form method="POST" action="{{ route('admin.service.delete-service', ['id' => $item->id])}}" id="deleteForm" >
                                                @csrf
                                                @method('DELETE')
                                                  <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                        </div>
                                                        <div class="modal-body">
                                                          ...
                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                            </div>

                                                {{-- <button type="submit" class="text-danger deleteServiceBtn" onclick="confirmDelete(event)">
                                                    <i class="dw dw-delete-3"></i>
                                                </button> --}}
                                            </form>
                                            {{-- <a href="javascript:;" class="text-danger deleteServiceBtn">
                                            <i class="dw dw-delete-3"></i>
                                        </a> --}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <span class="text-danger">No services found!</span>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

            </div>
        </div>

        {{-- <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text">Sub-Service</h4>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-primary btn-sm" type="button">
                            <i class="fa fa-plus"></i> Add Sub-Service
                        </a>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-borderless table-striped">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Sub-Service Name</th>
                                <th>Service Type</th>
                                <th>Sum of Sub-Service</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>Wisuda</td>
                                <td>Graduation</td>
                                <td>2</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="" class="text-primary">
                                            <i class="dw dw-edit2"></i>
                                        </a>
                                        <a href="" class="text-danger">
                                            <i class="dw dw-delete-3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}
    </div>
</div>
