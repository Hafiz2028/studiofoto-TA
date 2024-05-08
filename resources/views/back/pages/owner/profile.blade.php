@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Owner Profile')
@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Profile</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Profile
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    @livewire('owner-profile')


@endsection
@push('styles')
@endpush
@push('scripts')

    <script>
        window.addEventListener('updateOwnerInfo', function(event) {
            $('#ownerProfileName').html(event.detail.ownerName);
            $('#ownerProfileEmail').html(event.detail.ownerEmail);
        });
        $('input[type="file"][name="ownerProfilePictureFile"][id="ownerProfilePictureFile"]').ijaboCropTool({
            preview: '#ownerProfilePicture',
            setRatio: 1,
            allowedExtensions: ['jpg', 'jpeg', 'png'],
            buttonsText: ['CROP', 'QUIT'],
            buttonsColor: ['#30bf7d', '#ee5155', -15],
            processUrl: '{{ route('owner.change-profile-picture') }}',
            withCSRF: ['_token', '{{ csrf_token() }}'],
            onSuccess: function(message, element, status) {
                Livewire.dispatch('updateAdminOwnerHeaderInfo');
                toastr.success(message);
            },
            onError: function(message, element, status) {
                toastr.error(message);
            }
        });
        $('input[type="file"][name="ownerKtpImageFile"][id="ownerKtpImageFile"]').ijaboCropTool({
            preview: '#ownerKtpImage',
            setRatio: 85 / 53,
            allowedExtensions: ['jpg', 'jpeg', 'png'],
            buttonsText: ['CROP', 'QUIT'],
            buttonsColor: ['#30bf7d', '#ee5155', -15],
            processUrl: '{{ route('owner.change-ktp-image') }}',
            withCSRF: ['_token', '{{ csrf_token() }}'],
            onSuccess: function(message, element, status) {
                toastr.success(message);
            },
            onError: function(message, element, status) {
                toastr.error(message);
            }
        });
    </script>
@endpush
