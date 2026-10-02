<!-- Core Scripts -->
<script src="{{ asset('build/assets/app-DIZEten-.js') }}"></script>
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

@stack('vendor-scripts')
@yield('vendor-scripts')

@stack('page-scripts')
@stack('script')