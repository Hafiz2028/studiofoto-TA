@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Add Event Service')
@section('content')

    <div class="page-header">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-dark">Add Event Service</h4>
            </div>
        </div>
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.venue.index') }}">Venue's Manage</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.venue.show', $venue->id) }}">Detail Venue</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Add Event Service
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-primary">Add Event Service</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('owner.venue.show', $venue->id) }}" class="btn btn-outline-info btn-sm">
                            <i class="ion-arrow-left-a"></i> Kembali
                        </a>
                    </div>
                </div>
                <hr>

                <form action="{{ route('owner.venue.services.store', $venue->id) }}" method="POST"
                    enctype="multipart/form-data" class="mt-3">
                    @csrf
                    @if (Session::get('success'))
                        <div class="alert alert-success">
                            <strong><i class="dw dw-checked"></i></strong>
                            {!! Session::get('success') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (Session::get('fail'))
                        <div class="alert alert-danger">
                            <strong><i class="dw dw-checked"></i></strong>
                            {!! Session::get('fail') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Nama Layanan</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" placeholder="contoh : Foto Keluarga, Foto Wisuda"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <span class="text-danger ml-2">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="service_type_id">Jenis Layanan</label>
                                <select class="form-control @error('service_type_id') is-invalid @enderror" id="service_type_id" name="service_type_id">
                                    <option value="">Pilih Jenis Layanan</option>
                                    @foreach ($serviceTypes as $serviceType)
                                        <option value="{{ $serviceType->id }}">{{ $serviceType->service_name }}</option>
                                    @endforeach
                                </select>
                                @error('service_type_id')
                                    <span class="text-danger ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="catalog">Katalog Layanan</label>
                                <input type="file" class="form-control @error('catalog') is-invalid @enderror" id="catalog" name="catalog"
                                    placeholder="Input Foto Katalog Layanan Jika ada" accept="image/*">
                                @error('catalog')
                                    <span class="text-danger ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="images">Foto Layanan</label>
                                <input type="file" class="form-control-file @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple>
                                @error('images.*')
                                    <span class="text-danger ml-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div id="additionalImages"></div>
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary" id="addImageInput">Tambah Foto</button>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary float-right">Tambah Layanan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@stack('scripts')
<script>
    document.getElementById('addImageInput').addEventListener('click', function() {
        var additionalImagesContainer = document.getElementById('additionalImages');
        var input = document.createElement('input');
        input.type = 'file';
        input.className = 'form-control-file mt-2';
        input.name = 'images[]';
        input.multiple = true;
        additionalImagesContainer.appendChild(input);

        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-danger mt-2 ml-2';
        removeButton.textContent = 'Hapus Foto';
        removeButton.addEventListener('click', function() {
            additionalImagesContainer.removeChild(input);
            additionalImagesContainer.removeChild(removeButton);
        });
        additionalImagesContainer.appendChild(removeButton);
    });
</script>
