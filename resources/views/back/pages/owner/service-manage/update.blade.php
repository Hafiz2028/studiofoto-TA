@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Event Service')
@section('content')

    <div class="page-header">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-dark">Edit Event Service</h4>
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
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.venue.services.show', ['venue' => $venue->id, 'service' => $service->id]) }}">Detail
                        Layanan</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Edit Event Service
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-primary">Edit Event Service</h4>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('owner.venue.services.show', ['venue' => $venue->id, 'service' => $service->id]) }}"
                            class="btn btn-outline-info btn-sm">
                            <i class="ion-arrow-left-a"></i> Kembali
                        </a>
                    </div>
                </div>
                <hr>

                <form
                    action="{{ route('owner.venue.services.update', ['venue' => $venue->id, 'service' => $service->id]) }}"
                    method="POST" enctype="multipart/form-data" class="mt-3">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <x-alert.form-alert />
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Nama Layanan</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Contoh: Foto Keluarga, Foto Wisuda"
                                        value="{{ $service->name }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="service_type_id">Jenis Layanan</label>
                                    <select class="form-control @error('service_type_id') is-invalid @enderror"
                                        id="service_type_id" name="service_type_id">
                                        <option value="">Pilih Jenis Layanan</option>
                                        @foreach ($serviceTypes as $serviceType)
                                            <option value="{{ $serviceType->id }}"
                                                @if ($serviceType->id == $service->service_type_id) selected @endif>
                                                {{ $serviceType->service_name }}</option>
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
                                    <label for="print_photos_switch">Print Foto Layanan</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="print_photos_switch"
                                            name="print_photos_switch"
                                            {{ $printServiceEvents->isNotEmpty() ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="print_photos_switch">Aktifkan Untuk Pilih
                                            Ukuran Print Foto</label>
                                    </div>
                                </div>
                                <div id="print_photos_options"
                                    {{ $service->print_photos_switch || $printServiceEvents->isNotEmpty() ? 'style=display:block;' : 'style=display:none;' }}>
                                    <label for="print_photos">Pilih Ukuran Foto & Harga jika layanan ini bisa Print
                                        Foto.</label><br>
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <button id="check-all-button" type="button"
                                                class="btn btn-outline-success mr-2" data-toggle="check-all"
                                                title="Ceklis semua ukuran foto"><i class="bi bi-check-all"></i></button>
                                            <button id="uncheck-all-button" type="button" class="btn btn-outline-danger"
                                                data-toggle="uncheck-all" title="Uncheck semua ukuran foto"><i
                                                    class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @php
                                            $columnCount = 3;
                                            $rowCount = ceil(count($printPhotos) / $columnCount);
                                        @endphp
                                        @for ($i = 0; $i < $rowCount; $i++)
                                            <div class="col-md-{{ 12 / $columnCount }}">
                                                @for ($j = $i * $columnCount; $j < min(($i + 1) * $columnCount, count($printPhotos)); $j++)
                                                    @php $printPhoto = $printPhotos[$j]; @endphp
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="print_photo_{{ $printPhoto->id }}" name="print_photos[]"
                                                            value="{{ $printPhoto->id }}"
                                                            @if ($printServiceEvents->contains('print_photo_id', $printPhoto->id)) checked @endif>
                                                        <label class="custom-control-label"
                                                            for="print_photo_{{ $printPhoto->id }}">{{ $printPhoto->size }}</label>
                                                        <div class="input-group" style="margin-top: 5px;">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="text" class="form-control"
                                                                placeholder="Harga cetak ukuran ini..."
                                                                name="prices[{{ $printPhoto->id }}]"
                                                                value="{{ $printServiceEvents->firstWhere('print_photo_id', $printPhoto->id)->price ?? '' }}">
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-6">
                                <div class="mb-0">
                                    <label>Preview Katalog Harga</label>
                                </div>
                                <div id="catalogPreview" class="image-preview mb-3">
                                    <img id="previewImage" src="/images/venues/Katalog/{{ $service->catalog }}"
                                        alt="" style="max-width: 180px;">
                                </div>
                                <div class="form-group">
                                    <label for="newCatalogInput" class="btn btn-outline-primary">Update
                                        Catalog</label>
                                    <input type="file" name="new_catalog" id="newCatalogInput" style="display: none;"
                                        onchange="updateCatalogPreview(this)">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="description">Deskripsi Layanan</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="4" placeholder="Isi deskripsi untuk venue">{{ $service->description }}</textarea>
                                    @error('description')
                                        <span class="text-danger ml-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row mb-3 px-3"
                                    style="display: flex; justify-content: space-between; align-items: center;">
                                    <label style="margin-right: auto;">Preview Hasil Foto Layanan :</label>
                                    <a href="javascript:void(0);" onclick="addPhoto()"
                                        class="btn btn-outline-primary btn-md">
                                        <i class="fas fa-plus"></i> Tambah gambar
                                    </a>
                                </div>

                                <div class="row" id="imageContainer">
                                    <input type="hidden" name="deletedImageIds" id="deletedImageIdsInput"
                                        value="">
                                    <!-- Gambar-gambar dari perulangan -->
                                    @foreach ($serviceEventImages as $key => $serviceEventImage)
                                        <div class="col-lg-3 col-md-4 col-sm-6 clone-{{ $key + 1 }}"
                                            id="clone-{{ $key + 1 }}">
                                            <div class="fileinput fileinput-exist" data-provides="fileinput">
                                                <div class="fileinput-new img-thumbnail"
                                                    style="width: 300px; height: 200px; position: relative; overflow: hidden;">
                                                    <img src="{{ asset('images/venues/Service_Image/' . $serviceEventImage->image) }}"
                                                        style="width:auto; height:100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                        alt="...">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists img-thumbnail"
                                                    style="max-width: 300px; max-height: 200px;"></div>
                                                <div>
                                                    <a href="javascript:void(0);" onclick="delClone(this)"
                                                        class="btn btn-outline-danger btn-block">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </a>
                                                    <input type="hidden" name="image_to_keep[]"
                                                        value="{{ $serviceEventImage->id }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12 mt-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary float-right">Update Layanan</button>
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

        #imageContainer .col-lg-3,
        #additionalImageInput .col-lg-3 {
            flex: 1;
            max-width: 25%;
            padding: 0 5px;
            /* Untuk memberi jarak antar kolom */
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
    {{-- update input foto --}}
    <script>
        var deletedImageIds = [];
        function addPhoto() {
            var imageContainer = $("#imageContainer");
            var lastClone = imageContainer.find("[id^='clone-']").last();

            var newCloneNumber = 1;
            if (lastClone.length !== 0) {
                var lastCloneId = lastClone.attr("id");
                var lastCloneNumber = parseInt(lastCloneId.replace("clone-", ""));
                newCloneNumber = lastCloneNumber + 1;
            }

            var fileInputSize = $("<div class='col-lg-3 col-md-4 col-sm-6 clone-" + newCloneNumber +
                "' id='clone-" + newCloneNumber + "' data-id='" + newCloneNumber + "'></div>");
            var fileInputDiv = $("<div class='fileinput fileinput-new' data-provides='fileinput'></div>");
            var defaultImage = $(
                "<div class='fileinput-new img-thumbnail default-photo' style='width: 300px; height: 200px; position: relative; overflow: hidden;'>\
                                                    <img id='previewImage-" +
                newCloneNumber +
                "' src='/images/venues/upload.png' style='width:auto; height:100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);' alt='Preview Image'>\
                                                </div>"
            );
            fileInputDiv.append(defaultImage);
            var fileInputSpan = $(
                "<span class='btn btn-outline-success btn-file btn-block'><span class='fileinput-new'>Choose Image</span><input type='file' name='image_venue[]' style='display: none;'></span>"
            );
            fileInputDiv.append(fileInputSpan);
            var deleteButton = $(
                "<a href='javascript:void(0);' onclick='delClone(this)' class='btn btn-outline-danger btn-block'>\
                                                    <i class='fas fa-trash'></i> Delete\
                                                </a>"
            );
            fileInputDiv.append(deleteButton);
            var imageToDeleteInput = $("<input type='hidden' name='image_to_delete[]'>");
            var imageToKeepInput = $("<input type='hidden' name='image_to_keep[]'>");
            fileInputDiv.append(imageToDeleteInput);
            fileInputDiv.append(imageToKeepInput);



            fileInputSize.append(fileInputDiv);
            if (lastClone.length === 0) {
                imageContainer.append(fileInputSize);
            } else {
                lastClone.after(fileInputSize);
            }

            var imageInput = fileInputSpan.find("input[type='file']");
            var previewImage = defaultImage.find("img");

            fileInputSpan.find(".fileinput-new").on('click', function() {
                imageInput.click();
            });

            imageInput.on('change', function() {
                fileInputSpan.find('.fileinput-new').text(imageInput[0].files[0].name);
                previewImage.attr('src', URL.createObjectURL(this.files[0]));
            });
        }

        function delClone(button) {
            var cloneDiv = $(button).closest("[id^='clone-']");
            var cloneId = cloneDiv.attr("id");
            var imageToKeepInput = cloneDiv.find("input[name='image_to_keep[]']");
            var imageId = imageToKeepInput.val();

            // Simpan ID foto yang dihapus
            if (!imageId) {
                var imageToDeleteInput = cloneDiv.find("input[name='image_to_delete[]']");
                imageId = imageToDeleteInput.val();
            }

            // Jika imageId ditemukan, tambahkan ke dalam deletedImageIds
            if (imageId) {
                deletedImageIds.push(imageId);
            }

            // Hapus dari tampilan HTML
            cloneDiv.remove();

            // Update IDs and classes for remaining clones
            updateCloneIds();

            $('#deletedImageIdsInput').val(JSON.stringify(deletedImageIds));
        }

        function updateCloneIds() {
            var imageContainer = $("#imageContainer");
            imageContainer.children("[id^='clone-']").each(function(index) {
                var cloneNumber = index + 1;
                var newId = "clone-" + cloneNumber;
                $(this).attr("id", newId).removeClass().addClass("col-lg-3 col-md-4 col-sm-6 clone-" +
                    cloneNumber);
            });
        }

        function previewImage(input, index) {
            console.log("Preview image function called for index: " + index);
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    console.log("FileReader onload event triggered for index: " + index);
                    var previewImage = $('#previewImage-' + index);
                    console.log("Preview image element: ", previewImage);
                    if (previewImage.length) {
                        previewImage.attr('src', e.target.result);
                    } else {
                        var newPreviewImage = $("<img id='previewImage-" + index +
                            "' class='img-thumbnail' style='width: auto; height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);' alt='Preview Image'>"
                        );
                        newPreviewImage.attr('src', e.target.result);
                        $('#clone-' + index).find('.fileinput-new').prepend(newPreviewImage);
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
            updateButtonText(input);
        }

        function updateButtonText(input) {
            var fileInput = input.files[0];
            var fileinputNew = $(input).closest('.btn-file').find('.fileinput-new');
            if (fileinputNew.text() === 'Choose Image' && fileInput) {
                fileinputNew.text('Change Image');
                $(input).closest('.btn').removeClass('btn-outline-success').addClass('btn-outline-info');
            }
        }

        function validateImage(input) {
            var filePath = input.value;
            var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
            if (!allowedExtensions.exec(filePath)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tipe Gambar Salah',
                    text: 'Hanya bisa input gambar format .jpg .jpeg .png.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        input.value = '';
                    }
                });
                return false;
            } else {
                updateButtonText(input);
            }
        }
    </script>

    {{-- catalog --}}
    <script>
        function updateCatalogPreview(input) {
            var preview = document.getElementById('previewImage');
            var file = input.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
            }

            reader.readAsDataURL(file);
        }
    </script>
    {{-- validasi --}}
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     const inputFile = document.getElementById('imagesInput');

        //     function validateImages(input) {
        //         const files = input.files;
        //         const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        //         const maxSize = 5000;

        //         for (let i = 0; i < files.length; i++) {
        //             const file = files[i];
        //             const fileSize = file.size / 1024;

        //             if (!allowedExtensions.test(file.name)) {
        //                 alert('File harus berupa gambar (format JPG, JPEG, PNG, GIF).');
        //                 input.value = '';
        //                 return false;
        //             }

        //             if (fileSize > maxSize) {
        //                 alert('Ukuran file tidak boleh melebihi 5000 KB.');
        //                 input.value = '';
        //                 return false;
        //             }
        //         }

        //         return true;
        //     }

        //     inputFile.addEventListener('change', function() {
        //         validateImages(inputFile);
        //     });
        // });
    </script>


    {{-- print foto --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printPhotosSwitch = document.getElementById('print_photos_switch');
            const printPhotosOptions = document.getElementById('print_photos_options');
            const checkAllButton = document.getElementById('check-all-button');
            const uncheckAllButton = document.getElementById('uncheck-all-button');
            const printPhotoCheckboxes = document.querySelectorAll('input[name="print_photos[]"]');
            const serviceId = '{{ $service->id }}';
            const printServiceEvents = @json($printServiceEvents);

            // Fungsi untuk menampilkan atau menyembunyikan kolom harga
            function showOrHidePriceInput(checkbox) {
                const priceInputGroup = checkbox.parentNode.querySelector('.input-group');
                const isChecked = checkbox.checked;
                if (isChecked) {
                    if (priceInputGroup === null) {
                        createPriceInput(checkbox);
                    }
                } else {
                    if (priceInputGroup !== null) {
                        priceInputGroup.remove();
                        deletePriceData(checkbox); // Hapus data harga saat unchecked
                    }
                }
            }

            // Fungsi untuk membuat kolom harga
            function createPriceInput(checkbox) {
                const printPhotoId = checkbox.value;
                const priceInputGroup = document.createElement('div');
                priceInputGroup.setAttribute('class', 'input-group');
                const prependSpan = document.createElement('span');
                prependSpan.setAttribute('class', 'input-group-text');
                prependSpan.textContent = 'Rp';
                const priceInput = document.createElement('input');
                priceInput.setAttribute('type', 'text');
                priceInput.setAttribute('class', 'form-control');
                priceInput.setAttribute('placeholder', 'Harga cetak ukuran ini...');
                priceInput.setAttribute('name', 'prices[' + printPhotoId + ']');
                priceInput.setAttribute('onkeypress', 'return event.charCode >= 48 && event.charCode <= 57');
                const printServiceEvent = printServiceEvents.find(function(event) {
                    return event.print_photo_id == printPhotoId;
                });
                priceInput.value = printServiceEvent ? printServiceEvent.price : '';
                priceInputGroup.appendChild(prependSpan);
                priceInputGroup.appendChild(priceInput);
                checkbox.parentNode.appendChild(priceInputGroup);
            }

            // Fungsi untuk menghapus data harga
            function deletePriceData(checkbox) {
                const printPhotoId = checkbox.value;
                // Hapus data harga dari array printServiceEvents
                const index = printServiceEvents.findIndex(function(event) {
                    return event.print_photo_id == printPhotoId;
                });
                if (index !== -1) {
                    printServiceEvents.splice(index, 1);
                }
            }

            // Mengatur tampilan kolom harga saat halaman dimuat
            printPhotoCheckboxes.forEach(function(checkbox) {
                showOrHidePriceInput(checkbox);
            });

            // Mendengarkan perubahan pada kotak centang dan memanggil fungsi yang sesuai
            printPhotoCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    showOrHidePriceInput(this);
                });
            });

            // Menampilkan atau menyembunyikan opsi cetak foto sesuai dengan keadaan switch
            printPhotosSwitch.addEventListener('change', function() {
                if (this.checked) {
                    printPhotosOptions.style.display = 'block';
                } else {
                    printPhotosOptions.style.display = 'none';
                    // Hapus semua data harga jika switch dinonaktifkan
                    printServiceEvents.splice(0, printServiceEvents.length);
                    // Hapus semua elemen HTML yang menampilkan harga
                    printPhotoCheckboxes.forEach(function(checkbox) {
                        const priceInputGroup = checkbox.parentNode.querySelector('.input-group');
                        if (priceInputGroup !== null) {
                            priceInputGroup.remove();
                        }
                    });
                }
            });

            // Menandai semua kotak centang ketika tombol "Ceklis semua" diklik
            checkAllButton.addEventListener('click', function() {
                printPhotoCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                    showOrHidePriceInput(checkbox);
                });
            });

            // Membatalkan tanda centang semua kotak ketika tombol "Hapus semua" diklik
            uncheckAllButton.addEventListener('click', function() {
                printPhotoCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    showOrHidePriceInput(checkbox);
                });
            });

            // Menampilkan atau menyembunyikan opsi cetak foto saat halaman dimuat
            if (printServiceEvents && printServiceEvents.length > 0) {
                printPhotosSwitch.checked = true;
                printPhotosOptions.style.display = 'block';
            }
        });
    </script>
@endpush
