@include('admin.layouts.header')

@yield('main-section')

@include('admin.layouts.footer')

<script src="{{ asset('assets/js/select2.min.js') }}"></script>
@yield('script')