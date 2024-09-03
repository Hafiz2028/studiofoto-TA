@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Profile Page')
@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="/front/img/tomat.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Profile {{ ucwords($user->name) }}</h2>
                        <div class="breadcrumb__option">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Profile</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
    @livewire('customer-profile')



@endsection
@push('styles')
@endpush
@push('scripts')
    <script>
        window.addEventListener('updateCustomerInfo', function(event) {
            $('#customerProfileName').html(event.detail.customerName);
            $('#customerProfileEmail').html(event.detail.customerEmail);
        });
        $('input[type="file"][id="customerProfilePictureFile"]').ijaboCropTool({
            preview: '#customerProfilePicture',
            setRatio: 1,
            allowedExtensions: ['jpg', 'jpeg', 'png'],
            buttonsText: ['CROP', 'QUIT'],
            buttonsColor: ['#30bf7d', '#ee5155', -15],
            processUrl: '{{ route('customer.change-profile-picture') }}',
            withCSRF: ['_token', '{{ csrf_token() }}'],
            onSuccess: function(message, element, status) {
                Livewire.dispatch('updateCustomerHeaderInfo');
                toastr.success(message);
            },
            onError: function(message, element, status) {
                toastr.error(message);
            }
        });
    </script>
@endpush
