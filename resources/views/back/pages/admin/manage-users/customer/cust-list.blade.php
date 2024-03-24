@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Customer Users')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Customer Users</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Customer Users
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
                            <h4 class="h4 text">List User Customer</h4>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('admin.user.customer.create')}}" class="btn btn-primary btn-sm" type="button">
                                <i class="fa fa-plus"></i> Add Customer
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-borderless table-striped">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="sortable_services">
                                @forelse ($customer as $item )
                                <tr data-index="{{ $item->id}}" data-ordering="">
                                    <td>{{ $item->name}}</td>
                                    <td>{{ $item->email}}</td>
                                    <td>{{ $item->handphone}}</td>
                                    <td>
                                        <div class="table-actions">
                                            {{-- <a href="{{ route('admin.user.owner.edit',['id'=>$item->id])}}" class="text-primary"> --}}
                                            {{-- <a href="{{ route('products.edit',$product->id) }}" class="text-primary"> --}}

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
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
