@stack('stylesheets')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    #map {
        height: 400px;
        width: 70%;
    }
</style>
<style>
    .custom-switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
    }

    .custom-switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #007bff;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #007bff;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(20px);
        -ms-transform: translateX(20px);
        transform: translateX(20px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 20px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>


@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : "Venue's Manage")
@section('content')

    <div class="mobile-menu-overlay"></div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Add Venue</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            {{-- <a href="{{ route('admin.home') }}">Home</a> --}}
                            <a href="">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Add Venue
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    {{-- real --}}
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <h4 class="text-blue h4">Add New Venue</h4>
        </div>
        <br>
        @livewire('venue.add-venue-tabs')


    </div>
@endsection

@stack('scripts')

<script>
    function checkAll(dayName, event) {
        event.preventDefault();
        $('#' + dayName + '-schedule input[type="checkbox"]').prop('checked', true);
    }

    function uncheckAll(dayName, event) {
        event.preventDefault();
        $('#' + dayName + '-schedule input[type="checkbox"]').prop('checked', false);
    }

    function toggleScheduleInput(day, event) {
        let scheduleDiv = document.getElementById(day + '-schedule');
        let isChecked = event.target.checked;
        if (isChecked) {
            scheduleDiv.style.display = 'block';
        } else {
            scheduleDiv.style.display = 'none';
        }
        // Jika tombol switch on ditekan, tampilkan jadwal di hari tersebut
        if (isChecked) {
            showSchedule(day);
        }
    }

    function showSchedule(day) {
        let checkboxes = document.querySelectorAll('#' + day + '-schedule input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.parentElement.style.display = 'block';
        });
    }

    function checkWorkingHours(day, event) {
        event.preventDefault(); // Mencegah tindakan default
        // Loop through checkboxes for working hours (ids 16 to 45)
        for (let i = 16; i <= 45; i++) {
            document.getElementById(day + '-' + i).checked = true;
        }
    }

    function copySchedule(currentDay, nextDay, event) {
        event.preventDefault(); // Mencegah tindakan default

        // Copy schedule from current day to next day
        let checkboxes = document.querySelectorAll('#' + currentDay + '-schedule input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            let nextCheckbox = document.getElementById(nextDay + '-' + checkbox.id.split('-')[1]);
            if (nextCheckbox) {
                nextCheckbox.checked = checkbox.checked;
            }
        });

        // Switch on next day's schedule
        let nextDayToggle = document.getElementById(nextDay + '-toggle');
        if (nextDayToggle) {
            nextDayToggle.checked = true;
            // Tampilkan jadwal untuk hari berikutnya
            toggleScheduleInput(nextDay, {
                target: nextDayToggle
            });
        }
    }
</script>
<script>
    function addAdditionalPhoto() {
        // Create new input element for additional photo
        var additionalPhotoInput = document.createElement('input');
        additionalPhotoInput.type = 'file';
        additionalPhotoInput.className = 'form-control';
        additionalPhotoInput.setAttribute('data-delete', 'true'); // Mark input for deletion
        additionalPhotoInput.addEventListener('change', handleAdditionalPhotoChange);

        // Create button to remove additional photo input
        var removeButton = document.createElement('button');
        removeButton.className = 'btn btn-outline-danger';
        removeButton.type = 'button';
        removeButton.innerHTML = '&times;';
        removeButton.addEventListener('click', removeAdditionalPhoto);

        // Create div to contain additional photo input and remove button
        var inputGroup = document.createElement('div');
        inputGroup.className = 'input-group mb-3';
        inputGroup.appendChild(additionalPhotoInput);
        inputGroup.appendChild(removeButton);

        // Append div to additional photos section
        var additionalPhotos = document.getElementById('additionalPhotos');
        additionalPhotos.appendChild(inputGroup);
    }

    function removeAdditionalPhoto(event) {
        var inputGroup = event.target.parentNode;
        var additionalPhotos = document.getElementById('additionalPhotos');
        additionalPhotos.removeChild(inputGroup);
    }

    function handleAdditionalPhotoChange(event) {
        // Implement your additional photo handling logic here
    }
</script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
{{-- <script>
                            var map = L.map('map').setView([-0.9234, 100.4474], 20);
                            L.tileLayer('https://api.maptiler.com/maps/streets/{z}/{x}/{y}.png?key=SzhEAidXbTEomDww4vrj', {
                                attribution: '&copy; <a href="https://www.maptiler.com/">MapTiler</a> contributors',
                                maxZoom: 25
                            }).addTo(map);
                            var marker;

                            function onMapClick(e) {
                                if (marker) {
                                    map.removeLayer(marker);
                                }
                                marker = L.marker(e.latlng).addTo(map);
                                document.getElementById('latitude').value = e.latlng.lat.toFixed(4);
                                document.getElementById('longitude').value = e.latlng.lng.toFixed(4);
                                fetch('https://api.maptiler.com/geocoding/reverse?lat=' + e.latlng.lat + '&lon=' + e.latlng.lng +
                                        '&key=SzhEAidXbTEomDww4vrj')
                                    .then(response => response.json())
                                    .then(data => {
                                        document.getElementById('address').value = data.features[0].properties.street + ', ' + data
                                            .features[0].properties.locality;
                                    });
                            }
                            map.on('click', onMapClick);
                            document.getElementById('myLocationButton').addEventListener('click', findMyLocation);

                            function findMyLocation(event) {
                                event.preventDefault(); // Mencegah perilaku default tombol
                                map.locate({
                                    setView: true,
                                    maxZoom: 25
                                });
                            }

                            function onLocationFound(e) {
                                if (marker) {
                                    map.removeLayer(marker);
                                }
                                marker = L.marker(e.latlng).addTo(map);
                                document.getElementById('latitude').value = e.latlng.lat.toFixed(4);
                                document.getElementById('longitude').value = e.latlng.lng.toFixed(4);
                                fetch('https://api.maptiler.com/geocoding/reverse?lat=' + e.latlng.lat + '&lon=' + e.latlng.lng +
                                        '&key=SzhEAidXbTEomDww4vrj')
                                    .then(response => response.json())
                                    .then(data => {
                                        document.getElementById('address').value = data.features[0].properties.street + ', ' + data
                                            .features[0].properties.locality;
                                    });
                            }
                            map.on('locationfound', onLocationFound);
                        </script> --}}
