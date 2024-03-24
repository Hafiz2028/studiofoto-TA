@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Booking List')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Booking List</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Booking List
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
                            <h4 class="h4 text">List Transaksi Booking Studio Foto</h4>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('owner.booking.create')}}" class="btn btn-primary btn-sm" type="button">
                                <i class="fa fa-plus"></i> Add New Offline Booking
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-borderless table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th>Service Type</th>
                                    <th>Package</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="sortable_services">
                                    <td>03-03-2024</td>
                                    <td>Studio Foto Unand</td>
                                    <td>Wisuda</td>
                                    <td>Paket 1 + Cetak Foto</td>
                                    <td>09.30-10.00</td>
                                    <td>
                                        <span class="badge badge-info ">Booking Diajukan</span>
                                        <span class="badge badge-danger ">Booking Ditolak</span>
                                        <span class="badge badge-primary ">Selesai</span>
                                        <span class="badge badge-success ">Berhasil Booking</span>
                                        <span class="badge badge-warning ">Kadaluarsa</span>
                                    </td>
                                    <td>

                                        <a href="" class="btn btn-info">
                                            <i class="bi bi-info-lg"></i> Detail
                                        </a>
                                        <a href="" class="btn btn-primary">
                                            <i class="dw dw-edit2"></i> Edit
                                        </a>
                                        <a type="button" class="btn btn-danger text-white" data-toggle="modal" data-target="">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>


                                        <a type="button" class="btn btn-success text-white" data-toggle="modal" data-target="">
                                            <i class="bi bi-check-lg"></i> Terima
                                        </a>
                                        <a type="button" class="btn btn-danger text-white" data-toggle="modal" data-target="">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </a>


                                    </td>
                                {{-- @forelse ($customer as $item )
                                <tr data-index="{{ $item->id}}" data-ordering="">
                                    <td>{{ $item->name}}</td>
                                    <td>{{ $item->email}}</td>
                                    <td>{{ $item->handphone}}</td>
                                    <td>
                                        <div class="table-actions">
                                                <a href="{{ route('admin.user.customer.edit',$item->id)}}" class="text-primary">
                                                <i class="dw dw-edit2"></i>
                                            </a>
                                            <a type="button" class="text-danger" data-toggle="modal" data-target="#exampleModal{{$item->id}}">
                                                <i class="dw dw-delete-3"></i>
                                              </a>
                                            <form method="POST" action="{{ route('admin.user.customer.destroy',$item->id)}}" id="deleteForm" >
                                                @csrf
                                                @method('DELETE')
                                                  <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                        </div>
                                                        <div class="modal-body">
                                                          Are you sure to delete this user?
                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                            </div>
                                            </form>

                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <span class="text-danger">No services found!</span>
                                        </td>
                                    </tr>
                                @endforelse --}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
