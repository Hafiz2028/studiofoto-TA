@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Tambah Event Layanan')
@section('content')

    <div class="page-header">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-dark">Tambah Event Layanan</h4>
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
                    Tambah Event Layanan
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-primary">Tambah Event Layanan</h4>
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
                    <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                    @csrf
                    <x-alert.form-alert />
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name"><strong>1. Nama Layanan</strong></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Contoh: Foto Keluarga, Foto Wisuda"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="service_type_id"><strong>2. Tipe Layanan</strong></label>
                                    <select class="form-control @error('service_type_id') is-invalid @enderror"
                                        id="service_type_id" name="service_type_id">
                                        <option value="">Pilih Tipe Layanan</option>
                                        @foreach ($serviceTypes as $serviceType)
                                            <option value="{{ $serviceType->id }}">{{ $serviceType->service_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('service_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-12">
                                <div class="form-group">
                                    <label for="description"><strong>3. Deskripsi Layanan</strong></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="4" placeholder="Isi deskripsi untuk venue"></textarea>
                                    @error('information')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><strong>4. Katalog Harga Layanan (optional)</strong></label>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('catalog') is-invalid @enderror"
                                            id="catalogInput" name="catalog" accept="image/*">
                                        <label class="custom-file-label" for="catalogInput">Input Paket Foto dari Layanan
                                            ini... (Optional)</label>
                                        <div class="invalid-feedback" id="catalogError">
                                            @error('catalog')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><strong>5. Foto Layanan</strong></label>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('images.*') is-invalid @enderror"
                                            id="imagesInput" name="images[]" multiple accept="image/*">
                                        <label class="custom-file-label" for="imagesInput">Input Hasil Foto dari
                                            Layanan...</label>
                                        <div class="invalid-feedback" id="imagesError">
                                            @error('images.*')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-outline-secondary float-right mb-5"
                                            id="addImageInput">Tambah Foto</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div id="additionalImages" class="row">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                            <div id="imagePreviewContainer" class="overflow-auto" style="display: inline-block;">
                                <label>Preview Katalog Harga & Hasil Foto Layanan : </label><br>
                                <div id="catalogPreview" class="image-preview mr-3" style="display: inline-block;">
                                </div>
                                <div id="imagesPreview" class="image-preview mr-3" style="display: inline-block;"></div>
                                <br><label>Preview Hasil Foto Tambahan : </label><br>
                                <div id="additionalPreviews" class="image-preview mr-3"
                                    style="display: inline-block; white-space: nowrap;"></div>
                            </div>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mt-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary float-right">Tambah Layanan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('styles')
    <style>
        #imagePreviewContainer {
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
        }

        .image-preview {
            margin-right: 10px;
        }
    </style>
    <style>
        #additionalPreviews {
            overflow-x: auto;
            /* Tambahkan overflow-x agar bisa di-scroll secara horizontal jika melebihi lebar */
        }

        .additional-preview-container {
            display: inline-block;
            /* Atur display ke inline-block agar elemen-elemen di dalamnya ditampilkan secara horizontal */
            vertical-align: top;
            /* Atur vertical-align agar elemen-elemen di dalamnya sejajar di bagian atas */
            white-space: nowrap;
            /* Agar elemen-elemen di dalamnya tidak pindah ke baris baru */
        }
    </style>
@endpush
@push('scripts')
    {{-- validasi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputFile = document.getElementById('imagesInput');

            function validateImages(input) {
                const files = input.files;
                const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                const maxSize = 5000;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const fileSize = file.size / 1024;

                    if (!allowedExtensions.test(file.name)) {
                        alert('File harus berupa gambar (format JPG, JPEG, PNG, GIF).');
                        input.value = '';
                        return false;
                    }

                    if (fileSize > maxSize) {
                        alert('Ukuran file tidak boleh melebihi 5000 KB.');
                        input.value = '';
                        return false;
                    }
                }

                return true;
            }

            inputFile.addEventListener('change', function() {
                validateImages(inputFile);
            });
        });
    </script>
    {{-- ganti label input file --}}
    <script>
        function updateLabel(input) {
            var fileName = input.files[0].name;
            var label = input.nextElementSibling;
            label.innerHTML = fileName;
        }

        document.addEventListener("DOMContentLoaded", function() {
            var customFileInputs = document.querySelectorAll('.custom-file-input');
            customFileInputs.forEach(function(input) {
                input.addEventListener('change', function() {
                    updateLabel(this);
                });
            });
        });
    </script>
    {{-- tambah, hapus foto --}}
    <script>
        function addImagePreview(fileInput, previewContainer, labelText) {
            const files = fileInput.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.match('image.*')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.className = 'mr-2';
                        img.style.maxWidth = '100px';

                        const imageName = document.createElement("p");
                        imageName.textContent = file.name;

                        const deleteButton = document.createElement('button');
                        deleteButton.type = 'button';
                        deleteButton.className = 'btn btn-danger btn-sm mt-2';
                        deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                        deleteButton.addEventListener('click', function() {
                            previewDiv.remove();
                            removeFile(fileInput, file);
                            resetLabel(fileInput, labelText);
                        });

                        const previewDiv = document.createElement("div");
                        previewDiv.className = "image-preview mr-3";
                        previewDiv.appendChild(img);
                        previewDiv.appendChild(imageName);
                        previewDiv.appendChild(deleteButton);

                        previewContainer.appendChild(previewDiv);
                    }

                    reader.readAsDataURL(file);
                }
            }
            if (files.length === 0) {
                resetLabel(fileInput, labelText);
            }
        }

        function removeFile(input, file) {
            const newFiles = [];
            for (let i = 0; i < input.files.length; i++) {
                if (input.files[i] !== file) {
                    newFiles.push(input.files[i]);
                }
            }
            input.value = ''; // Clear input value to trigger change event
            input.files = newFiles;
        }

        function resetLabel(input, labelText) {
            const label = input.parentNode.querySelector('.custom-file-label');
            if (label) {
                label.textContent = labelText;
            }
        }

        function addPreviewForInput(inputId, previewContainerId, labelText) {
            const input = document.getElementById(inputId);
            const previewContainer = document.getElementById(previewContainerId);

            input.addEventListener('change', function() {
                previewContainer.innerHTML = '';
                addImagePreview(input, previewContainer, labelText);
            });
        }

        addPreviewForInput('catalogInput', 'catalogPreview', 'Input Paket Foto dari Layanan');
        addPreviewForInput('imagesInput', 'imagesPreview', 'Input Hasil Foto dari Layanan');

        function addImagePreviewForNewInput(input, previewContainer) {
            input.addEventListener('change', function() {
                const files = input.files;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const img = document.createElement("img");
                            img.src = e.target.result;
                            img.className = 'mr-2';
                            img.style.maxWidth = '100px';
                            img.style.display = 'inline-block';

                            const imageName = document.createElement("p");
                            imageName.textContent = file.name;

                            const deleteButton = document.createElement('button');
                            deleteButton.type = 'button';
                            deleteButton.className = 'btn btn-danger btn-sm mt-2';
                            deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                            deleteButton.addEventListener('click', function() {
                                previewDiv.remove();
                                input.parentNode.parentNode.remove();
                                removeFile(input, file);
                                resetLabel(input);
                            });


                            const previewDiv = document.createElement("div");
                            previewDiv.className = "image-preview mr-3";
                            previewDiv.style.display = 'inline-block';
                            previewDiv.appendChild(img);
                            previewDiv.appendChild(imageName);
                            previewDiv.appendChild(deleteButton);

                            const wrapperDiv = document.createElement('div');
                            wrapperDiv.className = 'additional-preview-container';
                            wrapperDiv.appendChild(previewDiv);

                            previewContainer.style.display = 'flex';
                            previewContainer.style.flexWrap = 'wrap';
                            previewContainer.appendChild(wrapperDiv);

                        }

                        reader.readAsDataURL(file);
                        const newLabel = input.parentNode.querySelector('.custom-file-label');
                        if (newLabel) {
                            newLabel.textContent = file.name;
                        }
                    }
                }
            });
        }

        function removeFile(input, file) {
            const files = input.files;
            const newFiles = [];
            for (let i = 0; i < files.length; i++) {
                if (files[i] !== file) {
                    newFiles.push(files[i]);
                }
            }
            input.files = newFiles;
        }

        const addImageInput = document.getElementById('addImageInput');
        const additionalPreviews = document.getElementById('additionalPreviews');

        addImageInput.addEventListener('click', function() {
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.className = 'custom-file-input';
            newInput.setAttribute('accept', 'image/*');
            newInput.name = 'images[]';

            const newLabel = document.createElement('label');
            newLabel.className = 'custom-file-label';
            newLabel.textContent = 'Input Hasil Foto dari Layanan...';

            const newInputGroup = document.createElement('div');
            newInputGroup.className = 'col-lg-4 col-md-6 col-sm-6 mb-3';

            const customFileDiv = document.createElement('div');
            customFileDiv.className = 'custom-file';
            customFileDiv.appendChild(newInput);
            customFileDiv.appendChild(newLabel);
            newInputGroup.appendChild(customFileDiv);

            additionalImages.appendChild(newInputGroup);

            addImagePreviewForNewInput(newInput, additionalPreviews);
        });
    </script>

    {{-- print foto --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printPhotosSwitch = document.getElementById('print_photos_switch');
            const printPhotosOptions = document.getElementById('print_photos_options');
            const checkAllButton = document.querySelector('[data-toggle="check-all"]');
            const uncheckAllButton = document.querySelector('[data-toggle="uncheck-all"]');
            const printPhotoCheckboxes = document.querySelectorAll('input[name="print_photos[]"]');

            printPhotosSwitch.addEventListener('change', function() {
                if (this.checked) {
                    printPhotosOptions.style.display = 'block';
                } else {
                    printPhotosOptions.style.display = 'none';
                    printPhotoCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = false;
                        hidePriceInput(checkbox);
                    });
                }
            });

            checkAllButton.addEventListener('click', function() {
                printPhotoCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                    showPriceInput(checkbox);
                });
            });

            uncheckAllButton.addEventListener('click', function() {
                printPhotoCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    hidePriceInput(checkbox);
                });
            });
            printPhotoCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        showPriceInput(this);
                    } else {
                        hidePriceInput(this);
                    }
                });
            });

            function showPriceInput(checkbox) {
                if (checkbox.parentNode.querySelector('.input-group') === null) {
                    const priceInputGroup = document.createElement('div');
                    priceInputGroup.setAttribute('class', 'input-group');

                    const prependSpan = document.createElement('span');
                    prependSpan.setAttribute('class', 'input-group-text');
                    prependSpan.textContent = 'Rp';

                    const priceInput = document.createElement('input');
                    priceInput.setAttribute('type', 'text');
                    priceInput.setAttribute('class', 'form-control');
                    priceInput.setAttribute('placeholder', 'Harga cetak ukuran...');
                    priceInput.setAttribute('name', 'price_' + checkbox.value);
                    priceInput.setAttribute('onkeypress', 'return event.charCode >= 48 && event.charCode <= 57');

                    priceInput.addEventListener('input', function() {
                        this.value = formatCurrency(this.value);
                    });

                    priceInputGroup.appendChild(prependSpan);
                    priceInputGroup.appendChild(priceInput);

                    checkbox.parentNode.appendChild(priceInputGroup);
                }
            }

            function formatCurrency(number) {
                let formattedNumber = number.replace(/\D/g, '');
                formattedNumber = formattedNumber.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                return formattedNumber;
            }

            function hidePriceInput(checkbox) {
                const priceInputGroup = checkbox.parentNode.querySelector('.input-group');
                if (priceInputGroup) {
                    priceInputGroup.remove();
                }
            }
        });
    </script>
@endpush
