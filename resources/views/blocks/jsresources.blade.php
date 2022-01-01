<!-- JQUERY LIBRARY -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<!-- BOOTSTRAP LIBRARIES -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- DATATABLES LIBRARIES -->
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>

<!-- MOMENT JS LIBRARY -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- DATETIMEPICKER BOOTSTRAP LIBRARY -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<!-- JQUERY VALIDATOR LIBRARIES -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js" type="text/javascript"></script>
<script src="http://test-system-web-app/js/validator.js"></script>

<!-- TOASTR LIBRARIES -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.js.map"></script>

<!-- INTERNAL FILES -->
<script src="http://test-system-web-app/js/utils.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

@if (\Illuminate\Support\Facades\Request::is('questions/*'))
    <script src="http://test-system-web-app/js/questions.js"></script>
@endif

@if (\Illuminate\Support\Facades\Request::is('tests/*'))
    <script src="http://test-system-web-app/js/tests.js"></script>
@endif

@if (\Illuminate\Support\Facades\Request::is('users/*'))
    <script src="http://test-system-web-app/js/users.js"></script>
@endif

@if (\Illuminate\Support\Facades\Request::is('testexecution/*'))
    <script src="http://test-system-web-app/js/testexecution.js"></script>
@endif

