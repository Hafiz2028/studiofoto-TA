@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Venue Services')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Venue's Service</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.home')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Venue's Service
                    </li>
                </ol>
            </nav>
            <br>
            <p>List jenis layanan pada Foto Studio</p>
        </div>
    </div>
</div>
@livewire('admin-service-subservice-type-list')

@endsection
@push('scripts')
<script>
    $(document).on('click','deleteServiceBtn',function(e){
        e.preventDefault();
        var service_type_id = $(this).data('id');
        Swal.fire({
            title:"Are you sure?",
            html:"You want to delete this service",
            showCloseButton:true,
            showCancelButton:true,
            cancelButtonText:'cancel',
            confirmButtonText:'Yes, Delete',
            cancelButtonColor: '#d33',
            confirmButtonColor:'#3085d6',
            width:300,
            allowOutsideClick:false
        }).then(function(result){
            if(result.value){
                Livewire.dispatch('deleteService',[service_type_id]) alert('Yes, delete Service');
            }
        });
    });
</script>


    {{-- // $('table tbody#sortable_services').sortable({
    //     cursor:"move",
    //     update:function(event, ui){
    //         $(this).children().each(function(index){
    //             if( $(this).attr("data-ordering")!=(index+1)){
    //                 $(this).attr("data-ordering",(index+1)).addClass("updated");
    //             }
    //         });
    //         var positions = [];
    //         $(".updated").each(function(){
    //             positions.push([$(this).attr("data-index"),$(this).attr("data-ordering")]);
    //             $(this).removeClass('updated');
    //         });
    //         window.livewire.emit('updateServicesOrdering',positions);
    //     }
    // }); --}}


@endpush
