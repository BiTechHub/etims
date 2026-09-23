@include('admin.layouts.header')

@yield('main-section')

@include('admin.layouts.footer')

@push('scripts')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
@endpush

@stack('scripts')

@yield('script')